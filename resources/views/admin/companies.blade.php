<x-admin-master>

@section('content')

<h1>Companies</h1>


@if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

  @endif
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add a Company
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Companies's Info</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{route('createCompany')}}" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Name: </label>
              <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email: </label>
              <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone: </label>
              <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password: </label>
              <input type="password" class="form-control" name="password" required>
            </div>
          
           
            <div class="mb-3">
                    <label for="role" class="form-label">Company status: </label>
                  <select name = "role" id = "role" required>
                    <option value = "1">Activated</option>
                    <option value = "0">Not Activated</option>
            
                    </select>

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
      <h6 class="m-0 font-weight-bold text-primary"> All Companies</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Operations</th>
            

            </tr>
          </thead>


          <tbody id="dyn">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$companies->links()}}

@endsection



</x-admin-master>


<script>

$(document).ready(function(){
  fetch_data();
  function fetch_data(query=''){
  $.ajax({
    url:"{{route('CompanyController.search')}}",
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