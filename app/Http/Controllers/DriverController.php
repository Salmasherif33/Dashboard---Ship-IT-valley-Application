<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bill;
use App\Models\Contact;
use App\Models\Discount;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    //
    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $drivers = Driver::where('name', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%')
                    ->orWhere('language', 'like', '%' . $query . '%')
                    ->orWhere('created_at', 'like', '%' . $query . '%')
                    ->orWhere('car_name', 'like', '%' . $query . '%')
                    ->orWhere('car_model', 'like', '%' . $query . '%')
                    ->orWhere('car_license_number', 'like', '%' . $query . '%')
                    ->orWhere('country_code', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')->paginate(10);
            } else {
                $drivers = Driver::select('drivers.*')->paginate(10);
            }
            if ($drivers->count() > 0) {
                foreach ($drivers as $driver) {
                    $out .= '<tr>
                    <td>' . $driver->name . '</td>
                    <td>' . $driver->phone . '</td>
                    <td>
                      <form method="POST" action="' . route('activateDriver', $driver->id) . '">' .
                        csrf_field() . '' .
                        method_field("PATCH") . '
                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">';

                    if ($driver->is_active)
                        $out .= 'Activated';
                    else
                        $out .= 'Not Activated';

                    $out .= '
                        </button>
      
      
                      </form>
                    </td>
                    <td>' . $driver->language . '</td>
                    <td>' . $driver->created_at . '</td>
                    <td>
                     <!-- Button trigger modal -->
       <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
       data-bs-target="#example' . $driver->id . 'Modal">
          Add fees
        </button>
        <!-- Modal -->
        <div class="modal fade" id="example' . $driver->id . 'Modal" tabindex="-1" 
        aria-labelledby="example' . $driver->id . 'ModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="example' . $driver->id . 'ModalLabel">Add fees for driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
      
              <form method="POST" action="' . route('addfees', $driver) . '" enctype="multipart/form-data">' .
                        csrf_field()
                        . '
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="fees" class="form-label">amount: </label>
                    <input type="number" name="fees" class="form-control" id="fees" required>
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
      
                    
                    </td>
                  </tr>';
                }

                $out .= '<tr>
                <td colspan="3" align="center">'.
                  $drivers->links() .'
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

    public function showDrivers()
    {
        $trucks = DB::table('trucks_types')->select('trucks_types.*')->get();
        $drivers = Driver::select('drivers.*')->paginate(10);


        return view('admin.showDrivers', ['drivers' => $drivers, 'trucks' => $trucks]);
    }

    public function activateDriver(Driver $driver)
    {
        //echo $driver;
        $d = Driver::where('id', '=', $driver->id)->first();
        $d->update(['is_active' => !($d->is_active)]);
        return redirect()->back();
    }

    public function driverStore(Request $request)
    {

        //echo $request->trucks_types_id. "\n";
        //echo $request->country_code. "\n";
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'digits_between:6,25', 'regex:/^\S*$/u'],
            'password' => ['required', 'string', 'min:8'],
            'country_code' => ['required', 'string'],
            'car_name' => ['required', 'string'],
            'car_model' => ['required', 'string'],
            'car_license_number' => ['required', 'numeric'],
            'driving_license_image' => ['file', 'required'],
            'car_license_image' => ['file', 'required'],
            'car_photo' => ['file', 'required'],
            'trucks_types_id' => ['required', 'numeric'],
            'id_image' => ['file', 'required']
        ]);
        if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
        }
        if (Driver::where('phone', '=', '+' . request('country_code') . (int)request('phone'))->count() > 0) {
            //echo '+'.request('country_code').(int)request('phone');
            Session::flash('fail', 'this driver is registered before');
            return back();
        }

        if (request('image')) {
            //$originalName = $data['image']->getClientOriginalName();

            $image = request('image')->store('image');
        }
        $driving_license = request('driving_license_image')->store('driving_license');
        $car_license = request('car_license_image')->store('car_license');
        $id_image = request('id_image')->store('id_image');
        $car_photo = request('car_photo')->store('car_photo');
        Driver::create([
            'name' => $request['name'],
            'country_code' => $request['country_code'],
            'phone' => '+' . $request['country_code'] . $request['phone'],
            'password' => Hash::make($request['password']),
            'car_name' => $request['car_name'],
            'car_model' => $request['car_model'],
            'car_license_number' => $request['car_license_number'],
            'driving_license_image' => $driving_license,
            'car_license_image' => $car_license,
            'id_image' => $id_image,
            'car_photo' => $car_photo,
            'image' => $image,
            'trucks_types_id' => $request['trucks_types_id'],
            'api_token' => str_random(65)
        ]);
        Session::flash('success', "The driver is registered successfuly");
        return back();
    }


    public function addfees(Request $request, Driver $driver)
    {
        $validator = Validator::make($request->all(), [
            'fees' => ['required', 'numeric']
        ]);
        if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
        }


        $driver->update(['fees' => $request['fees']]);
        Session::flash('success', "fees added to the driver");
        return back();
    }


    public function discounts(Request $request)
    {
        $discounts = Discount::select('discount_code.*')->paginate(10);
        return view('admin.discounts', ['discounts' => $discounts]);
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


    public function usersComplaints(Request $request)
    {

        $contacts = DB::table('contacts')->join('users', 'contacts.users_id', '=', 'users.id')
            ->join('contacts_types', 'contacts.contacts_types_id', '=', 'contacts_types.id')
            ->select(['name', 'phone', 'name_en', 'contacts.*'])->paginate(10);
        //print_r($contacts) ;
        return view('admin.users_complaints', ['contacts' => $contacts]);
    }
    public function driversComplaints(Request $request)
    {
        $contacts = DB::table('contacts')->join('drivers', 'contacts.drivers_id', '=', 'drivers.id')->join('contacts_types', 'contacts.contacts_types_id', '=', 'contacts_types.id')->select(['name', 'phone', 'name_en', 'contacts.*'])->paginate(10);
        //print_r($contacts) ;
        return view('admin.drivers_complaints', ['contacts' => $contacts]);
    }
}
