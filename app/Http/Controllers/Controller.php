<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bill;
use App\Models\Contact;
use App\Models\Discount;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $admins = Admin::where('type', '=', 'admin')->get()->count();
        $companies = Admin::where('type', '=', 'company')->get()->count();
        $drivers = Driver::where('is_verified', '=', 1)->get()->count();
        $users = User::where('is_verified', '=', 1)->get()->count();
        $codes = Discount::where('is_active', '=', 1)->get()->count();
        $trucks = DB::table('trucks_types')->where('is_active', '=', 1)->get()->count();
        $goods = DB::table('goods_types')->get()->count();
        $bills = Bill::where('status', '=', 'paid')->get()->sum('cost');
        $msgDrivers = Contact::where('status','=','open')->where('drivers_id','!=','null')->get()->count();
        $msgUsers = Contact::where('status','=','open')->where('users_id','!=','null')->get()->count();
        $openBills = Bill::where('status', '=', 'waiting')->get()->count();
        $paidBills = Bill::where('status', '=', 'paid')->get()->count();

        return view('admin.index', [
            'admins' => $admins,
            'companies' => $companies,
            'drivers' => $drivers,
            'users' => $users,
            'codes' => $codes,
            'trucks' => $trucks,
            'goods' => $goods,
            'bills' => $bills,
            'msgDrivers'=>$msgDrivers,
            'msgUsers' =>$msgUsers,
            'openBills'=>$openBills,
            'paidBills' =>$paidBills
        ]);
    }
}
