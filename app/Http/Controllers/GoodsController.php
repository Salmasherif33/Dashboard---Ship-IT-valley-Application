<?php

namespace App\Http\Controllers;

use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoodsController extends Controller
{
    //
    public function show()
    {
        $goods = Good::select('goods_types.*')->paginate(10);
        return view('admin.goods', ['goods' => $goods]);
    }

    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $goods = Good::where('name_ar', 'like', '%' . $query . '%')
                    ->orWhere('name_en', 'like', '%' . $query . '%')
                    ->orWhere('created_at', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $goods = Good::select('goods_types.*')->paginate(10);
            }
            if ($goods->count() > 0) {
                foreach ($goods as $good) {
                    $out .= '<tr>
                    <td>' . $good->name_ar . '</td>
                    <td>' . $good->name_en . '</td>
                    <td>' . $good->created_at . '</td>
                   
                 
                
                    <td>
                     <!-- Button trigger modal -->
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                     data-bs-target="#example' . $good->id . 'Modal">
                        Edit
                      </button>

                    


                      <!-- Modal -->
                      <div class="modal fade" id="example' . $good->id . 'Modal" tabindex="-1" 
                      aria-labelledby="example' . $good->id . 'ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="example' . $good->id . 'ModalLabel">Edit abank account</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                    
                            <form method="POST" action="' . route('editGood', $good) . '" enctype="multipart/form-data">' .
                        csrf_field()
                        . '
                              <div class="modal-body">
                              <div class="mb-3">
                              <label for="nameA" class="form-label">Name in Arabic: </label>
                              <input type="text" name="nameA" class="form-control" id="nameA" value = "' . $good->name_ar . '" required>
                            </div>
                            <div class="mb-3">
                              <label for="nameE" class="form-label">Name in English: </label>
                              <input type="text" name="nameE" class="form-control" id="nameE" value = "' . $good->name_en . '" required>
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
                    $goods->links() . '
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




    public function edit(Request $request, Good $good)
    {


        $good->update([
            'name_ar' => $request['nameA'],
            'name_en' => $request['nameE'],
        ]);
        Session::flash('success', "Good's info has been updated successfully");
        return back();
    }

    public function create(Request $request)
    {
    
        Good::create([
            'name_ar' => $request['nameA'],
            'name_en' => $request['nameE'],

        ]);

        Session::flash('success', "The good is addedd successfuly");
        return back();
    }
}
