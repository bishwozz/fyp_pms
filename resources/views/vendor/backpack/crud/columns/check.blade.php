{{-- checkbox with loose false/null/0 checking --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    $column['icon'] = $column['value'] != false
        ? ($column['icons']['checked'] ?? 'fa-check-circle text-success')
        : ($column['icons']['unchecked'] ?? 'fa-times-circle text-danger');

    $column['text'] = $column['value'] != false
        ? ($column['labels']['checked'] ?? trans('backpack::crud.yes'))
        : ($column['labels']['unchecked'] ?? trans('backpack::crud.no'));

    $column['text'] = $column['prefix'].$column['text'].$column['suffix'];
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
    <i class="fas {{ $column['icon'] }}" style="font-size: 18px;"></i>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>

<span class="sr-only">
    @if($column['escaped'])
        {{ $column['text'] }}
    @else
        {!! $column['text'] !!}
    @endif
</span>
