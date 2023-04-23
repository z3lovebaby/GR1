@extends('layouts.admin')
@section('title')
<title>
   Chi tiết đơn hàng
</title>
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('admins/products/index/index.css')}}">
@endsection

@section('js')
<script src="{{asset('vendors/SweetAlert2/sweetalert2@11.js')}}"></script>
<script src="{{asset('admins/products/index/index.js')}}"></script>
@endsection

@section('content')

<div class="content-wrapper">

    @include('patials.content-header',['name'=>'Order','key'=>'Detail'])



    <div class="content">
        <div class="container-fluid">
            @if (\Session::has('message'))
            <div class="alert alert-success">
              <ul>
                <li>{!! \Session::get('message') !!}</li>
              </ul>
            </div>
            @endif
            <div class="row">
                
                <div class="col-md-12">
                    <h3>Thông tin khách hàng</h3>
                <table class="table">
                   {{-- @foreach($orders as $order) --}}
                        <thead>
                            <tr>
                                
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Email</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Ghi chú</th>
                                

                            </tr>
                        </thead>
                        <tbody>
                           
                            <tr>
                                
                                <td>{{$order->name}}</td>
                                <td>{{$order->sdt}}</td>
                                <td>{{$order->email}}</td>
                                <td>{{$order->diachi}}</td>
                                <td>{{$order->ghichu}}</td>

                               
                                

                            </tr>

                           
                        </tbody>
                    {{-- @endforeach --}}
                    </table>
                   
                    <h3> Liệt kê chi tiết đơn hàng</h3>
                    <table class="table">
                       
                        <thead>
                            <tr>
                                
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Gía</th>
                                <th scope="col">Số lượng</th>
                                

                            </tr>
                        </thead>
                        @foreach($products as $product)
                        <tbody>
                           
                            <tr>
                                
                                <td>{{$product->product_name}}</td>
                                <td>{{number_format($product->price)}}</td>
                                <td>{{$product->product_sales_quantity}}</td>

                               
                                

                            </tr>

                           
                        </tbody>
                        @endforeach
                    </table>
                    <h3>Tổng tiền (cả thuế):</h3>
                    <h2>{{$order->total}} vnđ</h2>
                    

                        <form class="form-group" method="POST" action="{{route('order.update',['id'=>$order->id])}}">
                            @csrf
                            @method('post')
                            <label><h3>Trạng thái đơn hàng</h3></label>
                                <select class="form-control" name="status">
                                    @if ($order->status==3)
                                    <option value="{{$order->status}}"> Giao hàng thành công</option>
                                    @else
                                    <option value="{{$order->status}}">
                                        @if ($order->status==1)
                                   
                                        Đang chờ duyệt
                                    
                                    @elseif ($order->status==2)
                                    
                                        Đang vận chuyển
                                    
                                    @elseif ($order->status==3)
                                    
                                        Giao hàng thành công
                                    
                                    
                                    @endif
                                        </option>
                                    <option value="1">Đang chờ duyệt</option>
                                    <option value="2">Đang giao hàng</option>
                                    <option value="3">Giao hàng thành công</option>
                                    @endif
                                    

                                                
                                </select>
                                <button style="margin-top: 20px" type="submit" class="btn btn-primary">Thay đổi trạng thái</button>
                           
                        </form>
                    
                </div>
            </div>

        </div>
    </div>

</div>

@endsection