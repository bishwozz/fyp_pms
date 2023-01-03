<style>
   hr.hr-line {
       border: 1px solid azure;
       box-shadow: 4px 4px 4px black;
       opacity: .20 !important;
       color: azure;
       margin: 0.25rem !important;
   }

   .nav .fa-plus {
       position: absolute;
       right: 12px;
       top: 12px;
       color: white;
       margin-left: 15px;
   }

   .nav .fa-plus:hover {
       text-decoration: none;
       font-weight: bold;
       color: rgb(64, 255, 0) !important;
       font-size: 17px;
       top: 10px;
   }
</style>
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
       {{ trans('backpack::base.dashboard') }}</a>
</li>
<hr class="hr-line">

{{-- ///-----pms --}}
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>PMS</a>
    <ul class="nav-dropdown-items" style="overflow-x:hidden">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('item') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('Item') }}</a>
            <a href="{{ backpack_url('item/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstgenericname') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('MstGenericName') }}</a>
            <a href="{{ backpack_url('mstgenericname/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstcategory') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('MstCategory') }}</a>
            <a href="{{ backpack_url('mstcategory/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstsupplier') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('MstSupplier') }}</a>
            <a href="{{ backpack_url('mstsupplier/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('inventory') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('Inventory') }}</a>
            <a href="{{ backpack_url('inventory/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstpharmaceutical') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('MstPharmaceutical') }}</a>
            <a href="{{ backpack_url('mstpharmaceutical/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstunit') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('MstUnit') }}</a>
            <a href="{{ backpack_url('mstunit/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstbrand') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('MstBrand') }}</a>
            <a href="{{ backpack_url('mstbrand/create') }}"><i class="fa fa-plus"></i></a>
        </li>
    </ul>
 </li>
 <hr class="hr-line">
{{-- ///-----pms --}}

{{-- ///-----purchase --}}

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>Purchase</a>
    <ul class="nav-dropdown-items" style="overflow-x:hidden">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('item') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('Item') }}</a>
            <a href="{{ backpack_url('item/create') }}"><i class="fa fa-plus"></i></a>
        </li>
    </ul>
 </li>
 <hr class="hr-line">

{{-- bill --}}
@hasanyrole('superadmin|clientadmin|admin|reception|lab_admin|finance')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sales') }}'><i
           class='nav-icon la la-columns'></i> Patient billings</a>
   <a href="{{ backpack_url('sales/create') }}"><i class="fa fa-plus"></i></a>
</li>
<hr class="hr-line">
@endhasanyrole


{{-- report --}}
@hasanyrole('superadmin|clientadmin|admin|lab_admin|finance')

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('reports') }}'><i class='nav-icon la la-file'></i>
       Reports</a>
</li>
<hr class="hr-line">
@endhasanyrole


{{-- Employees --}}
@hasanyrole('superadmin|clientadmin|admin|lab_admin')

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('hrmstemployees') }}'><i
           class='nav-icon la la-user'></i>Employees</a>
   <a href="{{ backpack_url('hrmstemployees/create') }}"><i class="fa fa-plus"></i></a>
</li>
<hr class="hr-line">
@endhasanyrole


@hasrole('superadmin')
   <li class="nav-item nav-dropdown">
       <a class="nav-link nav-dropdown-toggle" href="#"><i
               class="nav-icon la la-tasks"></i>{{ trans('menu.master') }}</a>
       <ul class="nav-dropdown-items" style="overflow-x:hidden">
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mstcountry') }}'><i
                       class='nav-icon la la-cogs'></i> {{ trans('menu.country') }}</a>
               <a href="{{ backpack_url('mstcountry/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-province') }}'><i
                       class='nav-icon la la-cogs'></i>{{ trans('menu.province') }}</a>
               <a href="{{ backpack_url('mst-fed-province/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-district') }}'><i
                       class='nav-icon la la-cogs'></i> {{ trans('menu.district') }}</a>
               <a href="{{ backpack_url('mst-fed-district/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-local-level-type') }}'><i
                       class='nav-icon la la-cogs'></i>{{ trans('menu.localLevelType') }}</a>
               <a href="{{ backpack_url('mst-fed-local-level-type/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fed-local-level') }}'><i
                       class='nav-icon la la-cogs'></i> {{ trans('menu.localLevel') }}</a>
               <a href="{{ backpack_url('mst-fed-local-level/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-nepali-month') }}'><i
                       class='nav-icon la la-cogs'></i>{{ trans('menu.month') }}</a>
               <a href="{{ backpack_url('mst-nepali-month/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-fiscal-year') }}'><i
                       class='nav-icon la la-cogs'></i> {{ trans('menu.fiscalYear') }}</a>
               <a href="{{ backpack_url('mst-fiscal-year/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-gender') }}'><i
                       class='nav-icon la la-cogs'></i> {{ trans('menu.gender') }}</a>
               <a href="{{ backpack_url('mst-gender/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-religion') }}'><i
                       class='nav-icon la la-cogs'></i> Religion</a>
               <a href="{{ backpack_url('mst-religion/create') }}"><i class="fa fa-plus"></i></a>
           </li>
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-bank') }}'><i
                       class='nav-icon la la-cogs'></i> {{ trans('menu.bank') }}</a>
               <a href="{{ backpack_url('mst-bank/create') }}"><i class="fa fa-plus"></i></a>
           </li>
       </ul>
   </li>
   <hr class="hr-line">
@endhasrole

@hasrole('superadmin')
   <li class='nav-item'><a class='nav-link' href='{{ backpack_url('appclient') }}'><i
               class='nav-icon la la-users'></i>
           App Clients</a>
   </li>
   <hr class="hr-line">
@endhasrole

{{-- app-setting --}}
@hasanyrole('superadmin|clientadmin')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('app-setting') }}'><i
           class='nav-icon la la-users'></i>
       App Setting</a>
</li>
<hr class="hr-line">
@endhasanyrole

{{-- User Management --}}
@hasanyrole('superadmin|clientadmin|admin')
<li class="nav-item nav-dropdown">
   <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>User Management</a>
   <ul class="nav-dropdown-items" style="overflow-x:hidden">
       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('user/main') }}'><i
                   class='nav-icon la la-user'></i>{{ trans('menu.user') }}</a></li>
@hasanyrole('superadmin|clientadmin')

       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('role') }}'><i
                   class='nav-icon la la-cogs'></i>{{ trans('menu.role') }}</a></li>
@endhasanyrole

       @hasrole('superadmin')
           <li class='nav-item'><a class='nav-link' href='{{ backpack_url('permission') }}'><i
                       class='nav-icon la la-cogs'></i>{{ trans('menu.permission') }}</a></li>
       @endhasrole
   </ul>
</li>
@hasanyrole('superadmin|clientadmin')
<hr class="hr-line">
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('session_log') }}'><i class='nav-icon fa fa-cogs'></i>
    Session Logs</a></li>
<hr class="hr-line">
@endhasanyrole
@endhasanyrole
