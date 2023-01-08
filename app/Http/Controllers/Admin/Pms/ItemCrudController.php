<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstItem;
use App\Models\Pms\MstUnit;
use App\Models\Pms\MstBrand;
use Illuminate\Http\Request;
use App\Models\Pms\MstCategory;
use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;
use App\Base\Traits\FilterStore;
use Illuminate\Support\Facades\DB;
use App\Base\Traits\UserLevelFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\MstItemRequest;
use App\Http\Requests\Pms\ItemRequest;
use app\Base\Operations\FetchOperation;
use App\Imports\ItemEntriesExcelImport;
use Illuminate\Support\Facades\Validator;
use App\Base\Operations\InlineCreateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ItemCrudController extends BaseCrudController
{
	use FilterStore, InlineCreateOperation, FetchOperation, UserLevelFilter;


    private $user;

	public function setup()
	{
		CRUD::setModel(\App\Models\Pms\MstItem::class);
		CRUD::setRoute(config('backpack.base.route_prefix') . '/item');
		CRUD::setEntityNameStrings('Item', ' items');

		$this->user = backpack_user();
		// $this->crud->addButtonFromModelFunction('line', 'storeItemSetting', 'storeItemSetting', 'end');
	}

	public function fetchMstCategory()
    {
        $results = DB::select('select * from phr_mst_categories where client_id = ?', [$this->user->client_id]);
        return $results;
    }


	public function fetchMstSupplier()
    {
        // return $this->fetch(MstSupplier::class);
        $results = DB::select('select * from mst_suppliers where client_id = ?', [$this->user->client_id]);
        return $results;
    }

	public function fetchMstDiscMode()
    {
        return $this->fetch(MstDiscMode::class);
    }


	protected function setupListOperation()
	{
		$columns = [
			$this->addRowNumberColumn(),
			$this->addCodeColumn(),

			[
				'name' => 'name',
				'type' => 'text',
				'label' => 'Product Name',
			],
            [
                // any type of relationship
                'name'         => 'mstSupplierEntity', // name of relationship method in the model
                'type'         => 'select',
                'label'        => 'Supplier', // Table column heading
                'entity'    => 'mstSupplierEntity', // the method that defines the relationship in your Model
                'attribute' => 'name_en', // foreign key attribute that is shown to user
                'model'     => MstSupplier::class, // foreign key model
            ],
			[
				'label'     => 'Brand',
				'type'      => 'select',
				'name'      => 'brand_id', // the column that contains the ID of that connected entity;
				'entity'    => 'mstBrandEntity', // the method that defines the relationship in your Model
				'attribute' => 'name_en', // foreign key attribute that is shown to user
				'model'     => MstBrand::class,

			],
			[
				'label'     => trans('Category'),
				'type'      => 'select',
				'name'      => 'category_id', // the column that contains the ID of that connected entity;
				'entity'    => 'category', // the method that defines the relationship in your Model
				'attribute' => 'title_en', // foreign key attribute that is shown to user
				'model'     => MstCategory::class,
			],
			$this->addIsActiveColumn(),
		];

		$this->crud->addColumns(array_filter($columns));
        $this->crud->addButtonFromView('top', 'excelImport', 'excelImport', 'end');
        $this->crud->addButtonFromModelFunction('top', 'itemsSampleExcel', 'itemsSampleExcel', 'end');

	}


	protected function setupCreateOperation()
	{
        CRUD::setValidation(ItemRequest::class);

		$fields = [
			$this->addCodeField(),
			[
				'type' => 'custom_html',
				'name' => 'plain_html_2',
				'value' => '<br>',
			],
            $this->addClientIdField(),
			$this->addPlainHtml(),
			[
                'name' => 'store_hidden_id',
                'type' => 'hidden',
                'attributes' => [
                    'id' => 'store_hidden_id',
                ],
            ],
			$this->addPlainHtml(),
			[
				'name' => 'name',
				'type' => 'text',
				'label' => 'Model Name',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-4',
				],
			],
			[
				'name' => 'description',
				'type' => 'text',
				'label' => trans('common.description'),
				'wrapper' => [
					'class' => 'form-group col-md-4',
				],
			],

            [
				'label'     => 'Category',
				'type'      => 'select2',
				'name'      => 'category_id', // the column that contains the ID of that connected entity;
				'entity'    => 'category', // the method that defines the relationship in your Model
				'model'     => MstCategory::class,
				'attribute' => 'title_en', // foreign key attribute that is shown to user
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
				'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
			],

			[
				'label'     => 'Brand',
				'type'      => 'select2',
				'name'      => 'brand_id', // the column that contains the ID of that connected entity;
				'entity'    => 'brand', // the method that defines the relationship in your Model
				'model'     => MstBrand::class,
				'attribute' => 'name_en', // foreign key attribute that is shown to user
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
				'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
			],
			[
                'name' => 'unit_id',
                'type' => 'select2',
                'label' => trans('Unit'),
                'entity' => 'mstUnitEntity',
                'attribute' => 'name_en',
                'model' => MstUnit::class,
                'minimum_input_length' => 0,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'options' => (function ($query){
                    return $query->where('client_id',backpack_user()->client_id)->get();
                }),
            ],

            [
				'label'     => 'Supplier',
				'type'      => 'select2',
				'name'      => 'supplier_id', // the column that contains the ID of that connected entity;
				'entity'    => 'mstSupplierEntity', // the method that defines the relationship in your Model
				'model'     => MstSupplier::class,
				'attribute' => 'name_en', // foreign key attribute that is shown to user
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
				'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
			],
			[
				'name' => 'stock_alert_minimun',
				'label' => trans('stock_alert_minimun'),
				'type' => 'number',
				'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
			],

			[
				'name' => 'is_deprecated',
				'label' => trans('Non claimable'),
				'type' => 'radio',
				'default' => 0,
				'inline' => true,
				'wrapper' => [
					'class' => 'form-group col-md-3',
				],
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
			],
			[
				'name' => 'is_price_editable',
				'label' => trans('is_price_editable'),
				'type' => 'radio',
				'default' => 0,
				'inline' => true,
				'wrapper' => [
					'class' => 'form-group col-md-3',
				],
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
			],
            [
				'name' => 'is_barcode',
				'label' => 'System',
				'type' => 'radio',
				'default' => 0,
				'inline' => true,
				'options' =>
				[
					1 => 'Barcode',
					0 => 'Custom Qty',
				],
				'wrapper' => [
					'class' => 'form-group col-md-3',
				],
				'attributes' => [
					'id' => 'is_barcode',
					'onChange' => 'INVENTORY.setIsBarcodeField()',
				],
			],
			[
                'name' => 'is_active',
                'label' => trans('common.is_active'),
                'type' => 'radio',
                'default' => 1,
                'inline' => true,
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
                'options' =>
                [
                    1 => 'Yes',
                    0 => 'No',
                ],
            ],
		];
		$this->crud->addFields(array_filter($fields));

	}

	protected function setupUpdateOperation()
	{
		$this->setupCreateOperation();
	}

	protected function setupShowOperation()
	{

        $options = MstItem::sellingTypes();

		$arr = [
			$this->addCodeColumn(),

			[
				'name' => 'name',
				'type' => 'text',
				'label' => trans('common.name'),
			],
			[
				'name' => 'description',
				'type' => 'text',
				'label' => trans('common.description'),
			],
			[
				'label'     => trans('Category'),
				'type'      => 'select',
				'name'      => 'category_id', // the column that contains the ID of that connected entity;
				'entity'    => 'category', // the method that defines the relationship in your Model
				'attribute' => 'title_en', // foreign key attribute that is shown to user
				'model'     => MstCategory::class,
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
			],

			[
				'label'     => trans('common.brand_id'),
				'type'      => 'select',
				'name'      => 'brand_id', // the column that contains the ID of that connected entity;
				'entity'    => 'mstBrandEntity', // the method that defines the relationship in your Model
				'attribute' => 'name_en', // foreign key attribute that is shown to user
				'model'     => MstBrand::class,
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
			],
			[
				'label'     => trans('common.unit_id'),
				'type'      => 'select',
				'name'      => 'unit_id', // the column that contains the ID of that connected entity;
				'entity'    => 'mstUnitEntity', // the method that defines the relationship in your Model
				'attribute' => 'name_en', // foreign key attribute that is shown to user
				'model'     => MstUnit::class,
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
			],
			[
				'label'     => trans('Supplier'),
				'type'      => 'select',
				'name'      => 'supplier_id', // the column that contains the ID of that connected entity;
				'entity'    => 'mstSupplierEntity', // the method that defines the relationship in your Model
				'attribute' => 'name_en', // foreign key attribute that is shown to user
				'model'     => MstSupplier::class,
				'options' => (function ($query) {
					return $query->where('client_id', backpack_user()->client_id)->get();
				}),
			],

			[
				'name' => 'is_deprecated',
				'label' => trans('Nonclaimable'),
				'type' => 'radio_show',
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
			],

			[
				'name' => 'is_barcode',
				'label' => 'System',
				'type' => 'radio',
				'default' => 0,
				'inline' => true,
				'options' =>
				[
					1 => 'Barcode',
					0 => 'Custom Qty',
				],
				'wrapper' => [
					'class' => 'form-group col-md-4',
				],
				'attributes' => [
					'id' => 'is_barcode',
					'onChange' => 'INVENTORY.setIsBarcodeField()',
				],
			],
			[
				'name' => 'is_active',
				'label' => 'Is Active ?',
				'type' => 'radio_show',
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
			],

			$this->addIsActiveField()
		];

		$this->crud->addColumns(array_filter($arr));
	}

        /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */


	public function itemEntriesExcelImport(Request $request)
	{

		$total_errors = [];
		$validator = Validator::make($request->all(), [
			'itemExcelFileName' => 'required',
		]);

		try {
			$itemImport = new ItemEntriesExcelImport;
			Excel::import($itemImport, request()->file('itemExcelFileName'));

			//!! Error for name doesnot exists
			if (!empty($itemImport->name_errors)) {

				array_push($total_errors, $itemImport->name_errors);
			}

			//!! Error for items with same name that already exixts
			if (!empty($itemImport->item_errors)) {
				array_push($total_errors, $itemImport->item_errors);
			}

			if (!empty($total_errors)) {
				return view('excel-errors', compact('total_errors'));
			}

            return 1;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
			//!! Databse validation Errors
            $database_validation_errors = $e->failures();
            return view('excel-errors', compact('database_validation_errors'));
        }
	}

	public function storeItemSetting($id){
		$data['id'] = $id;
		return view('vendor.backpack.crud.buttons.storeItemSetting', $data);
	}

	public function updateItemSetting(Request $request){
        $id = $this->crud->getCurrentEntryId();
		DB::beginTransaction();
		try{
			DB::table('mst_item_stores')->where('item_id', $id)->where('store_id', backpack_user()->store_id)->update([
				'min_stock_alert' => $request->min_stock_alert,
				'is_active' => $request->is_active,
			]);

			DB::commit();

			return redirect($this->crud->route);
		}catch(\Exception $e){
			DB::rollback();
			dd($e);
		}
	}


    public function loadNotification()
    {
        if(!$this->user->isSystemUser()){
            $clause = [['sup_org_id', $this->user->sup_org_id], ['created_by', $this->user->id]];
            $stocks = ItemQuantityDetail::where($clause)->get();
        }else{
            $stocks = ItemQuantityDetail::all();
        }
        $notifications = [];
        $unreadNotifications = [];
        $readnotifications = [];

        foreach ($stocks as $stock) {
            foreach ($stock->notifications as $notification) {
                array_push($notifications, $notification);
            }
            foreach ($stock->unreadNotifications as $notification) {
                array_push($unreadNotifications, $notification);
            }
            foreach ($stock->readnotifications as $notification) {
                array_push($readnotifications, $notification);
            }
        }
        $notifications = collect($notifications);
        $unreadNotifications = collect($unreadNotifications);
        $readnotifications = collect($readnotifications);
        $orderNotyCount = count($unreadNotifications);

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
            'unreadNotifications' => $notifications,
            'readNotifications' => $readnotifications,
            'stockNotificationCount' => $orderNotyCount
        ]);
    }

    public function checkNotification()
	{
        if(!$this->user->isSystemUser()){
            $clause = [['sup_org_id', $this->user->sup_org_id], ['created_by', $this->user->id]];
            $stocks = ItemQuantityDetail::where($clause)->get();
        }else{
            $stocks = ItemQuantityDetail::all();
        }
		$unreadNotifications = [];

		foreach($stocks as $stock){
            foreach($stock->unreadNotifications as $notification){
                array_push($unreadNotifications, $notification);
			}
		}
		$countUnreadNotifications = count($unreadNotifications);
		return response()->json([
				'status' => 'success',
				'message' => 'New Notification',
				'countUnreadNotifications' => $countUnreadNotifications,
			]);
	}

    public function showNotifications()
	{
		$stocks = ItemQuantityDetail::all();
		$unreadNotifications = [];
		foreach($stocks as $stock){
			foreach($stock->unreadNotifications as $notification){
				array_push($unreadNotifications, $notification);
			}
		}
        $unreadNotifications = collect($unreadNotifications)->sortByDesc('created_at');
        return view('customAdmin.partial.notification')->with('unreadNotifications', $unreadNotifications);
	}

    public function markNotification($id)
	{

        $notification = Notification::find($id);
        $stock = ItemQuantityDetail::find($notification->data['item']['id']);
        if(($stock->minimum_alert_qty) < ($stock->item_qty)) {
            $notification->read_at = Carbon::now();
            $notification->save();
        }else{
            Alert::error('Must either update minimum stock alert or item quantity')->flash();
        }
        return redirect()->back();
	}
}
