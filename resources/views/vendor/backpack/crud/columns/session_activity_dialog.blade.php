@if(isset($crud->enableDialog) && $crud->enableDialog)
	<a data-fancybox1 data-type="ajax" class="font-weight-bold" data-src="{{ url($crud->route.'/'.$entry->getKey().'/edit')}}" href="javascript:void(0)" onclick="{{$crud->controller}}_item_edit_click()">{{ $entry->session_id}} </a>
@endif