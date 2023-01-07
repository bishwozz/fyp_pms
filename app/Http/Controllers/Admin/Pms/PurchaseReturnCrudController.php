<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Utils\PdfPrint;
use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;
use App\Models\Pms\PurchaseReturn;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Http\Requests\PurchaseReturnRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PurchaseReturnCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PurchaseReturnCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    protected $user;

    public function setup()
    {
        $this->user = backpack_user();
        CRUD::setModel(PurchaseReturn::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/purchase-return');
        CRUD::setEntityNameStrings(' ', 'Purchase Return');

    }

    protected function setupListOperation()
    {
        $cols = [

            [
                'name' => 'supplier_id',
                'type' => 'select',
                'entity' => 'supplierEntity',
                'attribute' => 'name_en',
                'model' => MstSupplier::class,

            ],
            [
                'name' => 'return_date',
                'type' => 'nepali_date',
                'label' => 'Return Date',
                'attributes' => [
                    'id' => 'return_date'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ],
            [
                'name' => 'approved_by',
                'type' => 'select',
                'entity' => 'approvedByEntity',
                'attribute' => 'name',
                'model' => User::class,

            ],

            [
                'name' => 'net_amt',
                'type' => 'number',
                'label' => 'Net Amount',

            ],
            [
                'name' => 'status_id',
                'type' => 'select',
                'entity' => 'statusEntity',
                'attribute' => 'name_en',
                'model' => SupStatus::class,


            ],

        ];
        $this->crud->addColumns(array_filter($cols));
    }

    public function create()
    {

        $this->crud->hasAccessOrFail('create');


        $suppliers = MstSupplier::where('client_id', $this->user->client_id)->pluck('id', 'name_en');
        // $reasons = ReturnReason::whereSupOrgId($this->user->sup_org_id)->whereIsActive(true)->get();
        // $reasons = ReturnReason::whereSupOrgId($this->user->sup_org_id)->whereIsActive(true)->get();
        $reasons = ['1,2'];

        // $item_lists = Item::where('client_id', $this->user->client_id)->where('status_id',2)->get();
        $crud = $this->crud;
        $item_lists = $this->getItemList();

        // $grn_items = Item::where('client_id', $this->user->client_id)->where('status_id',2)->get();


        return view('customAdmin.purchaseReturn.purchase_return', compact('item_lists', 'reasons', 'suppliers','crud'));
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        $request = $this->crud->validateRequest();
        isset($request->return_type) ? $request->return_type = true : $request->return_type = false;
        if (isset($request)) {

            $purchaseReturn = $request->only([
                'store_id',
                'supplier_id',
                'invoice_no',
                'return_reason_id',
                'return_type',
                'discount_amt',
                'net_amt',
                'tax_amt',
                'other_charges',
                'gross_amt',
                'total_discount',
                'taxable_amount',
                'return_no',
                'other_charge',
                'comments',
                'return_no',
                'status_id',
                'grn_id',
            ]);

            if ($request->status_id == SupStatus::APPROVED) {

                if (!$this->user->is_po_approver) abort(401);
                $purchaseReturnNo = $this->setMetaSequesnce('\App\Models\PurchaseReturn', 5, 'return_no');
                // if( $purchaseReturnNo != null){
                //     $purchaseReturn['return_no'] = $purchaseReturnNo;
                // }else{
                //     return response()->json([
                //         'status' => 'failed',
                //         'message' => "Failed to return purchase. Sequence is not Set. "
                //     ]);
                // }

                if($purchaseReturnNo['status'] == 'success'){
                    $purchaseReturn['return_no'] = $purchaseReturnNo['result'];
                }elseif($purchaseReturnNo['status'] == 'error'){
                    return response()->json([
                                'status' => 'failed',
                                'message' => "Failed to create purchase. ".$purchaseReturnNo['result']
                            ]);
                }

                $purchaseReturn['return_date'] = dateToday();
                $purchaseReturn['approved_by'] = $this->user->id;
            }
            $purchaseReturn['sup_org_id'] = $this->user->sup_org_id;

            DB::beginTransaction();
            try {
                $pr = PurchaseReturn::create($purchaseReturn);
                foreach ($request->ItemName as $key => $val) {

                    $itemArray = [
                        'purchase_return_id' => $pr->id,
                        'sup_org_id' => $this->user->sup_org_id,
                        'batch_qty' => $request->BatchQty[$key],
                        'batch_no' => $request->BatchNo[$key],
                        'purchase_qty' => $request->PurchaseQty[$key] ?? 0,
                        'free_qty' => $request->FreeQty[$key] ?? 0,
                        'return_qty' => $request->ReturnQty[$key],
                        'total_qty' => $request->BatchQty[$key],
                        'discount_mode_id' => $request->DiscountMode[$key],
                        'discount' => $request->Discount[$key],
                        'purchase_price' => $request->PurchasePrice[$key],
                        'item_amount' => $request->ItemAmount[$key],
                        'tax_vat' => $request->TaxVat[$key],
                        'mst_items_id' => $request->ItemName_hidden[$key],
                    ];

                    // dd($itemArray);
                    if (isset($itemArray['return_qty']) && $itemArray['return_qty'] > 0) {
                        PurchaseReturnItem::create($itemArray);

                        if ($request->status_id == SupStatus::APPROVED) {

                            $batchQtyDetail = BatchQuantityDetail::where('item_id', $request->ItemName_hidden[$key])->where('batch_no', $request->BatchNo[$key])->select('id', 'batch_qty')->first();
                            $itemQtyDetail = ItemQuantityDetail::where('item_id', $request->ItemName_hidden[$key])->where('store_id', $request->store_id)->where('sup_org_id', backpack_user()->sup_org_id)->select('id', 'item_qty')->first();
                            // dd( $request->ItemName_hidden[4],$request->BatchNo[4]);
                            $newBatchQty = $batchQtyDetail->batch_qty - $request->ReturnQty[$key];
                            $newItemQty = $itemQtyDetail->item_qty - $request->ReturnQty[$key];

                            $itemQtyDetail->item_qty = $newItemQty;
                            $batchQtyDetail->batch_qty = $newBatchQty;

                            $batchQtyDetail->save();
                            $itemQtyDetail->save();
                        }
                    }
                }




                DB::commit();

                Alert::success(trans('backpack::crud.insert_success'))->flash();
                return response()->json([
                    'status' => true,
                    'url' => backpack_url('/purchase-return'),
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                if($th->getMessage() == 'Undefined array key "return_no"'){
                    return response()->json([
                        'status' => 'failed',
                        'message' => "Purchase Return Sequence Already Exists. Try Another One."
                    ]);
                }
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 404);
            }
        }
    }
    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $data = [];
        // get the info for that entry (include softDeleted items if the trait is used)
        if ($this->crud->get('show.softDeletes') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->crud->model))) {
            $data['entry'] = $this->crud->getModel()->withTrashed()->findOrFail($id);
        } else {
            $data['entry'] = $this->crud->getEntry($id);
        }
        // dd($data['entry']);

        $data['items'] = $data['entry']->items;

        $data['crud'] = $this->crud;

        return view('customAdmin.purchaseReturn.show', [
            'entry' => $data['entry'],
            'items' => $data['items'],
            'crud' => $data['crud'],
        ]);
    }

    public function getBatchDetail($itemId, $batchNo)
    {
        $batchDetail = BatchQuantityDetail::whereItemId($itemId)
            ->whereBatchNo($batchNo)
            ->whereSupOrgId($this->user->sup_org_id)
            ->whereStoreId($this->user->store_id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($this->user->sup_org_id,$batchDetail->batch_from);

        if ($batchDetail->batch_from === 'stock-mgmt') {
            $itemDetails = StockItems::whereBatchNo($batchDetail->batch_no)
                ->whereSupOrgId($this->user->sup_org_id)
                ->first();
            $itemDetails->discount_mode = "%";
            $itemDetails->discount_mode_id = 1;
            // dd($itemDetails);
        }
        if ($batchDetail->batch_from === 'grn') {

            $itemDetails = GrnItem::whereBatchNo($batchDetail->batch_no)
                ->whereSupOrgId($this->user->sup_org_id)
                ->first();
                // dd("ok",$itemDetails,$batchDetail->batch_no);
                // dd($itemDetails->discount_mode_id);
            if ($itemDetails->discount_mode_id == 1) {

                $itemDetails->discount_mode = "%";
            }
            if ($itemDetails->discount_mode_id == 2) {
                $itemDetails->discount_mode = "NRS";
            }
        }

        // dd($itemDetails);
        return response()->json([
            'batchDetail' => $batchDetail,
            'itemDetails' => $itemDetails,
        ]);
    }

    public function listPdfDownload()
    {
        $poReturns = PurchaseReturn::all();
        $view = 'pdfPages.listOperations.purchaserETURN';
        $html = view($view, compact('poReturns'))->render();
        $file_name = 'Purchase - Returns.pdf';
        $res = PdfPrint::printPortrait($html, $file_name);
        return $res;
    }
}
