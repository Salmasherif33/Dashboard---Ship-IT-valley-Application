<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CompanyController extends Controller
{
    //
    public function show(){
        $companies = Admin::where('type', 'company')->select('admins.*')->paginate(10);
        return view('admin.companies',['companies'=>$companies]);
    }

    public function search(Request $request){
        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
              $companies = Admin::where('type', 'company')->where('name', 'like', '%' . $query . '%')
              ->where('type', 'company')->orWhere('phone', 'like', '%' . $query . '%')
              ->where('type', 'company')->orWhere('is_active', 'like', '%' . $query . '%')
                ->select('admins.*')->paginate(10);
            } else {
              $companies = Admin::where('type','company')->select('admins.*')->paginate(10);
            }
            if ($companies->count() > 0) {
              foreach ($companies as $company) {
                $out .= '<tr>
                          <td>' . $company->name . '</td>
                          <td>' . $company->email . '</td>
                          <td>' . $company->phone . '</td>
                          <td>
                          <form method="POST" action="' . route('ActivateCompany', $company->id) . '">' .
                            csrf_field() . '' .
                            method_field("PATCH") . '
                            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">';
    
                        if ($company->is_active)
                            $out .= 'Activated';
                        else
                            $out .= 'Not Activated';
    
                        $out .= '
                            </button>
          
          
                          </form>
                        </td>
      
                    
                      
                          <td style = " width: 160px;">
                           <!-- Button trigger modal -->
             <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
             data-bs-target="#example' . $company->id . 'Modal">
               Edit
              </button>
              
              <form method = "POST" style = "display:inline;" action = "'.route('deleteCompany',$company->id).'">
              '.csrf_field().
           
              '
              <button type="submit" class="btn btn-danger">
                Delete
               </button>
              </form>
              
              <!-- Modal -->
              <div class="modal fade" id="example' . $company->id . 'Modal" tabindex="-1" 
              aria-labelledby="example' . $company->id . 'ModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="example' . $company->id . 'ModalLabel">Edit the manager\'s Info</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
            
                    <form method="POST" action="' . route('editCompany', $company) . '" enctype="multipart/form-data">' .
                  csrf_field()
                  . '
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="name" class="form-label">Name: </label>
                          <input type="text" name="name" class="form-control" id="name"  value = "' . $company->name . '" required>
                        </div>
      
                        <div class="mb-3">
                          <label for="email" class="form-label">E-mail: </label>
                          <input type="email" name="email" class="form-control" id="email"  value = "' . $company->email . '" required>
                        </div>
      
                        <div class="mb-3">
                          <label for="phone" class="form-label">Phone: </label>
                          <input type="text" name="phone" class="form-control" id="phone"  value = "' . $company->phone . '" required>
                        </div>
      
                        <div class="mb-3">
                          <label for="password" class="form-label">Password: </label>
                          <input type="password" name="password" class="form-control" id="password"   required>
                        </div>
      
                        <div class="mb-3">
                          <label for="role" class="form-label">Company Status: </label>
                        <select name = "role" id = "role">
                          <option value = "1">Activated</option>
                          <option value = "0">Not Activated</option>
                          
                          </select>
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
                  $companies->links() .'
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

    public function edit(Request $request, Admin $company){
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'role' => ['required'],
            'password' => ['required']
      
          ]);
          if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
          }
      
          $company->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'is_active' => $request['role'],
            'password' => Hash::make($request['password']),
      
          ]);
          Session::flash('success', "Manager's info has been updated successfully");
          return back();
    }

    public function delete(Request $request, $id){
        try{
          
              $company = Admin::findOrFail($id);
              if(auth('web-admin')->user()->id == $id){
                Session::flash('fail', "You can't delete Your account, ask another manager to delete you");
                return back();
              }
        
              $company->delete();
              Session::flash('success', " this Manager has been deleted successfully");
            return back();
            }
            catch(Throwable $e){
              Session::flash('fail', "Something went wrong");
              return back();
            }
    }
    public function activateCompany(Admin $company){
        $d = Admin::where('id', '=', $company->id)->first();
        $d->update(['is_active' => !($d->is_active)]);
        return redirect()->back();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^\S*$/u'],
            'password' => ['required', 'string', 'min:8'],
            'email' =>['required'],
            'role' =>['required']
        ]);
        if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
        }
      
        Admin::create([
          'name' =>$request['name'],
          'email' =>$request['email'],
          'password'=> Hash::make($request['password']),
          'is_active' =>$request['role'],
          'phone' =>$request['phone'],
          'type' => "company",
          'role' =>"add"
        ]);
      
        Session::flash('success', "The manager is registered successfuly");
        return back();
    }
}
