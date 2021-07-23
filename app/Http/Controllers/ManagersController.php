<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ManagersController extends Controller
{
  //

  public function show()
  {
    $managers = Admin::select('admins.*')->where('type', '=', 'admin')->paginate(10);
    return view('admin.managers', ['managers' => $managers]);
  }

  public function search(Request $request)
  {
    if ($request->ajax()) {
      $out = '';
      $query = $request->get('query');
      if ($query != '') {
        $admins = Admin::where('name', 'like', '%' . $query . '%')
          ->orWhere('phone', 'like', '%' . $query . '%')
          ->orWhere('role', 'like', '%' . $query . '%')
          ->orWhere('email', 'like', '%' . $query . '%')->paginate(10);
      } else {
        $admins = Admin::select('admins.*')->paginate(10);
      }
      if ($admins->count() > 0) {
        foreach ($admins as $admin) {
          $out .= '<tr>
                    <td>' . $admin->name . '</td>
                    <td>' . $admin->email . '</td>
                    <td>' . $admin->phone . '</td>
                    <td>' . $admin->role . '</td>

              
                
                    <td style = " width: 160px;">
                     <!-- Button trigger modal -->
       <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
       data-bs-target="#example' . $admin->id . 'Modal">
         Edit
        </button>
        
        <form method = "POST" style = "display:inline;" action = "'.route('delete',$admin->id).'">
        '.csrf_field().
     
        '
        <button type="submit" class="btn btn-danger">
          Delete
         </button>
        </form>
        
        <!-- Modal -->
        <div class="modal fade" id="example' . $admin->id . 'Modal" tabindex="-1" 
        aria-labelledby="example' . $admin->id . 'ModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="example' . $admin->id . 'ModalLabel">Edit the manager\'s Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
      
              <form method="POST" action="' . route('edit', $admin) . '" enctype="multipart/form-data">' .
            csrf_field()
            . '
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="name" class="form-label">Name: </label>
                    <input type="text" name="name" class="form-control" id="name"  value = "' . $admin->name . '" required>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">E-mail: </label>
                    <input type="email" name="email" class="form-control" id="email"  value = "' . $admin->email . '" required>
                  </div>

                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone: </label>
                    <input type="text" name="phone" class="form-control" id="phone"  value = "' . $admin->phone . '" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password: </label>
                    <input type="password" name="password" class="form-control" id="password"   required>
                  </div>

                  <div class="mb-3">
                    <label for="role" class="form-label">Role: </label>
                  <select name = "role" id = "role">
                    <option value = "add">Add</option>
                    <option value = "edit">Edit</option>
                    <option value = "delete">Delete</option>

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

  public function edit(Request $request, Admin $manager)
  {
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

    $manager->update([
      'name' => $request['name'],
      'email' => $request['email'],
      'phone' => $request['phone'],
      'role' => $request['role'],
      'password' => Hash::make($request['password']),

    ]);
    Session::flash('success', "Manager's info has been updated successfully");
    return back();
  }

  public function delete(Request $request, $id){
    try{
    if($id == 1){
      Session::flash('fail', "You can't delete this manager");
    return back();
    }
      $manager = Admin::findOrFail($id);
      if(auth('web-admin')->user()->id == $id){
        Session::flash('fail', "You can't delete Your account, ask another manager to delete you");
        return back();
      }

      $manager->delete();
      Session::flash('success', " this Manager has been deleted successfully");
    return back();
    }
    catch(Throwable $e){
      Session::flash('fail', "Something went wrong");
      return back();
    }
    
  }

  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => ['required', 'string', 'max:255'],
      'phone' => ['required', 'numeric', 'digits_between:6,25', 'regex:/^\S*$/u'],
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
    'role' =>$request['role'],
    'phone' =>$request['phone']
  ]);

  Session::flash('success', "The manager is registered successfuly");
  return back();
  }

  
}
