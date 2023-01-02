@php
    $patient = App\Models\PatientAppointment::findOrFail($entry->id);
@endphp
@if($patient->appointment_status == 0)
<a data-fancybox data-type="ajax" data-src="/admin/patient-appointment/{{$entry->id}}/approve" href="javascript:;" class="fancybox btn btn-sm btn-info mr-2 p-0 px-2 mt-1"
    title="Approve"><i class="las la-check-circle" style="color: white;"></i>Approve</a>
@endif