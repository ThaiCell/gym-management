@extends('layouts.user')

@section('title', 'Chính sách bảo mật - GYM FIT')

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
        <h1>Chính sách bảo mật</h1>
        <p class="legal-intro">
            GYM FIT tôn trọng quyền riêng tư của bạn. Chính sách này giải thích những thông tin
            chúng tôi có thể thu thập khi bạn sử dụng website và dịch vụ, cũng như cách thông tin
            đó được sử dụng và bảo vệ.
        </p>

        <section class="legal-section">
            <h2>1. Thông tin chúng tôi thu thập</h2>
            <p>
                Tùy cách bạn sử dụng dịch vụ, chúng tôi có thể thu thập thông tin bạn cung cấp
                như họ tên, thông tin liên hệ, thông tin tài khoản, nội dung liên hệ với phòng
                tập và thông tin đăng ký gói tập, lớp học hoặc lịch huấn luyện.
            </p>
            <p>
                Chúng tôi cũng có thể ghi nhận thông tin giao dịch và trạng thái thanh toán cần
                thiết để quản lý đăng ký, cùng một số dữ liệu kỹ thuật như loại trình duyệt,
                thời điểm truy cập và thao tác trên website.
            </p>
        </section>

        <section class="legal-section">
            <h2>2. Mục đích sử dụng thông tin</h2>
            <ul>
                <li>Tạo và quản lý tài khoản, đăng ký gói tập, lớp học và lịch huấn luyện.</li>
                <li>Phản hồi yêu cầu, hỗ trợ khách hàng và gửi thông tin liên quan đến dịch vụ.</li>
                <li>Ghi nhận giao dịch, vận hành website và cải thiện chất lượng dịch vụ.</li>
                <li>Phát hiện, ngăn ngừa hành vi gian lận hoặc xử lý yêu cầu theo quy định pháp luật.</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>3. Chia sẻ và bảo vệ thông tin</h2>
            <p>
                GYM FIT không bán thông tin cá nhân của bạn. Thông tin chỉ được chia sẻ khi cần
                thiết để vận hành dịch vụ (chẳng hạn với đơn vị cung cấp hạ tầng hoặc xử lý giao
                dịch), khi bạn đồng ý, hoặc khi việc cung cấp được yêu cầu theo quy định pháp luật.
            </p>
            <p>
                Chúng tôi áp dụng các biện pháp phù hợp để hạn chế truy cập, sử dụng hoặc tiết lộ
                thông tin trái phép. Tuy nhiên, không phương thức truyền tải hay lưu trữ điện tử
                nào có thể được bảo đảm an toàn tuyệt đối.
            </p>
        </section>

        <section class="legal-section">
            <h2>4. Lưu trữ và quyền của bạn</h2>
            <p>
                Thông tin được lưu trữ trong thời gian cần thiết cho các mục đích nêu trên hoặc
                theo yêu cầu của pháp luật. Bạn có thể liên hệ với chúng tôi để yêu cầu xem,
                cập nhật hoặc xử lý thông tin cá nhân của mình theo quy định hiện hành.
            </p>
        </section>

        <section class="legal-section">
            <h2>5. Cookie và thay đổi chính sách</h2>
            <p>
                Website có thể sử dụng cookie hoặc công nghệ tương tự để duy trì phiên đăng nhập
                và hỗ trợ các chức năng cần thiết. Bạn có thể quản lý cookie trong cài đặt trình
                duyệt; việc tắt cookie có thể ảnh hưởng đến một số chức năng.
            </p>
            <p>
                Chính sách có thể được cập nhật khi dịch vụ hoặc quy định liên quan thay đổi.
                Phiên bản mới sẽ được đăng trên trang này.
            </p>
        </section>

        <section class="legal-section">
            <h2>6. Liên hệ</h2>
            <p>
                Nếu có câu hỏi hoặc yêu cầu liên quan đến quyền riêng tư, vui lòng liên hệ GYM FIT
                qua thông tin tại trang <a href="/contact">Liên hệ</a>.
            </p>
        </section>
    </article>
@endsection
