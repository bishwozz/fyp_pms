<?php

namespace App\Http\Controllers\Admin\Pms;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pms\Item;
use App\Utils\PdfPrint;
use App\Models\Pms\SupStatus;
use App\Models\Pms\PoSequence;
use App\Models\Pms\MstDiscMode;
use App\Models\Pms\MstSupplier;
use App\Models\Pms\PurchaseItem;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\Pms\PurchaseOrderDetail;
use App\Http\Requests\PurchaseOrderDetailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PurchaseOrderDetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PurchaseOrderDetailCrudController extends BaseCrudController
{


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    protected $user;

    public function __construct(PurchaseItem $purchaseItem, PurchaseOrderDetail $purchaseOrderDetail)
    {
        parent::__construct();
        $this->purchaseItem = $purchaseItem;
        $this->purchaseOrderDetail = $purchaseOrderDetail;
    }


    public function setup()
    {
        $this->user = backpack_user();
        $this->crud->enableExportButtons();
        CRUD::setModel(\App\Models\Pms\PurchaseOrderDetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/purchase-order-detail');
        CRUD::setEntityNameStrings('', 'Purchase Order');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $cols = [
            $this->addRowNumberColumn(),
            [
                'name' => 'supplier_id',
                'label'=>'Supplier',
                'type' => 'select',
                'entity' => 'supplierEntity',
                'attribute' => 'name',
                'model' => MstSupplier::class,

            ],
            [
                'name' => 'purchase_order_num',
                'type' => 'text',
                'label' => 'PO Number',

            ],
     
            [
                'name' => 'po_date',
                'label' => 'PO Date',
                'type' => 'nepali_date',
                'attributes' => [
                    'id' => 'date_bs',
                    'relatedId' => 'date_ad'
                ],
            ],
            [
                'name' => 'expected_delivery',
                'type' => 'text',
                'label' => 'Expected Delivery',

            ],
           
            [
                'name' => 'approved_by',
                'type' => 'select',
                'label' => 'Approved By',
                'entity' => 'approvedByEntity',
                'attribute' => 'name',
                'model' => User::class,

            ],
            [
                'name' => 'status_id',
                'type' => 'select',
                'entity' => 'statusEntity',
                'attribute' => 'name_en',
                'model' => SupStatus::class,
            ],
            [
                'name' => 'net_amt',
                'type' => 'text',
                'label' => 'Net Amt',

            ],
        ];
        $this->crud->addColumns(array_filter($cols));
        $this->crud->allowAccess('show');

    }

 
    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;

        $discount_modes = MstDiscMode::all();

        $suppliers = MstSupplier::where('is_active', true)
            ->select('id', 'name')
            ->get();


        $contact = User::where('id', backpack_user()->id)
            ->select('email')
            ->get();

        $created_by = backpack_user()->name;
        $item_lists = $this->getItemList();

        $this->data['item_lists'] = $item_lists;
        $this->data['suppliers'] =  $suppliers;
        $this->data['contact'] = $contact;
        $this->data['created_by'] = $created_by;
        $this->data['discount_modes'] = $discount_modes;
        $this->data['item_lists'] = $this->getItemList();
        // dd($this->getItemList());
        $this->data['purchaseOrderNumbers'] = $this->getsequenceCode(4);
        $this->data['sequenceCodes'] = $this->sequence_type();

        return view('customAdmin.purchaseOrder.purchase_order', $this->data);
    }
    public function store()
    {

        $this->crud->hasAccessOrFail('create');

        $request = $this->crud->validateRequest();
        if (isset($request)) {
            $purchaseOrderDetails = $request->only([
                'status_id',
                'expected_delivery',
                'approved_by',
                'gross_amt',
                'discount_amt',
                'tax_amt',
                'other_charges',
                'net_amt',
                'comments',
                'supplier_id',
            ]);

            // $sequenceCodes = $request->only('purchase_order_num');
            // dd($sequenceCodes);

            if ($request->status_id == SupStatus::APPROVED) {
                if($pod['status_id']==2){
                    $query = DB::table('purchase_order_details')->where('client_id',$this->user->client_id)->latest('created_at')->pluck('purchase_order_num')->first();
                    $prefix_key = appSetting()->purchase_order_seq_key;
                    $purchase_order_num = $prefix_key.'1';
                    if ($query != null) {
                        $explode = explode('-',$query);
                        $num = end($explode);
                        $purchase_order_num = $prefix_key.(intval($num) + 1);
                    }
                }
        
                $purchaseOrderDetails['purchase_order_num'] = $purchase_order_num;
                $purchaseOrderDetails['po_date'] = dateToday();
                $purchaseOrderDetails['approved_by'] = $this->user->id;
            }

            $purchaseOrderDetails['client_id'] = $this->user->client_id;


            // dd($podId);
            try {
                DB::beginTransaction();
                $podId = PurchaseOrderDetail::create($purchaseOrderDetails);

                foreach ($request->items_id as $key => $val) {
                    $itemArray = [
                        'po_id' => $podId->id,
                        'client_id' => $this->user->client_id,
                        'purchase_qty' => $request->purchase_qty[$key],
                        'free_qty' => $request->free_qty[$key],
                        'total_qty' => $request->total_qty[$key],
                        'discount' => $request->discount[$key],
                        'purchase_price' => $request->purchase_price[$key],
                        'sales_price' => $request->sales_price[$key],
                        'item_amount' => $request->item_amount[$key],
                        'tax_vat' => $request->tax_vat[$key],
                        'items_id' => $request->po_item_name_hidden[$key],
                        'discount_mode_id' => $request->discount_mode_id[$key]
                    ];
                    PurchaseItem::create($itemArray);
                }
                DB::commit();
      
                // \Alert::success(trans('SUCCESS'))->flash();
                // return redirect('admin/purchase-order-detail');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Stock added successfully',
                    'route' => url($this->crud->route)
                ]);
             
                
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 404);
            }
        }
    }
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');


        $po =  $this->purchaseOrderDetail->find($id);

        if (!isset($po))
            abort(404);

        $discount_modes = MstDiscMode::all();
    
        $suppliers = MstSupplier::where('is_active', true)
            ->select('id', 'name')
            ->get();

        $approverList = [
            ['id' => 1, 'name' => 'User 1'],
            ['id' => 2, 'name' => 'User 2'],
            ['id' => 3, 'name' => 'User 3'],
            ['id' => 4, 'name' => 'User 4'],
        ];
        $crud = $this->crud;
        $item_lists = $this->getItemList();
        $purchaseOrderNumbers = $this->getsequenceCode(4);
        $sequenceCodes = $this->sequence_type();

        return view('customAdmin.purchaseOrder.po_details_update', compact('discount_modes', 'item_lists', 'suppliers', 'crud', 'item_lists', 'approverList', 'po', 'purchaseOrderNumbers', 'sequenceCodes'));
    }
    public function update()
    {

        $this->crud->allowAccess('update');

        $request = $this->crud->validateRequest();

        $pod = $request->only([
            'status_id',
            'expected_delivery',
            'approved_by',
            'gross_amt',
            'discount_amt',
            'tax_amt',
            'other_charges',
            'net_amt',
            'comments',
            'supplier_id',
        ]);
        $sequenceCodes = $request->only('purchase_order_num');

        $pod['status_id'] = SupStatus::APPROVED;
        $pod['po_date'] = dateToday();
        // dd($pod['status_id']);
        if($pod['status_id']==2){
            $query = DB::table('purchase_order_details')->where('client_id',$this->user->client_id)->latest('created_at')->pluck('purchase_order_num')->first();
            $prefix_key = appSetting()->purchase_order_seq_key;
            $purchase_order_num = $prefix_key.'1';
            if ($query != null) {
                $explode = explode('-',$query);
                $num = end($explode);
                $purchase_order_num = $prefix_key.(intval($num) + 1);
            }
        }

        $pod['purchase_order_num'] = $purchase_order_num;



        try {
            DB::beginTransaction();


            $currentPo = $this->purchaseOrderDetail->find($this->crud->getCurrentEntryId());

            if ($request->status_id == SupStatus::CANCELLED) {
                $currentPo->update(['status_id' => SupStatus::CANCELLED]);
            }else {
                $initialSupStatus = $currentPo->status_id;

                // if ($initialSupStatus == SupStatus::APPROVED && !$this->user->is_po_approver) abort(401, "masdjasdsa");
                if ($request->status_id == SupStatus::APPROVED &&  $initialSupStatus != SupStatus::APPROVED) {
                    if(empty($sequenceCodes)){
                        return response()->json([
                            'status' => 'failed',
                            'message' => "Failed to approve stock. Sequence Codes are not available"
                        ]);
                    }elseif(!array_key_exists('purchase_order_num',$sequenceCodes)){
                        return response()->json([
                            'status' => 'failed',
                            'message' => "Failed to approve stock. Stock Adjustment Sequence is not created."
                        ]);
                    }

                    $stockInput['purchase_order_num'] = $sequenceCodes['purchase_order_num'];
                    $pod['po_date'] = dateToday();
                    $pod['approved_by'] = $this->user->id;

                }


                $currentPo->update($pod);

                $poItems = [];

                foreach ($request->items_id as $key => $val) {
                    $itemArray = [
                        'po_id' =>  $this->crud->getCurrentEntryId(),
                        'client_id' => $this->user->client_id,
                        'purchase_qty' => $request->purchase_qty[$key],
                        'free_qty' => $request->free_qty[$key],
                        'total_qty' => $request->total_qty[$key],
                        'discount' => $request->discount[$key],
                        'purchase_price' => $request->purchase_price[$key],
                        'sales_price' => $request->sales_price[$key],
                        'item_amount' => $request->item_amount[$key],
                        'tax_vat' => $request->tax_vat[$key],
                        'items_id' => $request->po_item_name_hidden[$key],
                        'discount_mode_id' => $request->discount_mode_id[$key]
                    ];
                    array_push($poItems, $itemArray);
                }
                // dd($poItems);
                $this->purchaseItem->destroy($currentPo->purchase_items->pluck('id'));
                $p =  $this->purchaseItem->insert($poItems);
                // dd($p);
            }




            DB::commit();
            \Alert::success(trans('SUCCESS'))->flash();
            return redirect('admin/purchase-order-detail');
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Stock added successfully',
            //     'route' => url($this->crud->route)
            // ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        // dd("jhfvhjhjfd");
        $this->crud->hasAccessOrFail('delete');
        try {
            DB::beginTransaction();

            $id = $this->crud->getCurrentEntryId() ?? $id;
            $pod = $this->purchaseOrderDetail->find($id);
            $relatedPoItemIds = $pod->purchase_items->pluck('id');
            $this->purchaseItem->destroy($relatedPoItemIds);
            $this->purchaseOrderDetail->destroy($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    // protected function setupUpdateOperation()
    // {
    //     $this->setupCreateOperation();
    // }



    public function poDetails(Item $item)
    {

        $taxRate = $item->tax_vat;
        return response()->json([
            'taxRate' => $taxRate
        ]);
    }
    public function purchaseOrderHistoryDetails($id, $from, $to)
    {

        $data = $this->purchaseOrderDetail::with('purchase_items')
            ->join('purchase_items as pi', 'purchase_order_details.id', 'pi.po_id')
            ->where('pi.items_id', $id)
            ->where('purchase_order_details.status_id', SupStatus::APPROVED)
            ->whereBetween('purchase_order_details.po_date', [$from, $to])
            ->get();
        return view('partial.po_history', compact('data'));
    }

    public function getContactDetails($id, Request $request)
    {
        if ($request->flag === 'supplier') {
            $contacts = MstSupplier::where('id', $id)
                ->first();
            $email = $contacts->email;
            $phone = $contacts->phone_number;
        }

        return response()->json([
            'email' => $email,
            'phone' => $phone,
        ]);
    }

    public function listPdfDownload()
    {
        $poDetails = PurchaseOrderDetail::all();
        $view = 'pdfPages.listOperations.purchaseOrder';
        $html = view($view, compact('poDetails'))->render();
        $file_name = 'Purchase Order Type.pdf';
        $res = PdfPrint::printPortrait($html, $file_name);
        return $res;
    }
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

        $data['items'] = $data['entry']->purchase_items;

        $data['crud'] = $this->crud;

        return view('customAdmin.purchaseOrder.show', [
            'entry' => $data['entry'],
            'items' => $data['items'],
            'crud' => $data['crud'],
        ]);
    }

}
