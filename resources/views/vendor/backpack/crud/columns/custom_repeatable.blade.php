@php
	$value = data_get($entry, $column['name']);

    // make sure columns are defined
    if (!isset($column['columns'])) {
        $column['columns'] = ['value' => "Value"];
    }

	$columns = $column['columns'];

	// if this attribute isn't using attribute casting, decode it
	if (is_string($value)) {
	    $value = json_decode($value);
    }
@endphp

<span>
    @if ($value && count($columns))

    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')

    <table class="table table-sm table-bordered table-condensed table-striped m-b-0">
		<thead>
			<tr>
				@foreach($columns as $tableColumnKey => $tableColumnLabel)
					<th class="pb-0">{{ $tableColumnLabel }}</th>
				@endforeach
			</tr>
		</thead>

		<tbody>
			@foreach ($value as $tableRow)
			<tr>
				@foreach($columns as $tableColumnKey => $tableColumnLabel)
					<td>
						@if( is_array($tableRow) && isset($tableRow[$tableColumnKey]) )
							{{ $tableRow[$tableColumnKey] }}
                        @elseif( is_object($tableRow) && property_exists($tableRow, $tableColumnKey) )
							@php
								switch($tableColumnKey){
									case 'step_id':
										if($tableRow->$tableColumnKey){
											$column_value = App\Models\MstStep::findOrFail($tableRow->$tableColumnKey)->name_en;
										}else{
											$column_value = " - ";
										}
									break;
									default:
										if($tableRow->$tableColumnKey){
											$column_value = wordwrap($tableRow->$tableColumnKey,60, "<br/>", false);
									}else{
											$column_value = " - ";
										}
									break;
								}
							@endphp
			
							{!! nl2br($column_value) !!}

                        @endif

					</td>
				@endforeach
			</tr>
			@endforeach
		</tbody>
    </table>

    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')

	@endif
</span>