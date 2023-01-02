{{-- relationships with pivot table (n-n) --}}
@php
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 40;
    $column['attribute'] = $column['attribute'] ?? (new $column['model'])->identifiableAttribute();

    
    $results = data_get($entry, $column['name']);
    $results_array = [];

    if(!$results->isEmpty()) {
        $related_key = $results->first()->getKeyName();
        $results_array = $results->pluck($column['attribute'], $related_key)->toArray();
    }

    foreach ($results_array as $key => $text) { 
        $results_array[$key] = Str::limit($text, $column['limit'], '[...]');
    }

 $permission_collection= [];
  foreach($results_array as $key=>$name){
    $entity_arr = explode(' ',$name);
    $permission_collection[end($entity_arr)][] = $entity_arr[0];
  }
@endphp

<span>
    @if(!empty($permission_collection))
        @foreach($permission_collection as $key => $text)
            <span>
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
                    @if($column['escaped'])
                    <span class="text-primary mr-3" style="line-height: 1.5rem;">{{$key}}</span> => &nbsp;&nbsp; {{implode("  ,  ",$text) }}
                    @else
                        {!! $text !!}
                    @endif
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
            </span>
        <br/>
        @endforeach
    @else
        -
    @endif
</span>