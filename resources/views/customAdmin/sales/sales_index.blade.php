@extends(backpack_view('blank'))
@php
$customer = json_encode($customer);
@endphp
@push('after_styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
        integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nepali.datepicker.v2.2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }

        .bg-default {
            background: rgba(27, 42, 78, 0.1) !important;
        }
    </style>
@endpush
@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add') . ' ' . $crud->entity_name !!}.</small>

            @if ($crud->hasAccess('list'))
                <small><a style="color:white" href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                            class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                        {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection
@section('content')
    <div class="billing_navbar">
        <div class="billing_nav">
            <div class="heading">
            </div>
            <div class="customer-search" style="margin-left: 57em;">
                <input type="hidden" name="selected_patient" id="selected_patient">
                <span id="patient_search"></span>
            </div>
            <div class="header_icons">
                    {{--  search Custmer --}}
                <a href="#" class='icon-btn' id="customer-div" data-toggle="tooltip" data-placement="top"
                    title="Click here to Search Existing Customer"><i class="fa-brands fa-searchengin"></i></a>

                    {{-- reload page to create sales  --}}
                <a href="{{ url($crud->route) . '/create' }}" class='icon-btn'><i class="fa fa-plus"
                        aria-hidden="true"></i></a>

                {{-- redirect home  --}}
                <a href="{{ url('/') }}" class='icon-btn'><i class="fa fa-home" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>

    <div class = "sales-billing-body" id="page_content">
    </div>


@endsection
@push('after_scripts')
    <script>
        let customer = <?php echo $customer ?>;
        $(document).ready(function () {
            let availableLists = [];
            if(customer){
                 customer.forEach(function(customer){
                    availableLists.push({'id':customer.id,'name':customer.name_en, 'code':customer.code,'contact_number':customer.contact_number});
                });
            }
            var customer_data = $('#patient_search').tautocomplete({
                width:"550px",
                columns:['Name','Code','Phone No.'],
                hide: [false],
                norecord:"No Records Found",
                regex:"^[a-zA-Z0-9\b\d./-]+$",
                theme:"white",
                placeholder:'Search ... Customer Name / Code / Phone no.',
                ajax:null,
                data: function () {
                        try{
                            var data=availableLists;
                        }catch(e){
                            alert(e);
                        }
                        var filterData = [];
                        $('.awhite table tbody tr:not(:first-child)').remove();
                        var searchData = eval("/" + customer_data.searchdata() + "/gi");
                        $.each(data, function(i,v)
                        {
                            if ((v.name.search(new RegExp(searchData)) != -1) || (v.code.search(new RegExp(searchData)) != -1) || (v.contact_number.search(new RegExp(searchData)) != -1) ) {
                                filterData.push(v);
                            }
                        });
                        return filterData;
                },
                highlight:"",
                onchange:function(){
                    $('#selected_patient').val(customer_data.id());
                    debugger;
                    if(customer_data.id() != null){
                        loadPatientDetail(customer_data.id());
                    }
                },

            });

            //get clicked_patient_id from session
            let clicked_patient_id = sessionStorage.getItem('clicked_patient_id');
            if(clicked_patient_id != ''){
                $('#selected_patient').val(clicked_patient_id);
                loadPatientDetail(clicked_patient_id);
                sessionStorage.removeItem('clicked_patient_id');
            }else{
                loadPatientDetail();
            }
        });
        function loadPatientDetail(id= null){
            $('#page_content').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
            INVENTORY.inventoryLoading(true,'Loading...');
            $.ajax({
                type: "GET",
                url: "get-customer-info",
                data: {customer_id: id},
                success: function(response){
                    INVENTORY.inventoryLoading(false);
                    $('#page_content').html(response);
                }
            });
        }
    </script>
@endpush
