<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu lương - {{ $details['employee']->TenNV }} - {{ $details['basic_info']['month'] }}/{{ $details['basic_info']['year'] }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        .company-info, .employee-info {
            margin-bottom: 20px;
        }
        .row {
            display: flex;
            margin-bottom: 15px;
        }
        .col-6 {
            width: 50%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        .footer {
            margin-top: 50px;
            display: flex;
        }
        .footer div {
            width: 50%;
            text-align: center;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                max-width: 100%;
                padding: 0;
            }
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>PHIẾU LƯƠNG</h2>
            <p>Kỳ lương: {{ $details['basic_info']['month'] }}/{{ $details['basic_info']['year'] }}</p>
        </div>
        
        <div class="row">
            <div class="col-6 company-info">
                <h3>CÔNG TY TNHH QLNS</h3>
                <p>123 Đường Lê Lợi, Q.1, TP Hồ Chí Minh<br>
                Điện thoại: (028) 3123-4567</p>
            </div>
            <div class="col-6 employee-info">
                <h3>Thông tin nhân viên</h3>
                <p>
                    Họ và tên: <strong>{{ $details['employee']->TenNV }}</strong><br>
                    Mã NV: {{ $details['employee']->MaNV }}<br>
                    Phòng ban: {{ $details['employee']->department->TenPB ?? 'N/A' }}<br>
                    Chức vụ: {{ $details['employee']->position->TenCV ?? 'N/A' }}
                </p>
            </div>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10%">STT</th>
                    <th style="width: 60%">Khoản mục</th>
                    <th style="width: 30%" class="text-right">Số tiền (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Lương cơ bản</td>
                    <td class="text-right">{{ number_format($details['salary_components']['base_salary'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Lương theo ngày công ({{ $details['salary_components']['working_days'] }}/22)</td>
                    <td class="text-right">{{ number_format($details['salary_components']['salary_by_days'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Phụ cấp</td>
                    <td class="text-right">{{ number_format($details['salary_components']['allowance'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Thưởng</td>
                    <td class="text-right">{{ number_format($details['salary_components']['reward'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Khấu trừ (tạm ứng, phạt)</td>
                    <td class="text-right">{{ number_format($details['salary_components']['advance'] + $details['salary_components']['penalty'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="row">
            <div class="col-6">
                <p><strong>Phương thức thanh toán:</strong><br>
                Chuyển khoản ngân hàng<br>
                Ngày thanh toán: 10 hàng tháng</p>
            </div>
            <div class="col-6">
                <table class="table">
                    <tr>
                        <th style="width: 60%">Tổng thu nhập:</th>
                        <td class="text-right">{{ number_format($details['salary_components']['salary_by_days'] + $details['salary_components']['allowance'] + $details['salary_components']['reward'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Tổng khấu trừ:</th>
                        <td class="text-right">{{ number_format($details['salary_components']['advance'] + $details['salary_components']['penalty'], 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Thực lãnh:</th>
                        <td class="text-right">{{ number_format($details['total'], 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="footer">
            <div>
                <p><strong>Người lập phiếu</strong></p>
                <p style="margin-top: 60px;">Phòng Nhân sự</p>
            </div>
            <div>
                <p><strong>Người nhận</strong></p>
                <p style="margin-top: 60px;">{{ $details['employee']->TenNV }}</p>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>