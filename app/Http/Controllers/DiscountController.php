<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;

class DiscountController extends Controller
{
    
  public function discounts()
    {
        $discounts = Discount::select('discount_code.*')->paginate(10);
        return view('admin.discounts', ['discounts' => $discounts]);
    }

    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                
                $discounts = Discount::where('code', 'like', '%' . $query . '%')
                    ->orWhere('discount', 'like', '%' . $query . '%')
                    ->orWhere('count', 'like', '%' . $query . '%')
                    ->orWhere('is_active', 'like', '%' . $query . '%')
                    ->orWhere('end_date', 'like', '%' . $query . '%')
                    ->orWhere('created_at', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $discounts = Discount::select('discount_code.*')->paginate(10);
            }
            if ($discounts->count() > 0) {
                foreach ($discounts as $discount) {
                    $out .= '<tr>
                    <td>' . $discount->code . '</td>
                    <td>' . $discount->discount . '</td>
                    <td>' . $discount->count . '</td>

                    <td>
                    <form method="POST" action="' . route('activateDiscount', $discount->id) . '">' .
                        csrf_field() . '' .
                        method_field("PATCH") . '
                      <button type="submit" class="btn btn-primary" style="margin-top: 10px;">';

                    if ($discount->is_active)
                        $out .= 'Activated';
                    else
                        $out .= 'Not Activated';

                    $out .= '
                      </button>
    
                    
                    </form>
                    
                  </td>
                    <td>' . $discount->end_date . '</td>
                    <td>' . $discount->created_at . '</td>

                
                    <td>
                    <form method="POST" action="'.route('discount.destroy',$discount->id).'" enctype="multipart/form-data">
                  '.@csrf_field().'
                  '.@method_field('DELETE').'
                  <!--we send post request, but we want delete-->
                  <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                    </td>
                  </tr>';
                }
                $out .= '<tr>
                <td colspan="3" align="center">' .
                    $discounts->links() . '
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

    public function activateDiscount(Request $request, Discount $discount)
    {
        //echo $discount;
        $d = Discount::where('id', '=', $discount->id)->first();
        $d->update(['is_active' => !($d->is_active)]);
        return redirect()->back();
    }

    public function deleteDiscount(Discount $discount)
    {
        $discount->delete();
        Session::flash('success', 'Discount was deleted');
        return back();
    }

    public function discountStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'numeric', 'digits_between:5,5'],
            'discount' => ['required', 'numeric', 'regex:/^\S*$/u'],
            'end_date' => ['required'],
            'count' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
        }
        if (Discount::where('code', '=', request('code'))->count() > 0) {
            //echo '+'.request('country_code').(int)request('phone');
            Session::flash('fail', 'this code is stored before');
            return back();
        }


        Discount::create([
            'code' => $request['code'],
            'discount' => $request['discount'],
            'count' => $request['count'],
            'end_date' => $request['end_date']
        ]);
        Session::flash('success', "The code is stored successfuly");
        return back();
    }
}
