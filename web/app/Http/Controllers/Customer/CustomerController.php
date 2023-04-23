<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    private $order;
    private $product;
    public function __construct(Order $order, Product $product)
    {
        $this->order = $order;
        $this->product = $product;
    }
    public function showInformation($id)
    {
        $idCustomer = $id;
        $customer = (DB::table('customers')->where('id', '=', $idCustomer)->get())[0];

        $order_data = DB::table('orders')->join('customers', 'orders.customer_id', '=', 'customers.id')->where('orders.customer_id', '=', $idCustomer)
            ->select('customers.name', 'orders.id', 'orders.total', 'orders.status', 'orders.method')->get();


        return view('fe.information_customer', compact('customer', 'order_data'));
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'name'             => 'required',
            'sdt'             => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|min:9',        // just a normal required validation
            'email'            =>  [
                'required',  'email', 'max:255',
                Rule::unique('customers')->ignore($id),
            ], // required and must be unique in the ducks table
            'password_confirm' => 'same:password'
        ]);
        $idCustomer = $id;
        if ($request->password) {
            $newPassword = bcrypt($request->password);
            $data['password'] = $newPassword;
        }
        $data = [
            'name' => $request->name,
            'sdt' => $request->sdt,
            'email' => $request->email,
        ];
        $customer = DB::table('customers')->where('id', '=', $idCustomer)->update($data);
        return back()->with(['message' => 'Cập nhật thông tin cá nhân thành công', 'alert-type' => 'success']);
    }

    public function showOrderDetailCustomer($id)
    {
        $order = $this->order->find($id);
        $products = DB::table('order_details')->where('order_id', $order->id)->get();
        foreach ($products as $product) {
            $product->feature_image_path = $this->product->find($product->product_id)->feature_image_path;
        }
        return view('fe.detail_history_order', compact('products', 'order'));
    }
}
