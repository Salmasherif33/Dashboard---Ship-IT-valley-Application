<x-admin-master>

@section('content')

<h1>Trucks</h1>


@if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

  @endif
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add a truck type
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Truck's Info</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{route('createTruck')}}" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="modal-body">
            <div class="mb-3">
              <label for="nameA" class="form-label">Name in Arabic: </label>
              <input type="text" name="nameA" class="form-control" id="nameA" required>
            </div>
            <div class="mb-3">
              <label for="nameE" class="form-label">Name in English: </label>
              <input type="text" name="nameE" class="form-control" id="nameE" required>
            </div>
            
            <div class="mb-3">
              <label for="status" class="form-label">Status: </label>
              <select name = "status" id = "role" required>
                    <option value = "1">Activated</option>
                    <option value = "0">Not Activated</option>
                

                    </select>
            </div>

            <div class="mb-3">
              <label for="max" class="form-label">Max weight: </label>
              <input type="number" name="max" class="form-control" id="max" required>
            </div>  


            <div class="mb-3">
              <label for="desciptions_ar" class="form-label">Description in Arabic: </label>
              <input type="text" name="desciptions_ar" class="form-control" id="desciptions_ar" required>
            </div> 

            <div class="mb-3">
              <label for="desciptions_en" class="form-label">Description in English: </label>
              <input type="text" name="desciptions_en" class="form-control" id="desciptions_en" required>
            </div>  
            
            <div class="mb-3">
              <label for="image" class="form-label">image: </label>
              <input type="file" class="form-control" name="image" required>
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
      <h6 class="m-0 font-weight-bold text-primary"> All Trucks Types</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name in Arabic</th>
              <th>Name in English</th>
              <th>Status</th>
              <th>Max weight</th>
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
    url:"/trucks/search?page="+page+"&query=",
    method:'GET',
    data:{query:query},
    dataType:'json',
    success:function(data){
     
      $('tbody').html(data.table_data);
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