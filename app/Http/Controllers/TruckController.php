<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TruckController extends Controller
{
    //
    public function show()
    {
        $trucks = Truck::select('trucks_types.*')->paginate(10);
        return view('admin.trucks', ['trucks' => $trucks]);
    }

    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $trucks = Truck::where('name_ar', 'like', '%' . $query . '%')
                    ->orWhere('name_en', 'like', '%' . $query . '%')
                    ->orWhere('is_Active', 'like', '%' . $query . '%')
                    ->orWhere('max_weight', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $trucks = Truck::select('trucks_types.*')->paginate(10);
            }
            if ($trucks->count() > 0) {
                foreach ($trucks as $truck) {
                    $out .= '<tr>
                    <td>' . $truck->name_ar . '</td>
                    <td>' . $truck->name_en . '</td>
                    <td>
                    <form method="POST" action="' . route('activateTruck', $truck->id) . '">' .
                        csrf_field() . '' .
                        method_field("PATCH") . '
                      <button type="submit" class="btn btn-primary" style="margin-top: 10px;">';

                    if ($truck->is_active)
                        $out .= 'Activated';
                    else
                        $out .= 'Not Activated';

                    $out .= '
                      </button>
    
    
                    </form>
                  </td>
                    <td>' . $truck->max_weight . '</td>
                
                    <td>
                     <!-- Button trigger modal -->
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                     data-bs-target="#example' . $truck->id . 'Modal">
                        Edit
                      </button>

                      <form method = "POST" style = "display:inline;" action = "' . route('deleteTruck', $truck->id) . '">
                      ' . csrf_field() .

                        '
                      <button type="submit" class="btn btn-danger">
                        Delete
                       </button>
                      </form>


                      <!-- Modal -->
                      <div class="modal fade" id="example' . $truck->id . 'Modal" tabindex="-1" 
                      aria-labelledby="example' . $truck->id . 'ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="example' . $truck->id . 'ModalLabel">Edit abank account</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                    
                            <form method="POST" action="' . route('editTruck', $truck) . '" enctype="multipart/form-data">' .
                        csrf_field()
                        . '
                              <div class="modal-body">
                              <div class="mb-3">
                              <label for="nameA" class="form-label">Name in Arabic: </label>
                              <input type="text" name="nameA" class="form-control" id="nameA" value = "' . $truck->name_ar . '" required>
                            </div>
                            <div class="mb-3">
                              <label for="nameE" class="form-label">Name in English: </label>
                              <input type="text" name="nameE" class="form-control" id="nameE" value = "' . $truck->name_en . '" required>
                            </div>
                            
                            <div class="mb-3">
                              <label for="status" class="form-label">Status: </label>
                              <select name = "status" id = "role" required>
                                    <option value = "1">Activated</option>
                                    <option value = "0">Not Activated</option>
                                
                
                                    </select>
                            </div>
                
                            <div class="mb-3">
                              <label for="max" class="form-label">Max weight: </label>
                              <input type="number" name="max" class="form-control" id="max" value = "' . $truck->max_weight . '" required>
                            </div>  
                
                
                            <div class="mb-3">
                              <label for="desciptions_ar" class="form-label">Description in Arabic: </label>
                              <input type="text" name="desciptions_ar" class="form-control" id="desciptions_ar" value = "' . $truck->descriptions_ar . '" required>
                            </div> 
                
                            <div class="mb-3">
                              <label for="desciptions_en" class="form-label">Description in English: </label>
                              <input type="text" name="desciptions_en" class="form-control" id="desciptions_en" value = "' . $truck->descriptions_en . '" required>
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
                <td colspan="3" align="center">' .
                    $trucks->links() . '
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

    public function delete($id)
    {
        try {

            $truck = Truck::findOrFail($id);

            $truck->delete();
            Session::flash('success', " this truck has been deleted successfully");
            return back();
        } catch (Throwable $e) {
            Session::flash('fail', "Something went wrong");
            return back();
        }
    }


    public function edit(Request $request, Truck $truck)
    {


        $truck->update([
            'name_ar' => $request['nameA'],
            'name_en' => $request['nameE'],
            'is_active' => $request['status'],
            'max_weight' => $request['max'],
            'descriptions_ar'=>$request['desciptions_ar'],
            'descriptions_en'=>$request['desciptions_en']
        ]);
        Session::flash('success', "Bank Account's info has been updated successfully");
        return back();
    }

    public function create(Request $request)
    {
        $image = request('image')->store('imageTruck');
        Truck::create([
            'name_ar' => $request['nameA'],
            'name_en' => $request['nameE'],
            'max_weight' => $request['max'],
            'image' => $image,
            'is_active' => $request['status'],
            'descriptions_ar' => $request['desciptions_ar'],
            'descriptions_en' => $request['desciptions_en']

        ]);

        Session::flash('success', "The truck is addedd successfuly");
        return back();
    }

    public function activate(Truck $truck)
    {
        //echo $driver;
        $d = Truck::where('id', '=', $truck->id)->first();
        $d->update(['is_active' => !($d->is_active)]);
        return redirect()->back();
    }

}
