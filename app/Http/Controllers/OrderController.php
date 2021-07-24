<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Financial;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
   
    public function show(){
        $orders = Order::join('users','orders.users_id','=','users.id')
        ->join('drivers','orders.drivers_id','=','drivers.id')->join('bills','bills.orders_id','=','orders.id')
        ->select('users.name','drivers.name','bills.cost','bills.payment_type','bills.status','orders.*')->paginate(10);
        return view('admin.orders',['orders'=>$orders]);
    }

    public function search(Request $request){
        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $orders =  Order::join('drivers', 'orders.drivers_id', '=', 'drivers.id')
                ->join('users','orders.users_id','=','users.id')->join('bills','bills.orders_id','=','orders.id')
                ->select('drivers.name as DriverName', 'users.name as UserName','bills.cost','bills.payment_type'
                    ,'bills.cost','bills.status as BillStatus','orders.*')
                    ->where('drivers.name', 'like', '%' . $query . '%')
                    ->orWhere('users.name', 'like', '%' . $query . '%')
                    ->orWhere('orders.code', 'like', '%' . $query . '%')
                    ->orWhere('bills.cost', 'like', '%' . $query . '%')
                    ->orWhere('bills.payment_type', 'like', '%' . $query . '%')
                    ->orWhere('bills.payment_type', 'like', '%' . $query . '%')
                    ->orWhere('bills.status', 'like', '%' . $query . '%')
                    ->orWhere('orders.status', 'like', '%' . $query . '%')
                    ->orWhere('orders.created_at', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $orders = Order::join('drivers', 'orders.drivers_id', '=', 'drivers.id')
                ->join('users','orders.users_id','=','users.id')->join('bills','bills.orders_id','=','orders.id')
                    ->select('drivers.name as DriverName', 'users.name as UserName','bills.cost','bills.payment_type'
                    ,'bills.cost','bills.status as BillStatus','orders.*')->paginate(10);
            }
            if ($orders->count() > 0) {
                foreach ($orders as $order) {
                    $out .= '<tr>
                    <td>' . $order->DriverName . '</td>
                    <td>' . $order->UserName . '</td>
                    <td>' . $order->code . '</td>
                    <td>' . $order->cost . '</td>
                    <td>' . $order->payment_type . '</td>
                    <td>' . $order->BillStatus . '</td>
                    <td>' . $order->status . '</td>
                    <td>' . $order->created_at . '</td>

                    
                  </tr>';
                }
                $out .= '<tr>
                <td colspan="3" align="center">'.
                  $orders->links() .'
                </td>
               </tr>';
            } else {
                $out .= '  <tr>
                <td align="center" colspan="5">No Data Found</td>
               </tr>';
            }
            $data = array(
                'table_data' => $out
            );
            echo json_encode($data);
        
        }
    }
    public function showBills(){
        $bills = Bill::join('orders','bills.orders_id','=','orders.id')
        ->join('users','users.id','=','orders.users_id')
        ->select('users.name','orders.code','bills.*')->paginate(10);
       
        return view('admin.requests',['bills'=>$bills]);
    }

    public function searchBills(Request $request){
        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
           
            if ($query != '') {
                $bills =   Bill::join('orders','bills.orders_id','=','orders.id')
                ->join('users','users.id','=','orders.users_id')
                ->select('users.name','orders.code','bills.*')
                    ->where('users.name', 'like', '%' . $query . '%')
                    ->orWhere('orders.code', 'like', '%' . $query . '%')
                    ->orWhere('bills.cost', 'like', '%' . $query . '%')
                    ->orWhere('bills.status', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $bills =  Bill::join('orders','bills.orders_id','=','orders.id')
                ->join('users','users.id','=','orders.users_id')
                ->select('users.name','orders.code','bills.*')->paginate(10);
            }
            if ($bills->count() > 0) {
                foreach ($bills as $bill) {
                    $out .= '<tr>
                    <td>' . $bill->name . '</td>
                    <td>' . $bill->cost . '</td>
                    <td>' . $bill->code . '</td>
                  
                   
                    <td>
                   ';

                  if ($bill->status == 'waiting'){
                      $out .= '<form method="POST" action="' . route('acceptOrder', $bill->id) . '" style = "display:inline;">' .
                      csrf_field() . '' .
                      method_field("PATCH") . '
                      <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                       waiting...click to accept the order  </button> </form>

                       <form method="POST" action="' . route('refuseBill', $bill) . '" style = "display:inline;">' .
                       csrf_field() . '
                       <button type="submit" class="btn btn-danger" style="margin-top: 10px;">
                        X</button> </form>
 


                       </td>
                    
                    
                    
                  </tr>
                     
                       
                       
                       ';
                  }
                  else if($bill->status == 'paid'){
                  $out .= '<form method="POST" action="' . route('acceptOrder', $bill->id) . '">' .
                  csrf_field() . '' .
                  method_field("PATCH") . '
                  <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                   Aceepted  </button> </form>
                   
                   
                   </td>
                    
                 
                   
                 </tr>';}

                  else{
                      $out .= ''.$bill->status.'</td>
                    
                     
                      
                    </tr>';
                  }
    
    
                }
                $out .= '<tr>
                <td colspan="3" align="center">'.
                  $bills->links() .'
                </td>
               </tr>';
            } 
           
            else {
                $out .= '  <tr>
                <td align="center" colspan="5">No Data Found</td>
               </tr>';
            }
            $data = array(
                'table_data' => $out
            );
            echo json_encode($data);
        
        }
    }

    public function acceptBill(Bill $request){
        $d = Bill::where('id','=',$request->id)->first();
        $d->update(['status'=>'paid']);
        return redirect()->back();

    }
    public function refuseBill(Bill $bill){
        $d = Bill::where('id','=',$bill->id)->first();
        $d->update(['status'=>'refused']);
        return redirect()->back();

    }
}
