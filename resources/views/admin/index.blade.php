<x-admin-master>

@section('content')

<h1 class="h3 mb-4 text-gray-800">Statistics</h1>

<div class="statistics" >Number of Admins<br>{{$admins}}</div>
<div class="statistics" >Number of Companies<br>{{$companies}}</div>
<div class="statistics" >Number of Drivers<br>{{$drivers}}</div>
<div class="statistics" >Number of Users<br>{{$users}}</div>
<div class="statistics" >Number of Codes<br>{{$codes}}</div>
<div class="statistics" >Number of Trucks<br>{{$trucks}}</div>
<div class="statistics" >Number of Goods<br>{{$goods}}</div>
<div class="statistics" >Number of Bills<br>{{$bills}}</div>
<div class="statistics" >Open Drivers's messages<br>{{$msgDrivers}}</div>
<div class="statistics" >Open Users's messages<br>{{$msgUsers}}</div>
<div class="statistics" >Open bill payment requests<br>{{$openBills}}</div>
<div class="statistics" >Paid bill payment requests<br>{{$paidBills}}</div>

@endsection

</x-admin-master>