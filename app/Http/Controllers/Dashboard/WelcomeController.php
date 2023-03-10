<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Order;
use App\Models\product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    //

    public function index(){
        $categories_count = Category::count();
        $products_count =  product::count();
        $clients_count =  product::count();
        $users_count =    User::whereRoleIs('admin')->count();

        $sales_data = Order::select(
            DB::raw(' YEAR(created_at)  as year '),
            DB::raw(' MONTH(created_at) as month '),
            DB::raw(' SUM(total_price)   as sum ')
        )->groupBy('month')->get();

        return view('dashboard.welcome',compact('categories_count','products_count','clients_count','users_count','sales_data'));
    }//end of index
}
