<x-admin-master>

@section('content')

<h1>Discounts</h1>

@if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

@endif


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add a discount
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add a discount</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{route('discountStore')}}" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Code: </label>
              <input type="text" name="code" class="form-control" id="name" required>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">Count: </label>
              <input type="number" name="count" class="form-control" id="name" required>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">Discount: </label>
              <input type="number" name="discount" class="form-control" id="name" required>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">end date: </label>
              <input type="date" name="end_date" class="form-control" id="name" required>
            </div>


            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>


  <br><br>          


  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">All discounts</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Code</th>
              <th>Discount</th>
              <th>Count</th>
              <th>Status</th>
              <th>End date</th>
              <th>Created at</th>
              <th>Operations</th>
            </tr>
          </thead>


          <tbody id="dyn">

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
  function fetch_data(query,page){
  $.ajax({
    url:"/discounts/search?page="+page+"&query=",
    method:'GET',
    data:{query:query},
    dataType:'json',
    success:function(data){
     
      $('tbody').html(data.table_data);
    }
  })
  }

  $(document).on('keyup', '#search' , function(){
     console.log("hii");
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