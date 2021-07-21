<x-admin-master>

@section('content')

<h1>Financials</h1>

@if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

  @endif


  <br><br>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary"> All Financials</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Total benefits</th>
              <th>Paid money</th>
              <th>Created at</th>
              <th>Updated at</th>
              <th>Modify</th>
            </tr>
          </thead>


          <tbody id="dyn">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$financials->links()}}

@endsection



</x-admin-master>
<script>

$(document).ready(function(){
  fetch_data();
  function fetch_data(query=''){
  $.ajax({
    url:"{{route('FinancialController.search')}}",
    method:'GET',
    data:{query:query},
    dataType:'json',
    success:function(data){
      $('tbody').html(data.table_data);
      //$('#total_records').text(data.total_data);
    }
  })
  }

  $(document).on('keyup', '#search' , function(){
     
    var query = $(this).val(); //fetch the data in the search bar to this var.
    fetch_data(query);
  });
});

</script>

