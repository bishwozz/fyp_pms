{{-- Text Backpack CRUD filter --}}

<li filter-name="{{ Str::slug($filter->name) }}"
	filter-type="{{ $filter->type }}"
	class="nav-item dropdown1 {{ Request::get($filter->name) ? 'active' : '' }}">
	<a href="#" class="nav-link dropdown-toggle1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $filter->label }} <span class="caret1"></span></a>
	<div class="dropdown-menu-1 p-0">
		<div class="form-group backpack-filter mb-0">
			<div class="input-group">
		        <input class="form-control pull-right"
		        		id="text-filter-{{ Str::slug($filter->name) }}"
		        		type="text"
						@if ($filter->currentValue)
							value="{{ $filter->currentValue }}"
						@endif
		        		>
		        <div class="input-group-append text-filter-{{ Str::slug($filter->name) }}-clear-button">
		          <a class="input-group-text" href=""><i class="la la-times"></i></a>
		        </div>
		    </div>
		</div>
	</div>
</li>

{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}

@push('crud_list_styles')
<style>
    [id^=text-filter-]{
        width:120px !important;
        }
</style>
@endpush
{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_list_scripts')
	<!-- include select2 js-->
  <script>
		jQuery(document).ready(function($) {
			$('#text-filter-{{ Str::slug($filter->name) }}').on('change', function(e) {

				var parameter = '{{ $filter->name }}';
				var value = $(this).val();

		    	// behaviour for ajax table
				var ajax_table = $('#crudTable').DataTable();
				var current_url = ajax_table.ajax.url();
				var new_url = addOrUpdateUriParameter(current_url, parameter, value);

				// replace the datatables ajax url with new_url and reload it
				new_url = normalizeAmpersand(new_url.toString());
				ajax_table.ajax.url(new_url).load();

				// add filter to URL
				// crud.updateUrl(new_url);

				// mark this filter as active in the navbar-filters
				if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
					$('li[filter-name={{ $filter->name }}]').removeClass('active').addClass('active');
				} else {
					$('li[filter-name={{ $filter->name }}]').trigger('filter:clear');
				}
			});

			$('li[filter-name={{ Str::slug($filter->name) }}]').on('filter:clear', function(e) {
				$('li[filter-name={{ $filter->name }}]').removeClass('active');
				$('#text-filter-{{ Str::slug($filter->name) }}').val('');
			});

			// datepicker clear button
			$(".text-filter-{{ Str::slug($filter->name) }}-clear-button").click(function(e) {
				e.preventDefault();

				$('li[filter-name={{ Str::slug($filter->name) }}]').trigger('filter:clear');
				$('#text-filter-{{ Str::slug($filter->name) }}').val('');
				$('#text-filter-{{ Str::slug($filter->name) }}').trigger('change');
			})
		});
  </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}