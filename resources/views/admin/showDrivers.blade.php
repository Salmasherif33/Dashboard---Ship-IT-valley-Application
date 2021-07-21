<x-admin-master>

  @section('content')


  <h1>Drivers</h1>
  @if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

  @endif
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add a driver
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{route('driverStore')}}" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Name: </label>
              <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
              <label for="country_code" class="form-label">Country code: </label>
              <x-model></x-model>
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
              <label for="car_name" class="form-label">Car Name: </label>
              <input type="text" class="form-control" name="car_name">
            </div>

            <div class="mb-3">
              <label for="car_model" class="form-label">Car model: </label>
              <input type="text" class="form-control" name="car_model" required>
            </div>

            <div class="mb-3">
              <label for="car_license_number" class="form-label">Car license number: </label>
              <input type="text" class="form-control" name="car_license_number" required>
            </div>

            <div class="mb-3">
              <label for="driving_license_image" class="form-label">Driving license image: </label>
              <input type="file" class="form-control" name="driving_license_image" required>
            </div>

            <div class="mb-3">
              <label for="car_license_image" class="form-label">Car license image: </label>
              <input type="file" class="form-control" name="car_license_image" required>
            </div>

            <div class="mb-3">
              <label for="car_photo" class="form-label">Car Photo: </label>
              <input type="file" class="form-control" name="car_photo" required>
            </div>

            

            <div class="mb-3">
              <label for="id_image" class="form-label">ID image: </label>
              <input type="file" class="form-control" name="id_image" required>
            </div>

            <div class="mb-3">
              <label for="image" class="form-label">image: </label>
              <input type="file" class="form-control" name="image" required>
            </div>

            <div class="mb-3">
              <label for="trucks_types_id" class="form-label">Truck type: </label>
              <select name="trucks_types_id" id="" required>
                @foreach($trucks as $truck)
                <option value="{{$truck->id}}">{{$truck->descriptions_en}}</option>
                @endforeach
              </select>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>

      </div>
    </div>
  </div>


  <br><br>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary"> All Drivers</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Phone</th>
              <th>Activation</th>
              <th>Language</th>
              <th>Created at</th>
              <th>Add money</th>
            </tr>
          </thead>


          <tbody id="dyn">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$drivers->links()}}

  @endsection



</x-admin-master>



<script>

$(document).ready(function(){
  fetch_data();
  function fetch_data(query=''){
  $.ajax({
    url:"{{route('DriverController.search')}}",
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