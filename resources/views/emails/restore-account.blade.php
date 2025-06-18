<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo khôi phục tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: #f4f7f9;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .header {
            background-color: #28a745; /* Màu xanh lá cho thông báo thành công */
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 16px;
        }

        .content {
            padding: 30px;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 15px;
        }

        .account-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .account-info li {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .text-white {
            color: #fff;
        }

        .text-green {
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <h2>Hệ thống Quản lý Sinh hoạt Lớp</h2>
        <p>Trường Đại học Thủy Lợi</p>
    </div>
    <div class="content">
        <p>Xin chào,</p>
        <p>Chúng tôi xin thông báo rằng tài khoản của bạn với email <strong>{{ $email }}</strong> đã được khôi phục thành công.</p>
        <p>Bạn có thể đăng nhập lại vào hệ thống bằng thông tin đăng nhập hiện tại của mình. Nếu bạn gặp bất kỳ vấn đề nào khi đăng nhập, vui lòng liên hệ với chúng tôi qua email <a href="mailto:p7@tlu.edu.vn">p7@tlu.edu.vn</a> hoặc số điện thoại <strong>024.3.5639577</strong>.</p>
        <p class="text-green">Cảm ơn bạn đã sử dụng Hệ thống Quản lý Sinh hoạt Lớp!</p>
        <p>
            <a href="{{ route('login') }}" class="btn-primary text-white">Đăng nhập ngay</a>
        </p>
        <p>Trân trọng,<br>Hệ thống Quản lý Sinh hoạt Lớp</p>
    </div>
    <div class="footer">
        <p>© 2025 Hệ thống Quản lý Sinh hoạt Lớp - Trường Đại học Thủy Lợi. Mọi quyền được bảo lưu.</p>
    </div>
</div>
</body>
</html>
