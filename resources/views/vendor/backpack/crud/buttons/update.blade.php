@if ($crud->hasAccess('update'))
	@if (!$crud->model->translationEnabled())

	@if(isset($crud->reporting_module))
		@if($entry->is_approved == false)
			<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-success edit-btn" data-toggle="tooltip" title="Edit">
				<i class="la la-edit"></i>
			</a>
		@endif
	<!-- Single edit button -->
	@else
		<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-success edit-btn"
			data-toggle="tooltip" title="Edit"><i class="la la-edit"></i></a>
	@endif

	<!-- Single edit button -->
	{{-- <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-success edit-btn"
		data-toggle="tooltip" title="Edit"><i class="la la-edit"></i></a> --}}
	@else

	<!-- Edit button group -->
	<div class="btn-group">
	  <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link pr-0"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	  <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu dropdown-menu-right">
  	    <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
	  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
		  	<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?_locale={{ $key }}">{{ $locale }}</a>
	  	@endforeach
	  </ul>
	</div>

	@endif
@endif
