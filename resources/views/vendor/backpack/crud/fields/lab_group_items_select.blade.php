@php
$current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
$entity_model = $crud->getRelationModel($field['entity'],  - 1);

if (!isset($field['options'])) {
    $options = $field['model']::all();
} else {
    $options = call_user_func($field['options'], $field['model']::query());
}

@endphp

<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <select
        name="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2Element"
        onchange="filterChecklist()"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
        >

        @if ($entity_model::isColumnNullable($field['name']))
            <option value="">-</option>
        @endif

        @if (count($options))
            @foreach ($options as $option)
                @if($current_value == $option->getKey())
                    <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
                @endif
            @endforeach
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        @if (app()->getLocale() !== 'en')
        <script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
        @endif
        <script>
            $(document).ready(function(){
                let val = $('#lab-category-id').val();
                if(val){
                    filterChecklist();
                }

            });
            function bpFieldInitSelect2Element(element) {
                // element will be a jQuery wrapped DOM node
                if (!element.hasClass("select2-hidden-accessible")) {
                    element.select2({
                        theme: "bootstrap"
                    });
                }
            }

            function filterChecklist(){
                let val = $('#lab-category-id').val();
                let url ="{{ backpack_url('lab-group/fetch-lab-category-items') }}"

                $("#checkbox_filtered").empty();

                if(val){
                    $.ajax({
                        type:"GET",
                        url:url,
                        data:{ categoryId: val, group_id: '{{ $crud->entry->id ?? '' }}'},
                        success:function(response){
                            $('#selected_items tbody').empty();
                            $('#total_price').empty();
                            let totalPrice = 0;
                            $.each(response.lab_items,function(index, value){            
                                $("#checkbox_filtered").append('<div class="icheck-primary col-md-4"><input type="checkbox" ' + value.checked + ' class="form-check-input" data-id="'+value.id+'" id ="'+value.id+'" name="laboratory_items[]" value="'+value.id+'" data-item_name="'+value.name+'" data-item_price="'+value.price+'" onclick="lab_item(this)"><label class="form-check-label" for="'+value.id+'">'+value.name+'</label></div>');

                                if(value.checked !== "") {
                                    totalPrice += parseInt(value.price)
                                    $('#selected_items tbody').append('<tr id ="'+value.id+'_item"><td class="text-left">' + value.name + ' (Rs.' + value.price + ')</td><td><input type="number" value="'+value.display_order+'" name="display_order['+value.id+']" class="form-control" /></td></tr>');
                                }
                            });
                            $('#total_price').append('<span><b>Total Price: Rs. '+totalPrice+'</b></span>');
                        }
                    })
                }
            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
