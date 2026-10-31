@extends('layouts.user')

@section('title', 'Điều khoản sử dụng - GYM FIT')

@section('content')
    <style>
        .legal-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 145px 30px 80px;
        }

        .legal-eyebrow {
            color: #e50914;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .legal-page h1 {
            color: #fff;
            font-size: clamp(32px, 5vw, 48px);
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .legal-intro {
            color: #bbb;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 38px;
        }

        .legal-section {
            border-top: 1px solid #252a31;
            padding: 25px 0;
        }

        .legal-section h2 {
            color: #fff;
            font-size: 21px;
            margin-bottom: 12px;
        }

        .legal-section p,
        .legal-section li {
            color: #b6bbc2;
            line-height: 1.8;
        }

        .legal-section p + p {
            margin-top: 12px;
        }

        .legal-section ul {
            padding-left: 22px;
        }

        .legal-section li + li {
            margin-top: 8px;
        }

        .legal-section a {
            color: #ff4048;
        }

        @media (max-width: 600px) {
            .legal-page {
                padding: 155px 22px 55px;
            }
        }
    </style>

    <article class="legal-page">
        <p class="legal-eyebrow">GYM FIT · THÔNG TIN</p>
        <h1>Điều khoản sử dụng</h1>
        <p class="legal-intro">
            Khi truy cập website hoặc sử dụng dịch vụ của GYM FIT, bạn đồng ý tuân thủ các điều
            khoản dưới đây. Vui lòng đọc kỹ trước khi tạo tài khoản hoặc đăng ký dịch vụ.
        </p>

        <section class="legal-section">
            <h2>1. Tài khoản và trách nhiệm của người dùng</h2>
            <ul>
                <li>Cung cấp thông tin chính xác, cập nhật khi thông tin thay đổi.</li>
                <li>Bảo mật thông tin đăng nhập và chịu trách nhiệm với hoạt động qua tài khoản của mình.</li>
                <li>Không sử dụng website để gian lận, gây gián đoạn hệ thống hoặc xâm phạm quyền của người khác.</li>
                <li>Thông báo cho GYM FIT nếu phát hiện tài khoản bị truy cập hoặc sử dụng trái phép.</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>2. Gói tập, lớp học và thanh toán</h2>
            <p>
                Thông tin về nội dung, thời hạn và giá của gói tập hoặc dịch vụ được thể hiện trên
                website tại thời điểm đăng ký. Việc đăng ký được ghi nhận theo thông tin và trạng
                thái hiển thị trong tài khoản hoặc xác nhận giao dịch.
            </p>
            <p>
                Các yêu cầu thay đổi, hủy đăng ký hoặc hoàn tiền được xử lý theo điều kiện áp dụng
                cho dịch vụ đã đăng ký và quy định hiện hành. Vui lòng liên hệ GYM FIT để được
                hướng dẫn về trường hợp cụ thể.
            </p>
        </section>

        <section class="legal-section">
            <h2>3. An toàn khi tập luyện</h2>
            <p>
                Việc tập luyện thể chất có thể có rủi ro. Bạn nên lựa chọn cường độ phù hợp với
                thể trạng, làm theo hướng dẫn của huấn luyện viên và thông báo cho nhân viên về
                các lưu ý sức khỏe có liên quan. Thông tin trên website không thay thế tư vấn,
                chẩn đoán hoặc điều trị của chuyên gia y tế.
            </p>
        </section>

        <section class="legal-section">
            <h2>4. Nội dung và quyền sở hữu</h2>
            <p>
                Nội dung trên website được cung cấp cho mục đích tham khảo và sử dụng dịch vụ
                GYM FIT. Không được sao chép, chỉnh sửa, phân phối hoặc khai thác nội dung khi
                chưa có sự cho phép của chủ sở hữu, trừ trường hợp pháp luật cho phép.
            </p>
        </section>

        <section class="legal-section">
            <h2>5. Tính khả dụng và thay đổi dịch vụ</h2>
            <p>
                GYM FIT nỗ lực duy trì website chính xác và hoạt động ổn định, nhưng một số chức
                năng có thể tạm thời gián đoạn để bảo trì hoặc do yếu tố ngoài khả năng kiểm soát.
                Thông tin dịch vụ có thể được cập nhật; các thay đổi áp dụng cho đăng ký sẽ được
                thông báo hoặc thể hiện tại thời điểm đăng ký.
            </p>
        </section>

        <section class="legal-section">
            <h2>6. Cập nhật điều khoản và liên hệ</h2>
            <p>
                Điều khoản có thể được sửa đổi khi cần thiết. Phiên bản hiện hành được đăng trên
                trang này. Nếu có câu hỏi về việc sử dụng dịch vụ, vui lòng liên hệ qua trang
                <a href="/contact">Liên hệ</a>.
            </p>
        </section>
    </article>
@endsection
