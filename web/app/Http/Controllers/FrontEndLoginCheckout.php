<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Shipping;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Slider;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

session_start();
class FrontEndLoginCheckout extends Controller
{
    private $customer;
    private $shipping;
    private $slider;
    private $setting;
    private $category;
    private $product;
    public function __construct(Customer $customer, Shipping $shipping, Slider $slider, Setting $setting, Category $category, Product $product)
    {
        $this->customer = $customer;
        $this->shipping = $shipping;
        $this->slider = $slider;
        $this->setting = $setting;
        $this->category = $category;
        $this->product = $product;
    }
    public function logincheckout()
    {
        $settings = $this->setting->all();
        $categories = $this->category->where('parent_id', '0')->orderby('id', 'desc')->get();
        return view('fe.login', compact('settings', 'categories'));
    }
    public function checkout()
    {
        $settings = $this->setting->all();
        $categories = $this->category->where('parent_id', '0')->orderby('id', 'desc')->get();
        return view('fe.checkout', compact('settings', 'categories'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'name'             => 'required',
            'sdt'             => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|min:9',        // just a normal required validation
            'email'            => 'required|email|unique:customers',     // required and must be unique in the ducks table
            'password'         => 'required',
            'password_confirm' => 'required|same:password'
        ]);
        $data = [
            'name' => $request->name,
            'sdt' => $request->sdt,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];
        $this->customer->create($data);
        $id = DB::table('customers')->where('email', $request->email)->value('id');
        session()->put('id', $id);
        $settings = $this->setting->all();
        $categories = $this->category->where('parent_id', '0')->orderby('id', 'desc')->get();
        return view('fe.checkout', compact('settings', 'categories'));
    }
    public function login(Request $request)
    {

        //truyen thông tin header
        $settings = $this->setting->all();
        $categories = $this->category->where('parent_id', '0')->orderby('id', 'desc')->get();
        //end
        $request->validate([

            'email'            => 'required|email',     // required and must be unique in the ducks table
            'password'         => 'required',

        ]);
        $email = $request->email;
        $password = $request->password;
        $passwordReal = DB::table('customers')->where('email', $email)->value('password');
        if ($passwordReal) {
            if (
                Hash::check($request->password, $passwordReal)
            ) {
                $id = DB::table('customers')->where('email', $request->email)->value('id');

                session()->put('id', $id);
                return view('fe.checkout', compact('settings', 'categories'));
            }
        }

        session()->flash('status', 'Tài khoản hoặc mật khẩu không đúng');
        return view('fe.login', compact('settings', 'categories'));
    }
    public function store_order(Request $request)
    {
        $request->validate([

            'email'            => 'required|email',     // required and must be unique in the ducks table
            'sdt'         => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|min:9',

        ]);
        $data = [
            'name' => $request->name,
            'sdt' => $request->sdt,
            'email' => $request->email,
            'diachi' => $request->diachi,
            'ghichu' => $request->ghichu,
            'customer_id' => session()->get('id')

        ];

        $id = DB::table('orders')->insertGetId($data);
        session()->put('order_id', $id);
        return redirect()->route('payment');
    }
    public function order_place(Request $request)
    {
        //truyen thông tin header
        $settings = $this->setting->all();
        $categories = $this->category->where('parent_id', '0')->orderby('id', 'desc')->get();
        //end
        if (isset($request->payment_option)) {
            // insert order
            $order_data = array();
            $order_data['customer_id'] = session()->get('id');
            $order_data['total'] = Cart::total();
            $order_data['status'] = 1;
            $order_data['method'] = $request->payment_option;

            $order_id = session()->get('order_id');
            $orderStore = DB::table('orders')->where('id', $order_id)->update($order_data);

            //insert order_details
            $profit = 0;
            $content = Cart::content();
            foreach ($content as $v_content) {
                $order_d_data = array();
                $order_d_data['order_id'] = $order_id;
                $order_d_data['product_id'] = $v_content->id;
                $order_d_data['product_name'] = $v_content->name;
                $order_d_data['price'] = $v_content->price;
                $order_d_data['product_sales_quantity'] = $v_content->qty;
                $profit = $profit + $v_content->qty * ($v_content->price - $this->product->find($v_content->id)->rootPrice);
                DB::table('order_details')->insertGetId($order_d_data);
            }
            DB::table('orders')->where('id', $order_id)->update(['profit' => $profit]);

            Cart::destroy();

            return view('fe.thanhtoanxong', compact('settings', 'categories'));
        } else {
            return back()->with(['message' => 'Vui lòng chọn phương thức thanh toán', 'alert-type' => 'success']);
        }
    }
    public function logout()
    {
        session()->flush();
        return redirect()->route('fe.home');
    }
    public function payment()
    {
        //truyen thông tin header
        $settings = $this->setting->all();
        $categories = $this->category->where('parent_id', '0')->orderby('id', 'desc')->get();
        //end
        return view('fe.payment', compact('settings', 'categories'));
    }
}
