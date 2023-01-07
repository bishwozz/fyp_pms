<?php

namespace App\Imports;

use Carbon\Carbon;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Base\Traits\MasterArrayData;
use App\Models\Pms\StockItemDetails;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StockEntriesExcelImport implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    private $user;
    public $barcode_errors;
    public $differeent_data_errors;
    public $item_exists_error;
    public $array_count;

    use MasterArrayData;

    use Importable;

    public function collection(Collection $rows)
    {
        $barcodes_from_excel = [];

        foreach ($rows as $row) {
            array_push($barcodes_from_excel, $row['barcode_details']);
        }

        //!! errors check
        $barcode_errors = $this->checkifBarcodeExistsInDb($barcodes_from_excel);

        $different_data_errors = $this->checkSameData($rows);

        $item_exists_error = $this->checkItemExists($rows);

        if (!empty($barcode_errors) || !empty($different_data_errors) || !empty($item_exists_error)) {

            //!! if errors found do this

            $this->barcode_errors = $barcode_errors;

            $this->differeent_data_errors = $different_data_errors;

            $this->item_exists_error = $item_exists_error;
        } else {
            //!! if errors not found do this
            $is_flat_discount = request()->flatDiscount;
            $flat_discount_rate = request()->flatDiscountAmount;

            $this->user = backpack_user();

            DB::beginTransaction();
            try {
                if (isset($rows)) {

                    $item_count = $this->getItemCount($rows);

                    $stock = StockEntries::firstorCreate([
                        'store_id' => $this->user->store_id,
                        'sup_org_id' => $this->user->sup_org_id,
                        'entry_date_ad' => dateToday(),
                        'entry_date_bs' => convert_bs_from_ad(dateToday()),
                        'sup_status_id' => SupStatus::CREATED,
                        'gross_total' => $item_count['final_gross_total'],
                        'total_discount' => $item_count['final_discount_total'],
                        'taxable_amount' => $item_count['final_taxable_amt_total'],
                        'tax_total' => $item_count['final_tax_total'],
                        'net_amount' => $item_count['final_net_total'],
                        'flat_discount' => $flat_discount_rate ?? null
                    ]);


                    foreach ($item_count['item_details'] as $item_key => $item_value) {

                        // dd($item_count['item_details'] , $item_key , $item_value);

                        // if ($item_key <= count($item_count['item_details'])) {

                            //** setting value of discount as per flat discount or individual discount

                            if ($is_flat_discount === '1') {
                                $discount = $flat_discount_rate;
                            } else {
                                $discount = $item_value['discount'];
                            }

                            //** Finding the item with its code from excel sheet

                            $item = MstItem::where('code', $item_value['code'])->first();
                            $item_id = $item->id;

                            // dd($item);

                            //** Item quantity detail if item already exists

                            $item_qty_detail = ItemQuantityDetail::select('item_qty')->where('item_id', $item_id);

                            if($this->user->isSystemUser()){
                                $item_qty_detail = $item_qty_detail->first();
                            }elseif($this->user->isOrganizationUser()){
                                $item_qty_detail = $item_qty_detail->where('sup_org_id', $this->user->sup_org_id)->first();
                            }elseif($this->user->isStoreUser()){
                                $item_qty_detail = $item_qty_detail->where(['sup_org_id' => $this->user->sup_org_id,'store_id' => $this->user->store_id])->first();
                            }

                            // dd($item_qty_detail);

                            //** setting value of available quantity
                            if ($item_qty_detail) {
                                $total_item_qty_detail = $item_qty_detail->item_qty;
                            } else {
                                $total_item_qty_detail = 0;
                            }

                            //** Stock item creation
                            $stock_items = StockItems::create([
                                'stock_id' => $stock->id,
                                'sup_org_id' => $this->user->sup_org_id,
                                'mst_item_id' => $item_id,
                                'item_total' => $item_value['sub_total_discounted'],
                                'available_total_qty' => $total_item_qty_detail,
                                'add_qty' => $item_value['count'],
                                'total_qty' => $total_item_qty_detail + $item_value['count'],
                                // 'expiry_date' => Carbon::now()->addDays(15)->toDateString(),
                                'discount' => $discount,
                                'unit_cost_price' => $item_value['unit_cost_price'],
                                'unit_sales_price' => $item_value['unit_sales_price'],
                                'tax_vat' => $item_value['tax_vat'],
                            ]);

                            // dd($stock_items);


                            //** stock item detail for each stock item
                            foreach ($item_value['bar_code'] as $bar_code) {
                                // dd($item_value['bar_code'] , $bar_code);
                                $stock_item_details = StockItemDetails::create([
                                    'stock_item_id' => $stock_items->id,
                                    'item_id' => $item_id,
                                    'store_id' => $this->user->store_id,
                                    'sup_org_id' => $this->user->sup_org_id,
                                    'barcode_details' => $bar_code,
                                    'is_active' => false
                                ]);

                                // dd($stock_item_details);
                            }
                        // }
                    }
                }
                DB::commit();
                Alert::success('Stocks inserted via Excel')->flash();
            } catch (\Exception $th) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 404);
            }
        }
    }

    public function getItemCount($rows)
    {
        $item_details = [];

        foreach ($rows as $key => $row) {

            $item = MstItem::where('code', $row['mst_item_code'])->first();
            $item_code = $item->code;

            if (array_key_exists($row['mst_item_code'], $item_details)) {
                if ($row['mst_item_code'] == $item->code) {
                    $item_details[$item_code]['count']++;
                    $item_details[$item_code]['bar_code'][] = $row['barcode_details'];
                }
            } else {
                $item_details[$item_code]['count'] = 1;
                $item_details[$item_code]['code'] = $row['mst_item_code'];
                $item_details[$item_code]['discount'] = $row['discount'];
                $item_details[$item_code]['unit_cost_price'] = $row['unit_cost_price'];
                $item_details[$item_code]['unit_sales_price'] = $row['unit_sales_price'];
                $item_details[$item_code]['tax_vat'] = $row['tax_vat'];
                $item_details[$item_code]['bar_code'][] = $row['barcode_details'];
            }
        }


        //Final Calculation

        $final_item_details = [];
        foreach ($item_details as $key => $item) {
            $final_item_details[$key]['sub_total'] = $item['count'] * $item['unit_cost_price'];
            $final_item_details[$key]['discount'] = ($item['discount'] / 100) * $final_item_details[$key]['sub_total'];
            $final_item_details[$key]['after_discount'] = $final_item_details[$key]['sub_total'] - $final_item_details[$key]['discount'];
            $final_item_details[$key]['taxable_amt'] =  $final_item_details[$key]['sub_total'] - $final_item_details[$key]['discount'];
            $final_item_details[$key]['tax_total'] =  (($item['tax_vat'] / 100) * $final_item_details[$key]['after_discount']);
            $final_item_details[$key]['net_total'] =  $final_item_details[$key]['taxable_amt'] + $final_item_details[$key]['tax_total'];

            // For Individual Item Details
            $item_details[$key]['sub_total'] = $final_item_details[$key]['sub_total'];
            $item_details[$key]['sub_total_discounted'] = $final_item_details[$key]['sub_total'] - $final_item_details[$key]['discount'];
        }

        $final_gross_total = 0;
        $final_discount_total = 0;
        $final_taxable_amt_total = 0;
        $final_tax_total = 0;
        $final_net_total = 0;

        foreach ($final_item_details as $item) {
            $final_gross_total = $item['sub_total'] + $final_gross_total;
            $final_discount_total = $item['discount'] + $final_discount_total;
            $final_taxable_amt_total = $item['taxable_amt'] + $final_taxable_amt_total;
            $final_tax_total = $item['tax_total'] + $final_tax_total;
            $final_net_total = $item['net_total'] + $final_net_total;
        }

        // dd($final_tax_total);
        return [
            'item_details' => $item_details,
            'final_item_details' => $final_item_details,
            'final_gross_total' => $final_gross_total,
            'final_discount_total' => $final_discount_total,
            'final_taxable_amt_total' => $final_taxable_amt_total,
            'final_tax_total' => $final_tax_total,
            'final_net_total' => $final_net_total
        ];
    }

    public function checkifBarcodeExistsInDb($barcodes_from_excel)
    {
        $existing_barcodes = [];
        $json_only_barcode = [];
        $filename = 'barcode-' . backpack_user()->sup_org_id;
        $path = storage_path() . "/barcodes/${filename}.json";
        $json = json_decode(file_get_contents($path), true);
        if (isset($json)) {
            $json_only_barcode = array_keys($json);
            $existing_barcodes = array_intersect($json_only_barcode, $barcodes_from_excel);
        }
        return $existing_barcodes;
    }

    public function checkItemExists($rows)
    {
        $total_error_messages = [];
        $errorList = [];
        $this->user = backpack_user();
        foreach ($rows as $key => $row) {

            $individual_error_messages  = [];

            $item_code = $rows[$key]['mst_item_code'];
            $item = MstItem::where('code', $item_code)->Where('sup_org_id', $this->user->sup_org_id)->first();

            if (($key + 1 <= count($rows))) {
                if (!$item) {
                    // $message['mst_item_code'] = 'Item with the Code <b class=' . '"text-primary' . '"> "' . $rows[$key]['mst_item_code'] . '" </b>, doesnot exist on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    $message['mst_item_code'] = 'Product with the Code <b class=' . '"text-primary' . '"> "' . $rows[$key]['mst_item_code'] . '" </b>, on <b> S. N. ' . $rows[$key]['serial'] . ' </b> does not exist in your Primary Master';
                    array_push($individual_error_messages, $message['mst_item_code']);
                }
                array_push($total_error_messages, $individual_error_messages);
            }
        }

        $errorList = array_filter($total_error_messages);

        return $errorList;
    }

    public function checkSameData($rows)
    {
        $total_error_messages = [];
        $errorList = [];

        foreach ($rows as $key => $row) {

            $individual_error_messages  = [];

            if (($key + 1 < count($rows))) {

                if ($rows[$key + 1]['mst_item_code'] === $rows[$key]['mst_item_code']) {

                    if ($rows[$key + 1]['discount'] !== $rows[$key]['discount']) {

                        $error_messages['discount'] = '<b class=' . '"text-primary' . '"> Discount </b> cannot be different for <b> same item </b> on <b> S. N. ' . $rows[$key]['serial'] . ' </b> and  on  <b> S. N. ' . $rows[$key + 1]['serial'] . '</b>';

                        array_push($individual_error_messages, $error_messages['discount']);
                    }

                    if ($rows[$key + 1]['unit_cost_price'] !== $rows[$key]['unit_cost_price']) {

                        $error_messages['cp'] = '<b class=' . '"text-warning' . '"> Cost Price </b> cannot be different for <b> same item </b> on <b> S. N. ' . $rows[$key]['serial'] . ' </b> and  on  <b> S. N. ' . $rows[$key + 1]['serial'] . '</b>';

                        array_push($individual_error_messages, $error_messages['cp']);
                    }

                    if ($rows[$key + 1]['unit_sales_price'] !== $rows[$key]['unit_sales_price']) {

                        $error_messages['sp'] = '<b class=' . '"text-info' . '"> Sales Price </b> cannot be different <b> same item </b> on <b> S. N. ' . $rows[$key]['serial'] . ' </b> and  on  <b> S. N. ' . $rows[$key + 1]['serial'] . '</b>';
                        array_push($individual_error_messages, $error_messages['sp']);
                    }

                    if ($rows[$key + 1]['tax_vat'] !== $rows[$key]['tax_vat']) {

                        $error_messages['tax_vat'] = '<b class=' . '"text-success' . '"> Tax/VAT </b> cannot be different <b> same item </b> on <b> S. N. ' . $rows[$key]['serial'] . ' </b> and  on  <b> S. N. ' . $rows[$key + 1]['serial'] . '</b>';

                        array_push($individual_error_messages, $error_messages['tax_vat']);
                    }
                }
            }
            array_push($total_error_messages, $individual_error_messages);
        }
        array_push($errorList, array_filter($total_error_messages));

        $errorList = array_filter($total_error_messages);

        return $errorList;
    }

    public function rules(): array
    {
        return [
            'serial' => [
                'required',
                'integer'
            ],
            'mst_item_code' => [
                'required',
                'integer',
            ],
            'discount' => [
                'integer',
            ],
            'unit_cost_price' => [
                'required',
            ],
            'unit_sales_price' => [
                'required',
            ],
            'tax_vat' => [
                'integer',
            ],
            'barcode_details' => [
                'required',
                'unique:stock_items_details,barcode_details',
            ],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'serial' => 'Serial Number',
            'mst_item_code' => 'Item Code',
            'discount' => 'Discount Percentage',
            'unit_cost_price' => 'Unit Cost Price',
            'unit_sales_price' => 'Unit Sales Price',
            'tax_vat' => 'Tax/Vat',
            'barcode_details' => 'Barcode Detail',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'required' => 'The <b> :attribute </b> is required',
            'integer' => 'The :attribute must be number',
            'unique' => 'The :attribute must be unique',
        ];
    }
}
