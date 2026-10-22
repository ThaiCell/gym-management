@extends('layouts.dashboard')
@section('title', 'Thanh toán thành công - GYM MANAGEMENT')
@section('content')
<style>.pay-success-wrap{max-width:700px;margin:50px auto;text-align:center;color:#fff;background:#111;border:1px solid #292929;border-radius:12px;padding:42px 24px}.pay-success-icon{width:76px;height:76px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;border-radius:50%;background:#12351e;color:#5be28b;font-size:38px}.pay-success-wrap h1{font-size:30px;font-weight:800}.pay-success-wrap p{color:#aaa;line-height:1.8}.pay-success-btn{display:inline-block;margin-top:16px;background:#e50914;color:white;text-decoration:none;font-weight:800;padding:13px 22px;border-radius:6px}</style>
<div class="pay-success-wrap"><div class="pay-success-icon">✓</div><h1>Cảm ơn bạn đã thanh toán!</h1><p>Hệ thống đã ghi nhận thanh toán mô phỏng của bạn.<br>Số hóa đơn đã thanh toán: <strong>{{ $soHoaDon }}</strong><br>Tổng tiền: <strong>{{ number_format($tongTien,0,',','.') }} ₫</strong><br>Phương thức: <strong>{{ $phuongThuc }}</strong></p><a class="pay-success-btn" href="{{ url('/user/dashboard') }}">Quay lại Dashboard</a></div>
@endsection
