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
