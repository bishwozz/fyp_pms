

<div class="row mb-3 ml-5">
    <div class="col-md-12">
          <ul class="nav nav-tabs flex-column flex-sm-row mt-2"id="billsTab" role="tablist">
              <li role="presentation" class="nav-item">
                  <a class="nav-link tab-link {{$recent_bills}} p-1 px-3 mr-2" 
                  href="{{ url($crud->route)}}?bill_status=recent" role="tab">Recent Bills</a>
              </li>
              <li role="presentation" class="nav-item ">
                  <a class="nav-link tab-link {{$accepted_bills}} p-1 px-3" 
                  href="{{ url($crud->route)}}?bill_status=accepted" role="tab">Accepted Bills</a>
              </li>
              <li role="presentation" class="nav-item ">
                  <a class="nav-link tab-link {{$pending_bills}} p-1 px-3" 
                  href="{{ url($crud->route)}}?bill_status=pending" role="tab">Credit Bills</a>
              </li>
              <li role="presentation" class="nav-item ">
                  <a class="nav-link tab-link {{$cancelled_bills}} p-1 px-3" 
                  href="{{ url($crud->route)}}?bill_status=cancelled" role="tab">Cancelled Bills</a>
              </li>
          </ul>
        </div>
  </div>

  <style>
    #billsTab li a.active{
        border:1px solid lightgray !important;
        border-bottom:4px solid blue !important;
        color: blue;
        font-weight: 550;
    }
    #billsTab li a:hover{
        border:1px solid lightgray !important;
        color:black;
        border-bottom:4px solid lightblue !important;
  }
  </style>
 
 