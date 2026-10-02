@extends('layouts.app')

@section('content')

<style>
    .edit-member-page {
        padding: 26px;
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .edit-header h2 {
        margin: 0;
        color: #fff;
        font-size: 25px;
    }

    .edit-header p {
        margin: 6px 0 0;
        color: #888;
        font-size: 14px;
    }

    .back-btn {
        text-decoration: none;
        background: #252525;
        color: #fff;
        padding: 10px 16px;
        border-radius: 7px;
        border: 1px solid #333;
    }

    .back-btn:hover {
        background: #333;
        color: #fff;
    }

    .edit-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
        padding: 24px;
        max-width: 1000px;
    }

    .edit-card h3 {
        margin: 0 0 22px;
        color: #fff;
        font-size: 18px;
        padding-bottom: 15px;
        border-bottom: 1px solid #292929;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        color: #aaa;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select {
        background: #222;
        border: 1px solid #333;
        color: #fff;
        border-radius: 7px;
        padding: 11px 13px;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #ed1c24;
    }

    .form-group select option {
        background: #222;
        color: #fff;
    }

    .form-note {
        color: #777;
        font-size: 12px;
        margin-top: 6px;
    }

    .error-message {
        background: rgba(237, 28, 36, 0.12);
        border: 1px solid rgba(237, 28, 36, 0.4);
        color: #ff5b61;
        padding: 12px 15px;
        border-radius: 7px;
        margin-bottom: 20px;
    }

    .button-area {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #292929;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .cancel-btn {
        text-decoration: none;
        background: #292929;
        color: #fff;
        padding: 11px 20px;
        border-radius: 7px;
    }

    .save-btn {
        background: #ed1c24;
        color: #fff;
        border: none;
        padding: 11px 22px;
        border-radius: 7px;
        cursor: pointer;
        font-weight: 600;
    }

    .save-btn:hover {
        background: #c9141b;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .edit-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="edit-member-page">

    <div class="edit-header">

        <div>
            <h2>Sửa thông tin hội viên</h2>
            <p>
                Chỉnh sửa thông tin hội viên #{{ $member->hoi_vien_id }}
            </p>
        </div>

        <a
            href="/admin/members/{{ $member->hoi_vien_id }}"
            class="back-btn"
        >
            ← Quay lại
        </a>

    </div>

    @if(session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
    @endif

    <div class="edit-card">

        <h3>Thông tin hội viên</h3>

        <form
            action="/admin/members/{{ $member->hoi_vien_id }}/edit"
            method="POST"
        >

            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Họ và tên</label>

                    <input
                        type="text"
                        name="ho_ten"
                        value="{{ old('ho_ten', $member->ho_ten) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $member->email) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>

                    <input
                        type="text"
                        name="so_dien_thoai"
                        value="{{ old('so_dien_thoai', $member->so_dien_thoai) }}"
                    >
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>

                    <input
                        type="date"
                        name="ngay_sinh"
                        value="{{ old('ngay_sinh', $member->ngay_sinh) }}"
                    >
                </div>

                <div class="form-group full">
                    <label>Địa chỉ</label>

                    <input
                        type="text"
                        name="dia_chi"
                        value="{{ old('dia_chi', $member->dia_chi) }}"
                    >
                </div>

                <div class="form-group">
                    <label>Trạng thái tài khoản</label>

                    <select name="trang_thai">

                        <option
                            value="hoat_dong"
                            {{ $member->trang_thai === 'hoat_dong' ? 'selected' : '' }}
                        >
                            Đang hoạt động
                        </option>

                        <option
                            value="bi_khoa"
                            {{ $member->trang_thai === 'bi_khoa' ? 'selected' : '' }}
                        >
                            Bị khóa
                        </option>

                    </select>

                    <span class="form-note">
                        Trạng thái này ảnh hưởng đến quyền đăng nhập của hội viên.
                    </span>
                </div>

            </div>

            <div class="button-area">

                <a
                    href="/admin/members/{{ $member->hoi_vien_id }}"
                    class="cancel-btn"
                >
                    Hủy
                </a>

                <button type="submit" class="save-btn">
                    Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

</div>

@endsection