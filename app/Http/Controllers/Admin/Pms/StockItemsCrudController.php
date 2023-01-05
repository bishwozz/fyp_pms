<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Base\BaseCrudController;
use App\Http\Requests\StockItemsRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StockItemsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockItemsCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\StockItems::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock-items');
        CRUD::setEntityNameStrings('stock items', 'stock items');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('available_total_qty');
        CRUD::column('add_qty');
        CRUD::column('total_qty');
        CRUD::column('batch_no');
        CRUD::column('expiry_date');
        CRUD::column('free_item');
        CRUD::column('discount');
        CRUD::column('unit_cost_price');
        CRUD::column('unit_sales_price');
        CRUD::column('tax_vat');
        CRUD::column('item_total');
        CRUD::column('client_id');
        CRUD::column('stock_id');
        CRUD::column('mst_item_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StockItemsRequest::class);

        CRUD::field('available_total_qty');
        CRUD::field('add_qty');
        CRUD::field('total_qty');
        CRUD::field('batch_no');
        CRUD::field('expiry_date');
        CRUD::field('free_item');
        CRUD::field('discount');
        CRUD::field('unit_cost_price');
        CRUD::field('unit_sales_price');
        CRUD::field('tax_vat');
        CRUD::field('item_total');
        CRUD::field('client_id');
        CRUD::field('stock_id');
        CRUD::field('mst_item_id');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
