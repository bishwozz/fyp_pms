@php
    $fields = ['id', 'client_id', 'is_active', 'created_by', 'updated_by', 'deleted_by', 'is_deleted', 'deleted_at', 'deleted_uq_code', 'created_at', 'updated_at'];

    $thead = collect($crud->model->first())
        ->keys()
        ->toArray();
    $tbody = $crud->model
        ->where('client_id', true)
        ->where('is_active', true)
        ->get();
    $tbody->makeHidden($fields);

    $strippedThead = array_diff($thead, $fields);
    $modelPath = get_class($crud->getModel());
    $replacedModelPath = Str::replace('\\', '_', $modelPath);
@endphp

<!-- Button trigger modal -->
<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#fetchMasterDataModel"
    title="Tooltip">
    <i class="fa fa-files-o" aria-hidden="true"></i>
    &nbsp;
    Copy Master Data
</button>


<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="fetchMasterDataModel" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="fetchMasterDataModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title text-center" id="fetchMasterDataModelLabel">
                    {{ Str::title($crud->entity_name_plural) }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if (!empty($thead) || $tbody->isNotEmpty())
                <form action="{{ route('fetch.superData', $replacedModelPath) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="fetchItemTable">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" id="header-checkbox">
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                        {{-- {{ dd($strippedThead) }} --}}
                                        @foreach ($strippedThead as $item)
                                            @php
                                                if (Str::contains($item, '_')) {
                                                    $item = Str::ucfirst(Str::replace('_', ' ', $item));
                                                } else {
                                                    $item = Str::title($item);
                                                }
                                            @endphp
                                            <th scope="col">{{ $item }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tbody as $item)
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox"
                                                                id="body-checkbox-{{ $item->id }}"
                                                                class="fetch_master_body_checkbox"
                                                                name="body_checkbox-{{ $item->id }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            @php
                                                $item = array_diff($item->toArray(), $fields);
                                            @endphp
                                            @foreach ($item as $key => $value)
                                                <td>{{ isset($value) ? $value : 'N/A' }}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($strippedThead) + 1 }}">Oops No super data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Fetch Data</button>
                    </div>
                </form>
            @else
                <div class="modal-body">
                    <div class="alert alert-error" role="alert">
                        <h4 class="alert-heading font-weight-bolder">Oops! No Master Data Available</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>



<script>
    $('#fetchMasterDataModel').appendTo('body');
</script>

<script>
    $('#header-checkbox').click(function() {
        if ($(this).is(':checked')) {
            $('.fetch_master_body_checkbox').attr('checked', true);
        } else {
            $('.fetch_master_body_checkbox').attr('checked', false);
        }
    });
    $('#fetchItemTable').DataTable({
        responsive: true
    });
</script>
