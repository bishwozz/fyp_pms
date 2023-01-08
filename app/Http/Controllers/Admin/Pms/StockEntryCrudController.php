<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\Item;
use App\Models\StockEntry;
use App\Models\StockItems;
use App\Models\Pms\MstItem;
use Illuminate\Http\Request;
use App\Models\Pms\SupStatus;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use App\Models\Pms\StockItemDetails;
use App\Models\Pms\ItemQuantityDetail;
use App\Models\Pms\BatchQuantityDetail;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\StockEntryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StockEntryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockEntryCrudController extends BaseCrudController
{
    /**
     * @var StockEntries
     */
    private $stockEntries;

    /**
     * @var StockItems
     */
    private $stockItems;

    /**
     * @var Backpack User
     */
    private $user;

    private $batchQtyDtl;
    private $itmQtyDtl;
    private $barcodeDetails;



    public function __construct(
        StockEntry $stockEntries,
        StockItems $stockItems,
        StockItemDetails $itemsDetails,
        ItemQuantityDetail $itmQtyDtl,
        BatchQuantityDetail $batchQtyDtl
    ) {
        parent::__construct();

        $this->stockEntries = $stockEntries;
        $this->stockItems = $stockItems;
        $this->itemsDetails = $itemsDetails;
        $this->itmQtyDtl = $itmQtyDtl;
        $this->batchQtyDtl = $batchQtyDtl;
    }



    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\StockEntry::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/stock-entry');
        $this->crud->setEntityNameStrings('stock entry', 'stock entries');
        $this->user = backpack_user();
        // $this->crud->allowAccess('show');
        // $this->crud->allowAccess('delete');
        // $this->crud->sup_status = true;
        // $this->crud->sup_status = true;
    }


    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $columns = [
            [
                'label' => 'Stock Status',
                'type' => 'model_function',
                'function_name' => 'getStockStatus', // the method that defines the relationship in your Model
            ],
            [
                'label' => 'Batch No',
                'type' => 'model_function',
                'function_name' => 'getBatchNo', // the method that defines the relationship in your Model
            ],
            [
                'name' => 'entry_date_ad',
                'type' => 'date',
                'label' => 'Entry date(AD)'
            ],
            [
                'label' => 'Entry date(BS)',
                'type' => 'model_function',
                'function_name' => 'getDateString',
            ],
            [
                'name' => 'net_amount',
                'type' => 'text',
                'label' => 'Stock Amount'
            ],
        ];
        $this->crud->addColumns(array_filter($columns));
        $this->crud->allowAccess('show');
        // $this->crud->allowAccess('delete');

        $this->crud->moveButton('show', 'after', 'delete');
        // if($this->crud->entry){

        // }
    }


    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['sequenceCodes'] = $this->sequence_type();
        $this->data['batchNumbers'] = $this->getSequenceCode(1);
        $this->data['item_lists'] = $this->getItemList();
        $this->data['clientLists'] = $this->getClientList();
        return view('customAdmin.stockEntry.form', $this->data);
    }


 
    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $request = $this->crud->validateRequest();
        $stockInput = $request->only([
            'comments',
            'gross_total',
            'total_discount',
            'taxable_amount',
            'tax_total',
            'net_amount',
            'sup_status_id',
            'client_id',
            'batch_number'
        ]);

        
        $sequenceCodes = $request->only(['batch_number']);

        $stockInput['created_by'] = $this->user->id;

        $statusCheck = $request->sup_status_id == SupStatus::APPROVED;

        if ($statusCheck) {
            if(empty($sequenceCodes)){
                return response()->json([
                    'status' => 'failed',
                    'message' => "Failed to approve stock. Sequence Codes are not available"
                ]);
            }
            $stockInput['entry_date_bs'] = $request->entry_date_bs;
            $stockInput['entry_date_ad'] = $request->entry_date_ad;
            $stockInput['approved_by'] = $this->user->id;
        }
        if (!$request->itemWiseDiscount) {
            $stockInput['flat_discount'] = $request->flat_discount;
        } else {
            $stockInput['flat_discount'] = null;
        }


        try {
            DB::beginTransaction();
            $stock = $this->stockEntries->create($stockInput);

            foreach ($request->mst_item_id as $key => $val) {
                $itemArr = [];
                $itemArr = array_merge($itemArr, [
                    'stock_id' => $stock->id,
                    'client_id' => $this->user->client_id,
                    'item_id' => $request->itemStockHidden[$key],
                    'available_total_qty' => $request->available_total_qty[$key],
                    'add_qty' => $request->custom_Qty[$key],
                    'total_qty' => $request->total_qty[$key],
                    'expiry_date' => $request->expiry_date[$key],
                    'discount' => isset($request->itemWiseDiscount) ? (isset($request->discount[$key]) ? $request->discount[$key] : null) :  $request->flat_discount,
                    'unit_cost_price' => $request->unit_cost_price[$key],
                    'unit_sales_price' => $request->unit_sales_price[$key],
                    'tax_vat' => $request->tax_vat[$key],
                    'item_total' => $request->item_total[$key],
                ]);

                if ($statusCheck) {
                    if(!array_key_exists('batch_number',$sequenceCodes)){
                        return response()->json([
                            'status' => 'failed',
                            'message' => "Failed to approve stock. Batch Number is not created."
                        ]);
                    }
                    $itemArr['batch_no'] = $sequenceCodes['batch_number'];
                    $this->saveQtyDetail($this->batchQtyDtl, $itemArr, 'batchQty');
                    $this->saveQtyDetail($this->itmQtyDtl, $itemArr, 'itemQty');

                }
                // dd($itemArr);
                $this->stockItems->create($itemArr);


                // foreach ($request->mst_item_id as $item => $itemsDetails) {
                //     $ItemDrtailArr = [
                //         'stock_item_id' => $stockItem->id,
                //         'barcode_details' => null,
                //         'item_id' => $item,
                //         'is_active' =>  true,
                //         'client_id' => $this->user->client_id,
                //     ];

                //     if ($statusCheck) {
                //         $barcodeArr['batch_no'] = $sequenceCodes['batch_number'];
                //     }
                //     array_push($barcodeInsertArr, $barcodeArr);
                // }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Stock added successfully',
                'route' => url($this->crud->route)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => "Failed to create stock." . $e->getMessage()
            ]);
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    public function edit($id)
    {
        $this->crud->allowAccess('update');

        $this->data['stock'] = $this->stockEntries->find($id);
        if (!isset($this->data['stock']))
            abort(404);

        $this->data['crud'] = $this->crud;
        $this->data['sequenceCodes'] = $this->sequence_type();
        $this->data['batchNumbers'] = $this->getSequenceCode(1);
        // $this->data['item_lists'] = $this->getItemList();
        $this->data['item_lists'] = $this->getStockItemList();
        $this->data['clientLists'] = $this->getClientList();

        return view('customAdmin.stockEntry.form_update', $this->data);
    }

    /**
     * @return bool|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update()
    {
        $this->crud->allowAccess('update');
        $request = $this->crud->validateRequest();
        $stockInput = $request->only([
            'comments',
            'gross_total',
            'total_discount',
            'taxable_amount',
            'tax_total',
            'net_amount',
            'sup_status_id',
            'client_id',
            'batch_number'
        ]);

        $sequenceCodes = $request->only(['batch_number']);
        $stockInput['updated_by'] = $this->user->id;
        $statusCheck = $request->sup_status_id == SupStatus::APPROVED;

        if ($statusCheck) {
            if(empty($sequenceCodes)){
                return response()->json([
                    'status' => 'failed',
                    'message' => "Failed to approve stock. Sequence Codes are not available"
                ]);
            }
            $stockInput['entry_date_bs'] = $request->entry_date_bs;
            $stockInput['entry_date_ad'] = $request->entry_date_ad;
            $stockInput['approved_by'] = $this->user->id;
        }
        if (!$request->itemWiseDiscount) {
            $stockInput['flat_discount'] = $request->flat_discount;
        } else {
            $stockInput['flat_discount'] = null;
        }
        try {
            DB::beginTransaction();
            $currentStock = $this->stockEntries->find($this->crud->getCurrentEntryId());
            $initialSupStatus = $currentStock->sup_status_id;
            $statusCheck = $request->sup_status_id == SupStatus::APPROVED;

            if ($statusCheck && $currentStock->sup_status_id != SupStatus::APPROVED) {
                if(empty($sequenceCodes)){
                    return response()->json([
                        'status' => 'failed',
                        'message' => "Failed to approve stock. Sequence Codes are not available"
                    ]);
                }
            }
            $currentStock->update($stockInput);
            $this->stockItems->destroy($currentStock->items->pluck('id'));
            foreach ($request->mst_item_id as $key => $val) {
                $itemArr = [];
                $itemArr = array_merge($itemArr, [
                    'stock_id' => $currentStock->id,
                    'client_id' => $this->user->client_id,
                    'item_id' => $request->itemStockHidden[$key],
                    'available_total_qty' => $request->available_total_qty[$key],
                    'add_qty' => $request->custom_Qty[$key],
                    'total_qty' => $request->total_qty[$key],
                    'expiry_date' => $request->expiry_date[$key],
                    'discount' => isset($request->itemWiseDiscount) ? (isset($request->discount[$key]) ? $request->discount[$key] : null) :  $request->flat_discount,
                    'unit_cost_price' => $request->unit_cost_price[$key],
                    'unit_sales_price' => $request->unit_sales_price[$key],
                    'tax_vat' => $request->tax_vat[$key],
                    'item_total' => $request->item_total[$key],
                ]);

                if ($statusCheck && $initialSupStatus != SupStatus::APPROVED) {
                    if(!array_key_exists('batch_number',$sequenceCodes)){
                        return response()->json([
                            'status' => 'failed',
                            'message' => "Failed to approve stock. Batch Number is not created."
                        ]);
                    }
                    $itemArr['batch_no'] = $sequenceCodes['batch_number'];
                }

                $this->stockItems->create($itemArr);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Stock added successfully',
                'route' => url($this->crud->route)
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to update stock. Please contact your administrator' . $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        try {
            DB::beginTransaction();
            $id = $this->crud->getCurrentEntryId() ?? $id;
            $stock = $this->stockEntries->find($id);
            $relatedStockItemIds = $stock->items->pluck('id');
            $this->stockEntries->destroy($id);
            $this->stockItems->destroy($relatedStockItemIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
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
        $data['items'] = $data['entry']->items;
        $data['crud'] = $this->crud;
        return view('customAdmin.stockEntry.show', [
            'entry' => $data['entry'],
            'items' => $data['items'],
            'crud' => $data['crud'],
        ]);
    }

    // /**
    //  * @param Item $item
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function stockItem(Item $item)
    // {
    //     $taxRate = $item->tax_vat;
    //     $is_barcode_status = $item->is_barcode;
    //     $availableQty = 0;
    //     return response()->json([
    //         'taxRate' => $taxRate,
    //         'availableQty' => $availableQty,
    //         'is_barcode'=> $is_barcode_status,
    //     ]);
    // }

    /**
     * @param $id
     * @param $from
     * @param $to
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function StockItemHistory($id, $from, $to)
    {
        $this->data['historyData'] = DB::table('stock_entries as se')
            ->join('stock_items as si', 'se.id', 'si.stock_id')
            ->where('si.item_id', $id)
            ->where('se.sup_status_id', SupStatus::APPROVED)
            ->whereBetween('se.entry_date_ad', [$from, $to])
            ->select('si.*', 'se.entry_date_ad as entry_date', 'se.created_by', 'se.approved_by')
            ->get();

        $this->data['itemName'] = MstItem::find($id)->name;
        return view('customAdmin.stockEntry.partials.history', $this->data);
    }

    public function stockItem(MstItem $item)
    {
        $taxRate = $item->tax_vat;
        $is_barcode_status = $item->is_barcode;
        $availableQty = ItemQuantityDetail::select('id', 'item_qty')
            ->where([
                'client_id' => $this->user->client_id,
                'item_id' => $item->id
            ])
            ->orderBy('id', 'desc')
            ->first()
            ->item_qty ?? 0;

        return response()->json([
            'taxRate' => $taxRate,
            'availableQty' => $availableQty,
            'is_barcode'=> $is_barcode_status,
        ]);
    }

    private function saveQtyDetail($qtyDtl, array $itemArr, $type)
    {
        $arr = [
            'client_id' => $this->user->client_id,
            'item_id' => $itemArr['item_id'],
            'created_by' => $this->user->id,
        ];


        $flag = false;
        if ($type == 'batchQty') {
            $arr['batch_no'] = $itemArr['batch_no'];
            $arr['batch_qty'] = $itemArr['add_qty'];
            $arr['batch_price'] = $itemArr['unit_sales_price'];
            $arr['batch_from'] = 'stock-mgmt';

            /** Todo: Additional stock entry after approved */
            //            $existingQtyDtl = $qtyDtl
            //                ->where('batch_no', $arr['batch_no'])
            //                ->where('batch_from', 'stock-mgmt')
            //                ->first();
            //            if($existingQtyDtl){
            //
            //            }
        } else if ($type == 'itemQty') {
            $arr['item_qty'] = $itemArr['total_qty'];
            $existingItemQty = $qtyDtl->where([
                'client_id' => $this->user->client_id,
                'item_id' => $itemArr['item_id'],
            ])->first();

            $flag = $existingItemQty ?? false;
        } else {
            throw new \Exception('Stock details could not be updated');
        }

        if ($flag) {
            $flag->item_qty = $itemArr['total_qty'];
            $flag->save();
        } else {
            $qtyDtl->create($arr);
        }
    }

    public function stockStatus()
    {

        // initializations
        $sum_total = null;
        $dataArr = [];
        $batchDataArr = [];
        $batchNoDataArr = [];
        $unique_array = [];

        $data = StockItemDetails::where('is_active', true)
                ->groupBy("item_id")
                ->select('item_id')
                ->addSelect(DB::raw('count(*) as count'));

        $data = $this->filterQueryByUser($data);
        $data = $data->get()->toArray();

        foreach ($data as $key => $value) {
            $item = MstItem::with('mstBrandEntity')->find($value['item_id']);
            $batchQty = BatchQuantityDetail::Where(['item_id' => $value['item_id'],['batch_qty', '>', 0]]);
            $batchQty = $this->filterQueryByUser($batchQty);
            $batchQty = $batchQty->get();
            $batchDataArr = [];
            $batchNoDataArr = [];
            foreach ($batchQty as $batch){
                $qty = BatchQuantityDetail::where('item_id', $value['item_id'])
                            ->where('batch_no', $batch->batch_no)
                            ->where('client_id', $this->user['client_id'])
                            ->orderBy('id', 'desc')
                            ->sum('batch_qty');
                $batchNo = MstSequence::find($batch->batch_no);
                $batchDataArr =  [
                    'batchNo' => $batchNo->sequence_code,
                    'qty' => $qty
                ];
                array_push($batchNoDataArr, $batchDataArr);
            }

            $salesQty = SaleItems::select(DB::RAW('SUM(total_qty) as total_sold'))->where('item_id', $value['item_id']);
            $salesQty = $salesQty->whereRelation('sales', 'client_id', '=', $this->user->client_id)->get();


            $arrData = [
                'item_qty' =>  $value['count'],
                'item' => $item,
            ];
            $arrData['item']['brandName'] = 'sdf';
            $arrData['item']['batchQty'] = $batchNoDataArr;
            $arrData['item']['soldQty'] = $salesQty;
            array_push($dataArr, $arrData);
            $sum_total += $value['count'];
        }

        $data = $dataArr;
        return view('stock_status', compact('data', 'sum_total'));
    }
}
