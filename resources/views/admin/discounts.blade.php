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
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
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
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>

      </div>
    </div>
  </div>


  <br><br>          


  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
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
              <th>Delete</th>
            </tr>
          </thead>


          <tbody>
            @foreach($discounts as $discount)
            <tr>
              <td>{{$discount->code}}</td>
              <td>{{$discount->discount}}</td>
              <td>{{$discount->count}}</td>
              <td>
              <form method="POST" action="{{route('activateDiscount',$discount)}}">
                  @csrf
                  {{ method_field('PATCH') }}
                  <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                    @if($discount->is_active)
                    Activated
                    @else
                    Not Activated
                    @endif
                  </button>

                </form>
              
              </td>
              <td>{{$discount->end_date}}</td>
              <td>{{$discount->created_at}}</td>

              <td>
              <form method="POST" action="{{route('discount.destroy',$discount->id)}}" enctype="multipart/form-data">
                  @csrf
                  @method('DELETE')
                  <!--we send post request, but we want delete-->
                  <button type="submit" class="btn btn-danger">Delete</button>
                </form>
              </td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$discounts->links()}}


@endsection

</x-admin-master>