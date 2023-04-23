<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use FFI\Exception;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    private $order;
    private $product;
    public function __construct(Order $order, Product $product)
    {
        $this->middleware('auth');
        $this->order = $order;
        $this->product = $product;
    }
    public function index()
    {
        $order_data = DB::table('orders')->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('customers.name', 'orders.id', 'orders.total', 'orders.status', 'orders.method')->get();



        return view('admin.order.index', compact('order_data'));
    }
    public function delete($id)
    {
        try {
            $this->order->find($id)->delete();
            return response()->json([
                'code' => 200,
                'message' => 'success',

            ],200);
        } catch (Exception $exception) {

            Log::error('Message:' . $exception->getMessage() . 'Line' . $exception->getLine());
            return response()->json([
                'code' => 500,
                'message' => 'fail',

            ],500);
        }
    }
    public function detail($id)
    {
        $order = $this->order->find($id);
        $products = DB::table('order_details')->where('order_id', $order->id)->get();
        return view('admin.order.detail', compact('products', 'order'));
    }
    public function update($id, Request $request)
    {
        $order =  $this->order->find($id);
        // giao hàng thành công thì trừ đi số lượng sản phẩm có trong kho
        if ($request->status == 3) {
            $products = DB::table('order_details')->where('order_id', $order->id)->get();
            foreach ($products as $p) {
                $product = $this->product->find($p->product_id);
                $product->update([
                    'number' => $product->number - $p->product_sales_quantity,
                ]);
            }
        }
        $order->update(['status' => $request->status]);
        return back()->with(['message' => 'Cập nhật thông tin đơn hàng thành công', 'alert-type' => 'success']);
    }
}
