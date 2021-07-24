<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BankController extends Controller
{
    //
    public function show()
    {
        $banks = Bank::select('bank_accounts.*')->paginate(10);
        return view('admin.banks', ['banks' => $banks]);
    }

    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $banks = Bank::where('name_ar', 'like', '%' . $query . '%')
                    ->orWhere('name_en', 'like', '%' . $query . '%')
                    ->orWhere('number', 'like', '%' . $query . '%')
                    ->orWhere('created_at', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $banks = Bank::select('bank_accounts.*')->paginate(10);
            }
            if ($banks->count() > 0) {
                foreach ($banks as $bank) {
                    $out .= '<tr>
                    <td>' . $bank->name_ar . '</td>
                    <td>' . $bank->name_en . '</td>
                    <td>' . $bank->number . '</td>
                    <td>' . $bank->created_at . '</td>
                
                    <td>
                     <!-- Button trigger modal -->
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                     data-bs-target="#example' . $bank->id . 'Modal">
                        Edit
                      </button>

                      <form method = "POST" style = "display:inline;" action = "'.route('deleteBank',$bank->id).'">
                      '.csrf_field().
                   
                      '
                      <button type="submit" class="btn btn-danger">
                        Delete
                       </button>
                      </form>


                      <!-- Modal -->
                      <div class="modal fade" id="example' . $bank->id . 'Modal" tabindex="-1" 
                      aria-labelledby="example' . $bank->id . 'ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="example' . $bank->id . 'ModalLabel">Edit abank account</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                    
                            <form method="POST" action="' . route('editBank', $bank) . '" enctype="multipart/form-data">' .
                                      csrf_field()
                                      . '
                              <div class="modal-body">
                              <div class="mb-3">
                              <label for="nameA" class="form-label">Name in Arabic: </label>
                              <input type="text" name="nameA" class="form-control" id="nameA"  value = "'.$bank->name_ar.'" required>
                            </div>
                            <div class="mb-3">
                              <label for="nameE" class="form-label">Name in English: </label>
                              <input type="text" name="nameE" class="form-control" id="nameE" value = "'.$bank->name_en.'" required>
                            </div>
                            
                            <div class="mb-3">
                              <label for="acc" class="form-label">Account Number: </label>
                              <input type="text" class="form-control" name="acc" value = "'.$bank->number.'" required>
                            </div>
                            <div class="mb-3">
                              <label for="image" class="form-label">image: </label>
                              <input type="file" class="form-control" name="image">
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
                  $banks->links() .'
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

    public function delete($id){
        try{
           
              $bank = Bank::findOrFail($id);
            
              $bank->delete();
              Session::flash('success', " this bank account has been deleted successfully");
            return back();
            }
            catch(Throwable $e){
              Session::flash('fail', "Something went wrong");
              return back();
            }
    }


    public function edit(Request $request,Bank $bank){
        $validator = Validator::make($request->all(), [
            'nameA' => ['required'],
            'nameE' => ['required'],
            'acc' => ['required'],
           
      
          ]);
          if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
          }
          if($request['image']){
            $image = request('image')->store('imageBank');
            $bank->update(['image'=>$image]);
          }

          $bank->update([
            'name_ar' => $request['nameA'],
            'name_en' => $request['nameE'],
            'number' => $request['acc'],
          ]);
          Session::flash('success', "Bank Account's info has been updated successfully");
          return back();
    }

    public function create(Request $request){
        $image = request('image')->store('imageBank');
        Bank::create([
          'name_ar' =>$request['nameA'],
          'name_en' =>$request['nameE'],
          'number'=> $request['acc'],
          'image' =>$image
         
        ]);
      
        Session::flash('success', "The bank account is addedd successfuly");
        return back();
        }
    

}
