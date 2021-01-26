@csrf
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multikart | Email template </title>
    <style>
        #css {
            padding: 50px;
            -webkit-box-shadow: -2px 3px 19px 0px rgba(144, 240, 224, 1);
            -moz-box-shadow: -2px 3px 19px 0px rgba(144, 240, 224, 1);
            box-shadow: -2px 3px 19px 0px rgba(144, 240, 224, 1);
        }
    </style>

</head>
</html>
<div id="css">
    <div class="container">
        <div class="row" style="padding-left: 120px; padding-right: 120px; padding-bottom: 40px">
            <h1 style="color: white;background: #78d1da7a;text-align: center;"><a
                    style="text-decoration: none;color: white">YACHIN.CF</a></h1>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3 style="padding-left: 100px">☆ {{$user}} vừa đăng nhập</h3>
                <h3 style="padding-left: 100px">☆ Vào lúc {{\Carbon\Carbon::now()}}</h3>
            </div>
            <div class="col-md-6">
                <img class="adapt-img"
                     src="\assets\images\icon-2.png"
                     alt=""
                     style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                     width="170">
            </div>
        </div>
        {{--<div class="row text-center" style=" display: flex; justify-content: center">--}}
    </div>
    <table cellpadding="0" cellspacing="0" class="es-footer" align="center"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;">
        <tr style="border-collapse:collapse;">
            <td align="center" style="padding:0;Margin:0;">
                <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" width="600"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
                    <tr style="border-collapse:collapse;">
                        <td align="left"
                            style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;">
                            <table cellpadding="0" cellspacing="0" width="100%"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tr style="border-collapse:collapse;">
                                    <td width="560" align="center" valign="top" style="padding:0;Margin:0;">
                                        <table cellpadding="0" cellspacing="0" width="100%"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:20px;Margin:0;">
                                                    <table border="0" width="75%" height="100%" cellpadding="0"
                                                           cellspacing="0"
                                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td style="padding:0;Margin:0px 0px 0px 0px;border-bottom:1px solid #CCCCCC;background:none;height:1px;width:100%;margin:0px;"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="center"
                                                    style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;"><h4
                                                        style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;">
                                                        ❄K͟͟I͟͟P͟͟S͟͟O͟͟F͟͟T͟͟❄</h4></td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="center"
                                                    style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;"><h4
                                                        style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;">
                                                        2019 - 20 Copy Right by me</h4></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

