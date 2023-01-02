<!-- textarea -->
@php
$value=$field['value'];
$vs = '';
if(is_array($value) && count($value)>0){
  foreach($value as $key=>$v){
      if(is_array($v)){
          $vs .= $key .' : '. json_encode($v) ."\n";
      }else{
          $vs .=$key .' : '.$v ."\n";
      }
  }
}
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <textarea
    	name="{{ $field['name'] }}" rows="5"
        @include('crud::fields.inc.attributes')

    	>{!! $vs !!}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')
