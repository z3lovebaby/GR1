<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $donhang = DB::table('orders')->count();
        $products = DB::table('products')->get();
        $totalProduct = 0;
        foreach ($products as $p) {
            $totalProduct = $totalProduct + $p->number;
        }
        $category = DB::table('categories')->count();
        $sales =  DB::table('orders')->where('status', 3)->get();
        // dd((int)str_replace(".00", "", str_replace(",", "", $sales[0]->total)));
        $doanhso = 0;
        $loinhuan = 0;
        foreach ($sales as $sale) {
            $doanhso = $doanhso + (int)str_replace(".00", "", str_replace(",", "", $sale->total));
            $loinhuan = $loinhuan + $sale->profit;
        }
        $user = DB::table('users')->count();
        return view('home', compact('donhang', 'totalProduct', 'user', 'category', 'doanhso', 'loinhuan'));
    }

    public function filter_by_date(Request $request)
    {
        $donhang = DB::table('orders')->count();
        $products = DB::table('products')->get();
        $totalProduct = 0;
        foreach ($products as $p) {
            $totalProduct = $totalProduct + $p->number;
        }
        $category = DB::table('categories')->count();
        $user = DB::table('users')->count();

        $this->validate($request, [
            'startDate' => 'before_or_equal:endDate',
            'endDate' => '',

        ]);
        // dd($request);
        $start_date = strtotime($request->startDate);
        $end_date = strtotime($request->endDate);
        $doanhso = 0;
        $loinhuan = 0;
        $sales =  DB::table('orders')->where('status', 3)->oldest()->get();
        // dd($sales);
        foreach ($sales as $sale) {
            $date = strtotime($sale->updated_at);
            $date = date('Y-m-d', $date);
            $date = strtotime($date);
            if ($date >= $start_date && $date <= $end_date) {

                $doanhso = $doanhso + (int)str_replace(".00", "", str_replace(",", "", $sale->total));
                $loinhuan = $loinhuan + $sale->profit;
            }
        }
        View::share('start_date', $request->startDate);
        View::share('end_date', $request->endDate);

        return view('home', compact('doanhso', 'donhang', 'totalProduct', 'user', 'category', 'loinhuan'));
    }
}
