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
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('patient') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('menu.patient') }}</a>
            <a href="{{ backpack_url('patient/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('emergency-patient') }}'><i
                    class='la la-user nav-icon'></i>{{ trans('menu.ePatient') }}</a>
            <a href="{{ backpack_url('emergency-patient/create') }}"><i class="fa fa-plus"></i></a>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('patient-appointment') }}">
                <i class="nav-icon la la-user"></i> Patient appointments</a>
            <a href="{{ backpack_url('patient-appointment/create') }}"><i class="fa fa-plus"></i></a>
        </li> --}}
    </ul>
 </li>
 <hr class="hr-line">
{{-- ///-----pms --}}
{{-- patient --}}
@hasanyrole('superadmin|clientadmin|admin|reception|lab_admin')
<li class="nav-item nav-dropdown">
   <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>Patient</a>
   <ul class="nav-dropdown-items" style="overflow-x:hidden">
       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('patient') }}'><i
                   class='la la-user nav-icon'></i>{{ trans('menu.patient') }}</a>
           <a href="{{ backpack_url('patient/create') }}"><i class="fa fa-plus"></i></a>
       </li>
       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('emergency-patient') }}'><i
                   class='la la-user nav-icon'></i>{{ trans('menu.ePatient') }}</a>
           <a href="{{ backpack_url('emergency-patient/create') }}"><i class="fa fa-plus"></i></a>
       </li>
       <li class="nav-item"><a class="nav-link" href="{{ backpack_url('patient-appointment') }}">
               <i class="nav-icon la la-user"></i> Patient appointments</a>
           <a href="{{ backpack_url('patient-appointment/create') }}"><i class="fa fa-plus"></i></a>
       </li>
   </ul>
</li>
<hr class="hr-line">
@endhasanyrole
{{-- bill --}}
@hasanyrole('superadmin|clientadmin|admin|reception|lab_admin|finance')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('billing/patient-billing/recent') }}'><i
           class='nav-icon la la-columns'></i> Patient billings</a>
   <a href="{{ backpack_url('billing/patient-billing/recent/create') }}"><i class="fa fa-plus"></i></a>
</li>
<hr class="hr-line">
@endhasanyrole

{{-- lis --}}
@hasanyrole('superadmin|clientadmin|admin|lab_admin|doctor|lab_technician|lab_technologist|reception|referral')

<li class="nav-item nav-dropdown">
   <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>LIS</a>
   <ul class="nav-dropdown-items" style="overflow-x:hidden">
        @hasanyrole('superadmin|clientadmin|admin|lab_admin|doctor|lab_technician|lab_technologist|reception')
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab-patient-test-data/pending_orders') }}'><i
                class='nav-icon la la-cogs'></i>Sample Collection</a></li>
        @endhasanyrole
    @hasanyrole('superadmin|clientadmin|admin|lab_admin|doctor|lab_technician|lab_technologist')
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab/result-entry/pending_orders') }}'><i
                class='nav-icon la la-cogs'></i> Result entries</a></li>
    @endhasanyrole
    @hasanyrole('superadmin|clientadmin|admin|lab_admin|referral|reception')
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab/dispatch-result') }}'>
               <i class='nav-icon la la-cogs'></i> Result Dispatch</a></li>
    @endhasanyrole

   </ul>
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

{{-- Excel Upload --}}
@hasanyrole('superadmin|clientadmin|admin|lab_admin')

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('excel-upload') }}'><i
           class='nav-icon la la-upload'></i>
       Excel Upload</a>
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

{{-- Lab Master --}}
@hasanyrole('superadmin|clientadmin|admin')

<li class="nav-item nav-dropdown">
   <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>Lab Master</a>
   <ul class="nav-dropdown-items" style="overflow-x:hidden">
       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab/lab-panel') }}'><i
                   class='nav-icon la la-cogs'></i>Lab Tests</a>
           <a href="{{ backpack_url('lab/lab-panel/create') }}"><i class="fa fa-plus"></i></a>
       </li>

       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab/lab-items') }}'><i
                   class='nav-icon la la-cogs'></i> Lab Items</a>
       </li>
       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab/lab-mst-categories') }}'><i
                   class='nav-icon la la-cogs'></i> Lab Categories</a>
           <a href="{{ backpack_url('lab/lab-mst-categories/create') }}"><i class="fa fa-plus"></i></a>
       </li>


       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lab/lab-group') }}'><i
                   class='nav-icon la la-cogs'></i> Lab groups</a>
           <a href="{{ backpack_url('lab/lab-group/create') }}"><i class="fa fa-plus"></i></a>
       </li>


       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-lab-sample') }}'><i
                   class='nav-icon la la-cogs'></i> {{ trans('menu.labsample') }}</a>
           <a href="{{ backpack_url('mst-lab-sample/create') }}"><i class="fa fa-plus"></i></a>
       </li>


       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mst-lab-method') }}'><i
                   class='nav-icon la la-cogs'></i> {{ trans('menu.labmethod') }}</a>
           <a href="{{ backpack_url('mst-lab-method/create') }}"><i class="fa fa-plus"></i></a>
       </li>


       <li class="nav-item"><a class="nav-link" href="{{ backpack_url('lab/interpretation') }}">
               <i class="nav-icon la la-cogs"></i> Interpretations</a>
           <a href="{{ backpack_url('lab/interpretation/create') }}"><i class="fa fa-plus"></i></a>
       </li>

   </ul>
</li>
<hr class="hr-line">
@endhasanyrole

{{-- Data Master --}}
@hasanyrole('superadmin|clientadmin|admin')

<li class="nav-item nav-dropdown">
   <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-tasks"></i>Data Master</a>
   <ul class="nav-dropdown-items" style="overflow-x:hidden">
       <li class='nav-item'><a class='nav-link' href='{{ backpack_url('hrmstdepartments') }}'><i
                   class='nav-icon la la-cogs'></i>Departments</a>
           <a href="{{ backpack_url('hrmstdepartments/create') }}"><i class="fa fa-plus"></i></a>
       </li>
       <li class="nav-item"><a class="nav-link" href="{{ backpack_url('referral') }}"><i
                   class="nav-icon la la-user nav-columns"></i> Referral</a>
           <a href="{{ backpack_url('referral/create') }}"><i class="fa fa-plus"></i></a>
       </li>
       <li class="nav-item"><a class="nav-link" href="{{ backpack_url('mst-bank') }}"><i
                   class="nav-icon la la-user nav-columns"></i> Bank</a>
           <a href="{{ backpack_url('mst-bank/create') }}"><i class="fa fa-plus"></i></a>
       </li>
   </ul>
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
