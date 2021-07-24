<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Financial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FinancialController extends Controller
{
    //

    public function show()
    {
        $financials = Financial::join('drivers', 'financials.drivers_id', '=', 'drivers.id')
            ->select('name', 'financials.*')->paginate(10);
        return view('admin.financial', ['financials' => $financials]);
    }


    public function search(Request $request)
    {

        if ($request->ajax()) {
            $out = '';
            $query = $request->get('query');
            if ($query != '') {
                $financials = Financial::join('drivers', 'financials.drivers_id', '=', 'drivers.id')
                    ->where('drivers.name', 'like', '%' . $query . '%')
                    ->orWhere('financials.created_at', 'like', '%' . $query . '%')
                    ->orWhere('financials.total_benefit', 'like', '%' . $query . '%')
                    ->orWhere('financials.paid_money', 'like', '%' . $query . '%')
                    ->orWhere('financials.updated_at', 'like', '%' . $query . '%')
                    ->paginate(10);
            } else {
                $financials = Financial::join('drivers', 'financials.drivers_id', '=', 'drivers.id')
                    ->select('name', 'financials.*')->paginate(10);
            }
            if ($financials->count() > 0) {
                foreach ($financials as $driver) {
                    $out .= '<tr>
                    <td>' . $driver->name . '</td>
                    <td>' . $driver->total_benefit . '</td>
                    <td>' . $driver->paid_money . '</td>
                    <td>' . $driver->created_at . '</td>
                    <td>' . $driver->updated_at . '</td>
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
                    
                            <form method="POST" action="' . route('modify', $driver) . '" enctype="multipart/form-data">' .
                                      csrf_field()
                                      . '
                              <div class="modal-body">
                                <div class="mb-3">
                                  <label for="total" class="form-label">amount: </label>
                                  <input type="number" name="total" class="form-control" id="total" required>
                                </div>

                                <div class="mb-3">
                                  <label for="paid" class="form-label">amount: </label>
                                  <input type="number" name="paid" class="form-control" id="paid" required>
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
                  $financials->links() .'
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


    public function modify(Request $request, Financial $financial)
    {
        $validator = Validator::make($request->all(), [
            'total' => ['required', 'numeric'],
            'paid'=>['required', 'numeric']
        ]);
        if ($validator->fails()) {
            Session::flash('fail', $validator->errors()->first());
            return back();
        }


        $financial->update(['total_benefit' => $request['total'],'paid_money'=>$request['paid']]);
        Session::flash('success', "The financial is modified successfully");
        return back();
    }
}
