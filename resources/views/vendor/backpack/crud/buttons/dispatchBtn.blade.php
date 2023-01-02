@if($entry->dispatch_status != '1')
<a href="javascript:;" class="btn btn-sm btn-success mr-2 p-0 px-2 ml-2" title="Dispatch Report" onclick="dispatchUpdate({{$entry->id}})"><i class="la la-send" style="color: white;"></i> Dispatch</a>
@else
<a href="javascript:;" class="btn btn-sm btn-primary mr-2 p-0 px-2  ml-2" title="Dispatch Report" ><i class="la la-send" style="color: white;"></i> Dispatched</a>
@endif
<script>
    function dispatchUpdate(entry_id)
    {
       if(entry_id)
       {
        swal({
            closeOnClickOutside: false,
            title: "Confirm And Dispatch Result ?",
            text: "Please verify the results once, before dispatching !",
            buttons: {
                no: {
                    text: " No ",
                    value: false,
                    visible: true,
                    className: "btn btn-secondary px-5",
                    closeModal: true,
                },
                yes: {
                    text: " Yes ",
                    value: true,
                    visible: true,
                    className: "btn btn-success px-5",
                    closeModal: true,
                }
            },
        }).then((confirmResponse) => {
            if (confirmResponse) {
                LMS.lmsLoading(true,'Updating Status ...');
                axios.post('/admin/lab/'+entry_id+'/dispatch', {status:true})
                .then((response) => {
                    if(response.data.status == 'success'){
                        swal("Success !",'Result Dispatched Successfull !', "success")
                        location.reload();
                    }else{
                        swal("Error !", response.data.message, "error")
                    }
                    LMS.lmsLoading(false);
                }, (error) => {
                    swal("Error !", error.response.data.message, "error")
                    LMS.lmsLoading(false);
                });
            }else{

            }
        });
       }
    }

</script>

