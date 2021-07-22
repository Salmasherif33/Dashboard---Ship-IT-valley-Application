<?php

namespace App\Http\Controllers;

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
}
