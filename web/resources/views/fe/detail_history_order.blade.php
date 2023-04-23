@extends('fe.layouts.index')
@section('title')
<title>
	Thông tin chi tiết đơn hàng
</title>
@endsection
@section('css')
<style>
	.abc img {
		-webkit-transform: scale(1);
		transform: scale(1);
		-webkit-transition: .3s ease-in-out;
		transition: .3s ease-in-out;
	}

	.abc:hover img {
		-webkit-transform: scale(1.3);
		transform: scale(1.3);
	}
</style>
@endsection


@section('content-front-end')


<section id="cart_items">
	<div class="container">
		<div class="breadcrumbs">
			<ol class="breadcrumb">
				<li><a href="{{route('fe.home')}}">Trang chủ</a></li>
				<li class="active">Chi tiết đơn hàng</li>
			</ol>
		</div>
		<div class="table-responsive cart_info">

			<table class="table table-condensed">
				<thead>
					<tr class="cart_menu">
						<td class="image">Hình ảnh</td>
						<td class="description">Mô tả</td>
						<td class="price">Gía</td>
						<td class="quantity">Số lượng</td>
						<td class="total">Tổng tiền</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					@foreach($products as $product)
					<tr>
						<td class="cart_product">
							<a href=""><img src="{{$product->feature_image_path}}" width="70" alt=""></a>
						</td>
						<td class="cart_description">
							<h4><a href="">{{$product->product_name}}</a></h4>
							<p>ID: {{$product->product_id}}</p>
						</td>
						<td class="cart_price">
							<p>{{number_format($product->price)}} VNĐ</p>
						</td>
						<td class="cart_quantity">
							<p>{{number_format($product->product_sales_quantity)}} </p>

						</td>
						<td class="cart_total">
							<p class="cart_total_price">
								<?php
									$tong=$product->price*$product->product_sales_quantity;
									echo number_format($tong).' '.'VNĐ';
									?>
							</p>
						</td>

					</tr>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</section>
<!--/#cart_items-->

<section id="do_action">
	<div class="container">
		<div class="heading">
			<h3>Hóa đơn</h3>
			<p>Thông tin chi tiết hóa đơn</p>
		</div>
		<div class="row">

		</div>
		<div class="col-sm-6">
			<div class="total_area">
				<ul>
					<li>Tên : {{$order->name}}</li>
					<li>Số điện thoại: {{$order->sdt}}</li>

					<li>Email : {{$order->email}}</li>

					<li>Địa chỉ : {{$order->diachi}}</li>
					<li>Ghi chú : {{$order->ghichu}}</li>



					<li>Phí vận chuyển <span>Free</span></li>
					<li>Thành tiền : {{$order->total}} VNĐ</li>
					<li>Phương thức thanh toán :@if ($order->method==2)
						Thanh toán khi nhận hàng
						@else
						Internet Banking với mã: {{$order->method}}
						@endif</li>
					<li>Tình trạng : @if ($order->status==1)

						Đang chờ duyệt

						@elseif ($order->status==2)

						Đang vận chuyển

						@elseif ($order->status==3)

						Giao hàng thành công


						@endif</li>


				</ul>
				<!-- <a class="btn btn-default update" href="">Update</a> -->


			</div>
		</div>
	</div>
	</div>
</section>


@endsection