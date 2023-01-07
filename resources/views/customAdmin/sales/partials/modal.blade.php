<!-- Customer Type Modal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="customerType" tabindex="-1" role="dialog"
    aria-labelledby="customerTypeLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="customerTypeLabel">Select Customer Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 my-2">
                        <button type="button" class="btn btn-flat btn-block btn-success" id="individualCustomer"
                            data-toggle="tooltip" data-placement="top"
                            title="Click here to load existing Individual Customer">Individual</button>
                    </div>
                    <div class="col-lg-6 col-md-12 my-2">
                        <button type="button" class="btn btn-flat btn-block btn-info" id="coorporateCustomer"
                            data-toggle="tooltip" data-placement="top"
                            title="Click here to load existing Coorporate Customer">Coorporate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Individual Customer Modal -->
<div class="modal fade" id="customerModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100">Choose Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <select class="js-example-basic-single" name="state" style="width: 100%;" id="buyerSelect" placeholder="Select Individual Customer from here">
                        <option disabled selected>Select Individual Customer from here</option>
                        {{-- @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                @if ($customer->is_coorporate == true)
                                    {{ $customer->company_name }}
                                @else
                                    {{ $customer->name_en }}
                                @endif
                            </option>
                        @endforeach --}}
                    </select>
                </div>
                <button type="button" class="btn bg-default btn-block ml-auto" id="indCustomerSave">Select
                    Customer</button>
                {{-- <div class="my-3 text-center">
                    <p class="h1 font-weight-bold">
                        OR
                    </p>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Customer's Name"
                        aria-label="Customer's Name" id="customerNameInput" name="customerNameInput">
                    <div class="input-group-append">
                        <span class="input-group-text bg-success text-white" id="createIndividualCustomer">Create New</span>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<!--Coorporate Customer Modal -->
<div class="modal fade" id="coorporateCustomerModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100">Choose Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <select class="js-example-basic-single" name="state" style="width: 100%;" id="companyName">
                        <option disabled selected>-</option>
                        {{-- @foreach ($coorporateCustomers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->company_name }}
                            </option>
                        @endforeach --}}
                    </select>
                </div>

                <div class="input-group mb-3">
                    <select class="js-example-basic-single" name="customerName" style="width: 100%;" id="customerName">
                    </select>
                </div>
                {{-- <div class="input-group mb-3">
                    <button type="button" class="btn btn-info btn-block ml-auto" id="newCompanyCustomer">Create New Customer for this company</button>
                </div> --}}
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn bg-default btn-block ml-auto" id="companySave">Select
                    Customer</button>
                {{-- <button type="button" class="btn btn-secondary btn-block mr-auto"
                    data-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"></script>
