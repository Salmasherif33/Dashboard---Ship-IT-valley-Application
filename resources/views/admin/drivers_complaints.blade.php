<x-admin-master>
@section('content')

<h1>Driver' Complaints</h1>


@if(session('fail'))
  <div class="alert alert-danger">{{session('fail')}}</div>

  @elseif(session('success'))
  <div class="alert alert-success">{{session('success')}}</div>

@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Phone</th>
              <th>Code</th>
              <th>Contact Type</th>
            
            </tr>
          </thead>


          <tbody>
            @foreach($contacts as $contact)
            <tr>
              <td>{{$contact->name}}</td>
              <td>{{$contact->phone}}</td>
              <td>{{$contact->code}}</td>
              <td>{{$contact->name_en}}</td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$contacts->links()}}
@endsection

</x-admin-master>