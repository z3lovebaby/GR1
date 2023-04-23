@extends('fe.layouts.index')
@section('title')
<title>
  Trang chủ
</title>
@endsection
@section('css')
{{-- <script src="{{asset('/front-end/js/gmaps.js')}}"></script> --}}
<style>
  td{
    text-align: left
  }
</style>
@endsection



@section('content-front-end')

<body>


  <div id="contact-page" class="container">

    <div class="row">

      <div class="col-sm-12">
        <div class="contact-info">
          <h2 class="title text-center">Thông tin cá nhân</h2>
          @if (\Session::has('message'))
          <div class="alert alert-success">
            <ul>
              <li>{!! \Session::get('message') !!}</li>
            </ul>
          </div>
          @endif


          <form method="post" action="{{route('customer.edit',['id'=> session()->get('id')])}}">
            @csrf
            @method('post')
            <div class="form-group">
              <label for="exampleInputEmail1">Họ và tên</label>
              <input name="name" type="text" value="{{$customer->name}}" class="form-control @error('name') is-invalid @enderror" 
                placeholder="Nhập họ và tên">
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Email</label>
              <input name="email" value="{{$customer->email}}" type="text" class="form-control @error('email') is-invalid @enderror" 
                placeholder="Nhập email">
                @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Số điện thoại</label>
              <input name="sdt" type="text" value="{{$customer->sdt}}" class="form-control  @error('sdt') is-invalid @enderror" id="exampleInputEmail1"
                placeholder="Nhập số điện thoại">
                @error('sdt')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control  @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Password">
              @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Confirm Password</label>
              <input type="password" name="password_confirm" class="form-control @error('password_confirm') is-invalid @enderror" id="exampleInputPassword1"
                placeholder="password_confirm">
                @error('password_confirm')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            </div>

            <button type="submit" class="btn btn-primary"> Thay đổi thông tin</button>
          </form>
          <div class="social-networks">
            <h2 class="title text-center">Lịch sử đơn hàng</h2>
            <div class="row">
              <div class="col-md-12">
                  <table class="table">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Tên khách hàng</th>
                              <th scope="col">Tổng tiền</th>
                              <th scope="col">Phương thức thanh toán</th>
                              <th scope="col">Tình trạng</th>
                              <th scope="col">Lựa chọn</th>

                          </tr>
                      </thead>
                      <tbody>
                        <?php
                        $stt=0;
                          ?>
                         @foreach($order_data as $a)
                         <?php
                         $stt++;
                           ?>
                          <tr>
                            
                              <th scope="row">{{$stt}}</th>
                              <td>{{$a->name}}</td>
                              <td>{{$a->total}}</td>
                              @if ($a->method==2)
                                <td>Thanh toán khi nhận hàng</td>
                                @else
                                <td>Internet Banking với mã chuyển khoản: {{$a->method}}</td>
                              @endif
                             

                              @if ($a->status==1)
                              <td>
                                  Đang chờ duyệt
                              </td>
                              @elseif ($a->status==2)
                              <td>
                                  Đang vận chuyển
                              </td>
                              @elseif ($a->status==3)
                              <td>
                                  Giao hàng thành công
                              </td>
                              
                              @endif
                              <td>
                                              <a href="{{route('show_order_detail_customer',['id'=>$a->id])}}" class="btn btn-info btn-circle btn-sm ">
                                                 <span class="label label-info">Chi tiết</span>
                                               </a>

                                               {{-- <a href="{{route('order.confirm',['id'=>$a->id])}}" class="btn btn-success btn-circle btn-sm ">
                                                  <i class="fas fa-check"></i>
                                               </a>
                                 
                                              <a data-url="{{route('order.delete',['id'=>$a->id])}}" class="btn btn-danger btn-circle btn-sm action_delete">
                                                  <i class="fas fa-trash"></i>
                                              </a> --}}
                                          </td>

                          </tr>

                          @endforeach
                      </tbody>

                  </table>
                  
              </div>

          </div>
            <ul>
              <li>
                <a href="#"><i class="fa fa-facebook"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-twitter"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-google-plus"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-youtube"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!--/#contact-page-->

  @endsection