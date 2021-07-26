<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PriceController extends Controller
{
    public function show()
    {
        $prices = Price::select('price_list.*')->paginate(10);
        $trucks = DB::table('trucks_types')->select('trucks_types.*')->get();
        return view('admin.prices', ['prices' => $prices,'trucks'=>$trucks]);
    }

    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $prices = Price::join('trucks_types','price_list.trucks_types_id','=','trucks_types.id')
                ->where('trucks_types.name_en', 'like', '%' . $query . '%')
                    ->orWhere('price_list.category', 'like', '%' . $query . '%')
                    ->orWhere('price_list.price', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $prices = Price::select('price_list.*')->paginate(10);
            }
            if ($prices->count() > 0) {
                $trucks = DB::table('trucks_types')->select('trucks_types.*')->get();

                foreach ($prices as $price) {
                    $out .= '<tr>
                    <td>' . $price->category . '</td>
                    <td>' . $price->price . '</td>
                    <td>' . $price->name_en . '</td>
                   
                 
                
                    <td>
                     <!-- Button trigger modal -->
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                     data-bs-target="#example' . $price->id . 'Modal">
                        Edit
                      </button>

                    


                      <!-- Modal -->
                      <div class="modal fade" id="example' . $price->id . 'Modal" tabindex="-1" 
                      aria-labelledby="example' . $price->id . 'ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="example' . $price->id . 'ModalLabel">Edit a price</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                    
                            <form method="POST" action="' . route('editPrice', $price) . '" enctype="multipart/form-data">' .
                        csrf_field()
                        . '
                              <div class="modal-body">
                              <div class="mb-3">
                              <label for="category" class="form-label">Distance: </label>
                              <input type="number" name="category" class="form-control" id="category" 
                              value = "' . $price->category . '" required>
                            </div>
                            <div class="mb-3">
                              <label for="price" class="form-label">Price: </label>
                              <input type="number" name="price" class="form-control" id="price" value = "' . $price->price . '" required>
                            </div>
                            <div class="mb-3">
                            <label for="trucks_Types_id" class="form-label">Price: </label>
                            <select name="trucks_types_id" id="trucks_types_id" required>';

                            foreach ($trucks as $truck){
                                if($price->trucks_types_id == $truck->id){
                                    $out .= '<option selected  value="'.$truck->id.'">'.$truck->name_en.'</option>
                                    ';
                                }
                                else{
                                        $out .= '<option value="'.$truck->id.'">'.$truck->name_en.'
                                        </option>
                                ';
                                }
                            }
                 
                            $out .='
                        </select>
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
                    $prices->links() . '
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




    public function edit(Request $request, Price $price)
    {


        $price->update([
            'category' => $request['category'],
            'price' => $request['price'],
            'trucks_types_id' => $request['trucks_types_id']
        ]);
        Session::flash('success', "Price's info has been updated successfully");
        return back();
    }

    public function create(Request $request)
    {
    
        Price::create([
            'category' => $request['category'],
            'price' => $request['price'],
            'trucks_types_id' => $request['trucks_types_id']

        ]);

        Session::flash('success', "The price is addedd successfuly");
        return back();
    }
}
