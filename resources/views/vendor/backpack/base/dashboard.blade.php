@extends(backpack_view('blank'))

@section('content')
    @php
    $count = 0;
    $client =backpack_user()->client_id;
    if(backpack_user()->client_id == 1){
        $stock_infos = DB::table('stock_items')->get();

    }else{

        $stock_infos = DB::table('stock_items')->where('client_id',$client)->get();
    }
    $date_now = date("Y/m/d");
    
    foreach($stock_infos as $stock_info){
        $item_name = App\Models\Pms\MstItem::findOrFail($stock_info->item_id)->name;
        // dd($item_name);
        $date_sort = strtotime($stock_info->expiry_date);
        $date_convert = date("Y/m/d", $date_sort);
        if($date_convert < $date_now){
            $count+=1;

            $user = backpack_user();
            $data = [
                'greeting' => 'Hi '.$user->name.',',
                'body' => ''.$item_name .'Stock is expired',
                'thanks' => 'Thank you',
                'actionText' => 'View Stock',
                'actionURL' => url('/stock-entry'),
            ];
            $user->notify(new NewMail($data));

        }
    }

    @endphp
<style>
    .dashboard-heading{
        width: 100%;
        color: #192840 !important;
        margin:20px 20px;
        padding-left: 10px;
    }
</style>

{{-- <div style="height:5vh;"></div> --}}
{{-- Organization Overview --}}
<div class="container-fluid m-0 p-0 pb-5 px-3 mb-3" style="background: white;">
        <div class="row">
            <div class="dashboard-heading">
                <h3>
                    Organization Overview
                </h3>
            </div>
            @if(isset($organizations))
            <div class="col-lg-4 col-md-6 col-xs-12">
                <a href="{{ backpack_url('sup-organization') }}">
                    <div class="card-counter primary">
                        <i class="fa fa-building"></i>
                        <span class="count-numbers" id="">{{ $organizations }}</span>
                        <span class="count-name">Total Organizations</span>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-lg-4 col-md-6 col-xs-12">
                <a href="{{backpack_url('mst-store')}}">
                    <div class="card-counter success">
                        <i class="fa fa-shopping-bag"></i>
                        <span class="count-numbers" id="">{{ $stores }}</span>
                        <span class="count-name">Total Stores</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <a href="{{backpack_url('mst-item')}}">
                    <div class="card-counter info" style="background:black; color:white;">
                        <i class="fa fa-sitemap"></i>
                        <span class="count-numbers" id="">{{ $items }}</span>
                        <span class="count-name">Total Items</span>
                    </div>
                </a>
            </div>
            @if(isset($users))
            <div class="col-lg-4 col-md-6 col-xs-12">
                <a href="{{backpack_url('user')}}">
                    <div class="card-counter purple">
                        <i class="fa fa-user"></i>
                        <span class="count-numbers" id="">{{ $users }}</span>
                        <span class="count-name">Total Users</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
</div>

    {{-- Organization Barcodes Details Row --}}
        <div class="container-fluid m-0 p-0 pb-5 px-3" style="background: white;">
            <div class="row">
                <div class="dashboard-heading">
                    <h3>
                        Organization Stock Details
                    </h3>
                </div>
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <a href="#">
                        <div class="card-counter primary">
                            <i class="fa fa-barcode"></i>
                            <span class="count-numbers" id="">{{ $total_barcodes }}</span>
                            <span class="count-name">Total Stocks</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <a href="#">
                        <div class="card-counter success">
                            <i class="fa fa-check"></i>
                            <span class="count-numbers" id="">{{ $active_barcodes }}</span>
                            <span class="count-name">Total Active Stocks</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <a href="#">
                        <div class="card-counter danger">
                            <i class="fa fa-window-close"></i>
                            <span class="count-numbers" id="">{{ $count }}</span>
                            <span class="count-name">Total Expired Stocks</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

@endsection