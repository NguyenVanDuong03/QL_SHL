<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: #f4f7f9;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }

        .text-white {
            color: #fff;
        }

        .btn-verify {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <h2>Hệ thống Quản lý Sinh hoạt Lớp</h2>
        <p class="mb-0">Trường Đại học Thủy Lợi</p>
    </div>
    <div class="content">
        <p>Xin chào,</p>
        <p>Vui lòng nhấp vào nút dưới đây để xác minh email của bạn:</p>
        <a href="{{ $url }}">
            <button class="btn btn-primary btn-lg text-white btn-verify">Xác minh Email</button>
        </a>
        <p>Nếu bạn không tạo tài khoản, vui lòng bỏ qua email này.</p>
        <p>Trân trọng,<br>Hệ thống Quản lý Sinh hoạt Lớp</p>
    </div>
    <div class="footer">
        <p>&copy; 2025 Hệ thống Quản lý Sinh hoạt Lớp - Trường Đại học Thủy Lợi. Mọi quyền được bảo lưu.</p>
    </div>
</div>
</body>
</html>
