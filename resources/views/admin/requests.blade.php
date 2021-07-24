<x-admin-master>



@section('content')


<h1>Requests</h1>



@if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

  @endif


  <br><br>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary"> All Bills</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name of Order's Owner</th>
              <th>Payment Price</th>
              <th>Order Code</th>
              <th>Order Status</th>

            </tr>
          </thead>


          <tbody class="dyn">
          
          </tbody>
        </table>
        <input type="hidden" name = "hidden_page" value="1" id="hidden_page">
      </div>
    </div>
  </div>

  

@endsection



</x-admin-master>
<script>

$(document).ready(function(){
  fetch_data();
  function fetch_data(query, page){
  $.ajax({
    url:"/requests/search?page="+page+"&query=",
    method:'GET',
    data:{query:query},
    dataType:'json',
    success:function(data){
      $('.dyn').html('');
      $('.dyn').html(data.table_data);
      //$('#total_records').text(data.total_data);
    }
  })
  }
  $(document).on('keyup', '#search' , function(){
     
     var query = $(this).val(); //fetch the data in the search bar to this var.
     var page = $('#hidden_page').val();
     fetch_data(query,page);
   });

  $(document).on('click','.pagination a',function(event){

    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $('#hidden_page').val(page);
    var query = $('#serach').val();

    fetch_data(query,page);
  });
  
});

</script>