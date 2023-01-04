<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstSequence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\MstSequenceRequest;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstSequenceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstSequenceCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(MstSequence::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-sequence');
        CRUD::setEntityNameStrings('', 'mst sequences');
        // $this->crud->denyAccess([ 'delete']);
        $this->user = backpack_user();
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
            $this->addNameEnColumn(),
            [
                'type' => 'select_from_array',
                'name' => 'sequence_type',
                'label' => 'Sequence Type',
                'options' => $this->sequence_type()
            ],
            [
                'label' => 'Sequence Code',
                'type' => 'text',
                'name' => 'sequence_code',
            ],
            [
                'type' => 'text',
                'name' => 'sequence_code',
                'label' => 'Sequence Number',
            ],
        ];
        $this->crud->addColumns($cols);
        if(!$this->user->isSystemUser()){
            $this->crud->addButtonFromView('top', 'fetchMasterData', 'fetchMasterData', 'end');
        }
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
        // CRUD::setValidation(MstSequenceRequest::class);
        $fields = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addNameEnField(),
            $this->addNameLcField(),
            [
                'label' => 'Sequence Type',
                'type' => 'select2_from_array',
                'name' => 'sequence_type',
                'options' => $this->sequence_type(),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'label' => 'Sequence Code',
                'type' => 'text',
                'name' => 'sequence_code',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],

        ];
        $this->crud->addFields($fields);
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

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inlineStore()
    {
        $this->crud->hasAccessOrFail('create');
        $request = request()->only(['name_en', 'sequence_code', 'sequence_type']);
        $request['sup_org_id'] = $this->user->sup_org_id;
        $request['store_id'] = $this->user->store_id;
        if (backpack_user()->isSystemUser()) {
            $request["is_super_data"] = true;
        }
        $sequence = MstSequence::create($request);
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Sequence created successfully',
                'sequenceId' => $sequence->id,
                'sequenceCode' => $sequence->sequence_code,
            ]
        );
    }

    public function sequenceCodeCheck(Request $request)
    {
        if(!$request->sequenceCode){
            return response()->json([
                'status' => 'empty',
                'message' => 'Sequence code cannot be empty'
            ]);
        }

        $sequenceCode = MstSequence::where([['sequence_type' , $request->sequenceCodeType], ['sequence_code' , 'ILIKE', $request->sequenceCode]]);
        $sequenceCode = $this->filterQueryByUser($sequenceCode);
        $sequenceCode = $sequenceCode->first();

        if($sequenceCode){
            return response()->json([
                'status' => 'error',
                'message' => 'Sequence code already Exists'
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Sequence code can be created'
            ]);
        }
    }
}
