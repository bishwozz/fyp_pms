<?php

namespace App\Imports;


use App\Models\Pms\MstCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class ItemEntriesExcelImport implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    //?? Variables to be used inside this class

    private $user;
    public $name_errors;
    public $item_errors;

    use Importable;

    public function collection(Collection $rows)
    {

        $this->user = backpack_user();

        //!! Error Checking
        $different_data_errors = $this->checkErrorData($rows);
        $item_data_error = $this->checkItemExists($rows);

        //?? If error Found
        if (!empty($different_data_errors) || !empty($item_data_error)) {
            $this->name_errors = $different_data_errors;
            $this->item_errors = $item_data_error;
        } else {
            //** If no errors begin transaction
            DB::beginTransaction();
            try {
                if (isset($rows)) {
                    foreach ($rows as $key => $row) {

                        $cat_name = isset($row['cat_name_en']) ? trim($row['cat_name_en']) : NULL;
                        $catNameId = MstCategory::where('name_en', $cat_name)->first();

                        $sub_cat_name = isset($row['sub_cat_name_en']) ? $row['sub_cat_name_en'] : NULL;
                        $subCatNameId = MstSubcategory::where('name_en', $sub_cat_name)->first();

                        $supplier = isset($row['supplier_code']) ? $row['supplier_code'] : NULL;
                        $supplierId = MstSupplier::where('code', $supplier)->orWhere('code', '0'.$supplier)->first();

                        $brand = isset($row['brand_code']) ? $row['brand_code'] : NULL;
                        $brandId = MstBrand::where('code', $brand)->orWhere('code', '0'.$brand)->first();

                        $shop = isset($row['store_code']) ? $row['store_code'] : NULL;
                        $shopId = MstStore::where('code', $shop)->orWhere('code', '0'.$shop)->first();

                        $unit = isset($row['unit_code']) ? $row['unit_code'] : NULL;
                        $unitId = MstUnit::where('code', $unit)->orWhere('code', '0'.$unit)->first();

                        $discount = isset($row['discount_mode_code']) ? $row['discount_mode_code'] : NULL;
                        $discountId = MstDiscMode::where('code', $discount)->orWhere('code', '0'.$discount)->first();

                        $stock_alert = isset($row['stock_alert_minimum']) ? $row['stock_alert_minimum'] : NULL;
                        $tax_value = isset($row['is_taxable']) ? $row['is_taxable'] : NULL;
                        $item_name = isset($row['item_name_en']) ? $row['item_name_en'] : NULL;
                        $item_price = isset($row['item_price']) ? $row['item_price'] : NULL;
                        $is_taxable = isset($row['is_taxable']) ? $row['is_taxable'] : NULL;
                        $is_damaged = isset($row['is_damaged']) ? $row['is_damaged'] : NULL;
                        $is_nonclaimable = isset($row['is_nonclaimable']) ? $row['is_nonclaimable'] : NULL;
                        $is_staffdiscount = isset($row['is_staffdiscount']) ? $row['is_staffdiscount'] : NULL;
                        $is_price_editable = isset($row['is_price_editable']) ? $row['is_price_editable'] : NULL;

                        if ($tax_value === 1) {
                            $tax_value = $row['tax_vat'];
                        } else {
                            $tax_value = 0;
                        }

                        //?? Category Creation
                        $category = MstCategory::firstOrCreate(
                            [
                                'name_en' => $cat_name,
                            ],
                            [
                                'is_active' => 1,
                                'sup_org_id' => $this->user->sup_org_id,
                                'deleted_uq_code' => 1,
                            ]
                        );

                        //?? Sub category creation
                        $subCategory = MstSubcategory::firstOrCreate(
                            [
                                'name_en' => $sub_cat_name,
                                'category_id' => $category->id,
                            ],
                            [
                                'is_active' => 1,
                                'sup_org_id' => $this->user->sup_org_id,
                                'deleted_uq_code' => 1,
                            ]
                        );
                        //?? Item creation
                        $item = MstItem::create([
                            'name' => $item_name,
                            'category_id' => $category->id,
                            'subcategory_id' => $subCategory->id,

                            'supplier_id' => $supplierId->id,
                            'brand_id' => $brandId->id,
                            'unit_id' => $unitId->id,
                            'discount_mode_id' => $discountId->id,

                            'item_price' => $item_price,
                            'is_taxable' => $is_taxable,
                            'tax_vat' => $tax_value,
                            'is_damaged' => $is_damaged,
                            'is_nonclaimable' => $is_nonclaimable,
                            'is_staffdiscount' => $is_staffdiscount,
                            'is_price_editable' => $is_price_editable,
                            'stock_alert_minimum' => $stock_alert,
                            'is_active' => 1,
                            'sup_org_id' => $this->user->sup_org_id,
                            'deleted_uq_code' => 1,
                        ]);

                        //?? Storing items and store into pivot table
                        $store_items = DB::table('mst_item_stores')->insert([
                            'store_id' => $shopId->id,
                            'item_id' => $item->id
                        ]);
                    }
                }
                DB::commit();
                Alert::success('Items inserted via Excel')->flash();
            } catch (\Exception $th) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 404);
            }
        }
    }

    public function checkErrorData($rows)
    {
        $total_error_messages = [];
        $errorList = [];

        foreach ($rows as $key => $row) {

            $individual_error_messages  = [];

            $supplier = isset($row['supplier_code']) ? $row['supplier_code'] : NULL;
            $supplierId = MstSupplier::where('code', $supplier)->orWhere('code', '0'.$supplier)->first();

            $brand = isset($row['brand_code']) ? $row['brand_code'] : NULL;
            $brandId = MstBrand::where('code', $brand)->orWhere('code', '0'.$brand)->first();

            $shop = isset($row['store_code']) ? $row['store_code'] : NULL;
            $shopId = MstStore::where('code', $shop)->orWhere('code', '0'.$shop)->first();

            $unit = isset($row['unit_code']) ? $row['unit_code'] : NULL;
            $unitId = MstUnit::where('code', $unit)->orWhere('code', '0'.$unit)->first();

            $discount = isset($row['discount_mode_code']) ? $row['discount_mode_code'] : NULL;
            $discountId = MstDiscMode::where('code', $discount)->orWhere('code', '0'.$discount)->first();

            if (($key + 1 <= count($rows))) {

                if (!$supplierId) {
                    $message['supplier_name'] = 'Supplier with the id "<b class=' . '"text-primary' . '"> ' . $rows[$key]['supplier_code'] . '" </b>, doesnot exist on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    array_push($individual_error_messages, $message['supplier_name']);
                }

                if (!$brandId) {
                    $message['brand_name'] = 'Brand with the id "<b class=' . '"text-warning' . '">' . $rows[$key]['brand_code'] . '" </b>, doesnot exist on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    array_push($individual_error_messages, $message['brand_name']);
                }

                if (!$unitId) {
                    $message['unit_name'] = 'Unit with the id "<b class=' . '"text-info' . '">' . $rows[$key]['unit_code'] . '" </b>, doesnot exist on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    array_push($individual_error_messages, $message['unit_name']);
                }

                if (!$discountId) {
                    $message['discount_mode'] = 'Discount with the id "<b class=' . '"text-success' . '">' . $rows[$key]['discount_mode_code'] . '" </b>, doesnot exist on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    array_push($individual_error_messages, $message['discount_mode']);
                }

                if (!$shopId) {
                    $message['store_name'] = 'Store with the id "<b class=' . '"text-danger' . '">' . $rows[$key]['store_code'] . '" </b>, doesnot exist on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    array_push($individual_error_messages, $message['store_name']);
                }

                array_push($total_error_messages, $individual_error_messages);
            }
        }

        $errorList = array_filter($total_error_messages);
        return $errorList;
    }

    public function checkItemExists($rows)
    {
        $total_error_messages = [];
        $errorList = [];

        foreach ($rows as $key => $row) {

            $individual_error_messages  = [];

            $item_name = trim($rows[$key]['item_name_en']);

            $item = MstItem::where('name', $item_name)->first();

            if (($key + 1 <= count($rows))) {
                if ($item) {
                    $message['item_name_en'] = 'Item with the name <b class=' . '"text-primary' . '"> "' . $rows[$key]['item_name_en'] . '" </b>, already exists in databse on <b> S. N. ' . $rows[$key]['serial'] . ' </b>';
                    array_push($individual_error_messages, $message['item_name_en']);
                }
                array_push($total_error_messages, $individual_error_messages);
            }
        }

        $errorList = array_filter($total_error_messages);

        return $errorList;
    }

    public function rules(): array
    {
        return [
            'serial' => [
                'required', 'integer'
            ],
            'cat_name_en' => [
                'required',
            ],
            'sub_cat_name_en' => [
                'required',
            ],
            'item_name_en' => [
                'required',
            ],
            'supplier_code' => [
                'required', 'integer'
            ],
            'brand_code' => [
                'required', 'integer'
            ],
            'store_code' => [
                'required', 'integer'
            ],
            'unit_code' => [
                'required', 'integer'
            ],
            'discount_mode_code' => [
                'required', 'integer'
            ],
            'item_price' => [
                'required', 'integer',
            ],
            'stock_alert_minimum' => [
                'required', 'integer'
            ],
            'is_taxable' => [
                'required', 'boolean',
            ],
            'tax_vat' => [
                'required', 'integer',
            ],
            'is_price_editable' => [
                'required', 'boolean',
            ],
            'is_staffdiscount' => [
                'required', 'boolean',
            ],
            'is_nonclaimable' => [
                'required', 'boolean',
            ],
            'is_damaged' => [
                'required', 'boolean',
            ],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'serial' => 'Serial Number',
            'cat_name_en' => 'Category NAName',
            'sub_cat_name_en' => 'Sub Category Name',
            'item_name_en' => 'Item Name',

            'supplier_code' => 'Supplier id',
            'brand_code' => 'Brand id',
            'store_code' => 'Store id',
            'unit_code' => 'Unit id',
            'discount_mode_code' => 'Discount Mode Id',

            'item_price' => 'Item Price',
            'stock_alert_minimum' => 'Stock Alert Minimum',

            'is_taxable' => 'Is Taxable',
            'tax_vat' => 'Tax/Vat',
            'is_price_editable' => 'Price Editable',
            'is_staffdiscount' => 'Staff Discount',
            'is_nonclaimable' => 'Non Claimable',
            'is_damaged' => 'Is Damaged',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'required' => 'The <b class=' . '"text-primary' . '"> :attribute </b> is required',
            'integer' => 'The :attribute must be number',
            'unique' => 'The :attribute must be unique',
            'boolean' => 'The :attribute must be boolean',
        ];
    }
}
