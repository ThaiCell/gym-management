<?php

namespace App\Support;

class StatusLabel
{
    public static function format($status): string
    {
        $status = trim((string) $status);

        $key = mb_strtolower($status, 'UTF-8');
        $key = str_replace('_', ' ', $key);
        $key = preg_replace('/\s+/', ' ', $key);

        $labels = [
            'hoạt động' => 'Hoạt động',
            'hoat dong' => 'Hoạt động',

            'đang hoạt động' => 'Đang hoạt động',
            'dang hoat dong' => 'Đang hoạt động',

            'bị khóa' => 'Bị khóa',
            'bi khoa' => 'Bị khóa',

            'chờ thanh toán' => 'Chờ thanh toán',
            'cho thanh toan' => 'Chờ thanh toán',

            'đã thanh toán' => 'Đã thanh toán',
            'da thanh toan' => 'Đã thanh toán',

            'đã hủy' => 'Đã hủy',
            'da huy' => 'Đã hủy',

            'đã hết hạn' => 'Đã hết hạn',
            'da het han' => 'Đã hết hạn',

            'chưa xử lý' => 'Chưa xử lý',
            'chua xu ly' => 'Chưa xử lý',

            'đang xử lý' => 'Đang xử lý',
            'dang xu ly' => 'Đang xử lý',

            'đã xử lý' => 'Đã xử lý',
            'da xu ly' => 'Đã xử lý',

            'đã duyệt' => 'Đã duyệt',
            'da duyet' => 'Đã duyệt',

            'đã từ chối' => 'Đã từ chối',
            'da tu choi' => 'Đã từ chối',

            'chờ xác nhận' => 'Chờ xác nhận',
            'cho xac nhan' => 'Chờ xác nhận',

            'đã xác nhận' => 'Đã xác nhận',
            'da xac nhan' => 'Đã xác nhận',
        ];

        return $labels[$key] ?? $status;
    }
}
