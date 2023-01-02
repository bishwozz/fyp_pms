<!-- html5 date input -->

<?php
// if the column has been cast to Carbon or Date (using attribute casting)
// get the value as a date string
if (isset($field['value']) && ($field['value'] instanceof \Carbon\CarbonInterface)) {
    $field['value'] = $field['value']->toDateString();
}
?>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <div class="input-group">
        <input
            type="text"
            name="{{ $field['name'] }}"
            data-init-function="fieldDateChange"
            placeholder="yyyy-mm-dd"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            autocomplete="off"
            @include('crud::inc.field_attributes')
            ><div class="input-group-append"><span class="input-group-text"><i class="la la-calendar"></i></span></div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push('crud_fields_scripts')
    <!-- include select2 js-->
    <script>
        function fieldDateChange(element) {
            var selector_id = element.attr('id');
            var related_id = element.attr('relatedId');
           if(selector_id !== null ){
                $('#'+selector_id).nepaliDatePicker({
                    npdMonth: true, 
                    npdYear: true,
                    onChange: function () {
                        $('#'+related_id).val(BS2AD($('#'+selector_id).val()));
                    }
                });
                $('#'+selector_id).change(function(){
                    DateChange('#'+selector_id, '#'+related_id);
                    $('#'+related_id).val(BS2AD($('#'+selector_id).val()));
                });
                $('#'+related_id).change(function(){
                    $('#'+selector_id).val(AD2BS($('#'+related_id).val()));
                });

                var regexname='^[0-9]*$';
    
                $('#'+selector_id).keyup(function(e){
                    let selected_value = $('#'+selector_id).val();
                    if(e.key === '-' || e.key === '/'){
                        if(selected_value.length>10){
                            $('#'+selector_id).val(selected_value.substr(0,10));
                        }
                    }else{
                        if (e.key.match(regexname)){
                            if(selected_value.length>10){
                                $('#'+selector_id).val(selected_value.substr(0,10));
                            }
                        }else{
                            $('#'+selector_id).val(selected_value.substr(0,selected_value.length - 1));
                        }
                    }
                });
            }
        }
    </script>
@endpush
