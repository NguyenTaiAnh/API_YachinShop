<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Yachin - Hệ thống bán hàng online</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            crossorigin="anonymous"></script>
    <style>
        .text-muted {
            text-align: center;
            color: #000 !important;
        }
        .btn-error {
            color: #D8000C;
            background-color: #FFBABA;
            border: none;
            height: 40px;
            padding: 0;
            width: 289px;
        }
        .btn-success {
            color: #4F8A10;
            background-color: #DFF2BF;
            border: none;
            height: 40px;
            padding: 0;
            width: 289px;
        }

        .btn-close {
            color: #000;
            background-color: #eee;
            border: none;
            height: 40px;
            padding: 0;
            width: 100px;
        }
        .text-center {
            text-align: center !important;
        }
    </style>
</head>
<body>
<!--Begin display -->
<div class="container" style="margin-top: 30px">
    <div class="header clearfix">
        <h3 class="text-muted">Yachin - Hệ thống bán hàng online</h3>
    </div>
    <div class="table-responsive">
        <div class="form-group" style="text-align: center">
            @if($_GET['vnp_ResponseCode'] == '00')
                <button class="btn-success" disabled>Thanh toán thành công!</button>
            @else
                <button class="btn-error" disabled>Thanh toán thất bại!</button>
            @endif
        </div>
        <div class="form-group text-center">
            <label>Mã đơn hàng:</label>
            <label style="font-weight: 700"><?php echo $_GET['vnp_TxnRef'] ?></label>
        </div>
        <div class="form-group text-center">
            <label>Mã giao dich:</label>
            <label style="font-weight: 700"><?php echo $_GET['vnp_TransactionNo'] ?></label>
        </div>

        <div class="form-group text-center">

            <label>Số tiền:</label>
            <label style="font-weight: 700">{{number_format($_GET['vnp_Amount'], 0, '.', ',')}} đ</label>
        </div>
        <div class="form-group text-center">
            <label>Ngân hàng:</label>
            <label style="font-weight: 700"><?php echo $_GET['vnp_BankCode'] ?></label>
        </div>
        {{--<div class="form-group text-center">--}}
            {{--<label>Thời gian thanh toán:</label>--}}
            {{--<label style="font-weight: 700">{{gmdate("H:i:s d-m-Y", $_GET['vnp_PayDate']/1000)}}</label>--}}
        {{--</div>--}}
        {{--<div class="form-group text-center">--}}
            {{--<button class="btn-close" onclick="closeCurrentTab()">Đóng</button>--}}
        {{--</div>--}}

    </div>
    <p>
        &nbsp;
    </p>
    <footer class="footer" style="position: absolute; bottom: 0; text-align: center; right: 0; left: 0;">
        <p>&copy; kipsoft company</p>
    </footer>
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
    <script type="text/javascript">
        function closeCurrentTab(){
            var conf=confirm("Are you sure, you want to close this tab?");
            if(conf==true){
                window.close();
            }
        }
    </script>
</div>
</body>
</html>
