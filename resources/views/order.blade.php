<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title></title>
    <!--[if (mso 16)]>
    <style type="text/css">
        a {
            text-decoration: none;
        }
    </style>
    <![endif]-->
    <!--[if gte mso 9]>
    <style>sup {
        font-size: 100% !important;
    }</style><![endif]-->
</head>

<body>
<div id=":wg" class="ii gt">
    <div id=":wh" class="a3s aXjCH ">
        <div class="adM">
        </div>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#dcf0f8"
               style="margin:0;padding:0;background-color:#f2f2f2;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
            <tbody>
            <tr>
                <td align="center" valign="top"
                    style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">


                    <table border="0" cellpadding="0" cellspacing="0" width="70%" style="margin-top:15px">
                        <tbody>

                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" style="line-height:0">
                                    <tbody>

                                    </tbody>
                                </table>
                            </td>

                        </tr>
                        <tr style="background:#fff">
                            <td align="left" width="600" height="auto" style="padding:15px">
                                <table>
                                    <tbody>
                                    <tr>

                                        <td>
                                            {{--padding:6px 9px--}}
                                            {{--<h1 style="font-size:14px;font-weight:bold;color:#fff;padding:0 0 5px 0;margin:0; background:#00ced1">--}}
                                            <h1 style="font-size:14px;font-weight:bold;color:#fff;                                            padding:6px 9px
;margin:0; background:#00ced1">

                                                Cảm ơn bạn đã mua hàng của chúng tôi

                                                !</h1>


                                            <p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                Thanh toán được xử lý thành công và đơn hàng của bạn đang được thực hiện
                                            </p>

                                            @foreach($data as $id)
                                                <p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                    Mã đơn hàng của bạn là {{$id->code}}
                                                </p>
                                                <h3 style="font-size:13px;font-weight:bold;color:#02acea;text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd">
                                                    Thông tin đơn hàng #{{$id->code}}
                                                </h3>
                                            @endforeach
                                        </td>


                                    </tr>

                                    <tr>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">

                                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th align="left" width="50%"
                                                        style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold">
                                                        Thông tin thanh toán
                                                    </th>
                                                    <th align="left" width="50%"
                                                        style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold">
                                                        Địa chỉ giao hàng
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data as $addr)
                                                    <tr>
                                                        <td valign="top"
                                                            style="padding:3px 9px 9px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">

                                                            <span style="text-transform:capitalize">{{$addr->address[0]['receiver_name']}}</span><br>

                                                            <a href="mailto:{{$addr->address[0]['receiver_email']}}" target="_blank">{{$addr->address[0]['receiver_email']}}</a><br>

                                                            {{$addr->address[0]['phone']}}

                                                        </td>

                                                        <td valign="top"
                                                            style="padding:3px 9px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                            {{$addr->address[0]['village']}},
                                                            <br>
                                                            {{$addr->address[0]['wards']['name']}},
                                                            <br>{{$addr->address[0]['districts']['name']}}, <br>
                                                            {{$addr->address[0]['provinces']['name']}}<br>
                                                            Số điện thoại: {{$addr->address[0]['phone']}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                {{--<tr>--}}
                                                {{--<td valign="top"--}}
                                                {{--style="padding:7px 9px 0px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444"--}}
                                                {{--colspan="2">--}}
                                                {{--<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:normal">--}}
                                                {{--<br>--}}
                                                {{--<strong>Phí vận chuyển: </strong>0đ (Miễn phí)--}}

                                                {{--<br>--}}
                                                {{--<strong>Phương thức thanh toán: </strong>Thanh toán tiền mặt--}}
                                                {{--khi nhận hàng--}}


                                                {{--</p></td>--}}
                                                {{--</tr>--}}

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h2 style="text-align:left;margin:10px 0;border-bottom:1px solid #ddd;padding-bottom:5px;font-size:13px;color:#02acea">
                                                CHI TIẾT ĐƠN HÀNG</h2>
                                            <table cellspacing="0" cellpadding="0" border="0" width="100%"
                                                   style="background:#f5f5f5">
                                                <thead>
                                                <tr>
                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        STT
                                                    </th>

                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Sản phẩm
                                                    </th>

                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Màu
                                                    </th>

                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Kích thước
                                                    </th>

                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Đơn giá
                                                    </th>

                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Giá khuyến mãi
                                                    </th>

                                                    <th align="left" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Số lượng
                                                    </th>

                                                    <th align="right" bgcolor="#00ced1"
                                                        style="padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                        Tạm tính
                                                    </th>
                                                </tr>
                                                </thead>
                                                <?php $i = 1;?>
                                                @foreach($data[0]->item as $val)
                                                    <tbody bgcolor="#eee"
                                                           style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
                                                    <tr>
                                                        <td align="center" valign="top"
                                                            style="padding:3px 9px">{{$i++}}</td>
                                                        <td align="left" valign="top" style="padding:3px 9px">
                                                            <strong>{{$val->product[0]->name}}</strong>
                                                        </td>
                                                        @if($val->color)
                                                            <td align="center" valign="top"
                                                                style="padding:3px 9px">{{$val->color}}</td>
                                                        @else
                                                            <td align="center" valign="top"
                                                                style="padding:3px 9px">{{$val->color}}</td>
                                                        @endif
                                                        @if($val->size)
                                                            <td align="center" valign="top"
                                                                style="padding:3px 9px">{{$val->size}}</td>
                                                        @else
                                                            <td align="center" valign="top"
                                                                style="padding:3px 9px"></td>
                                                        @endif
                                                        <td align="center" valign="top" style="padding:3px 9px"><span>{{number_format($val->price, 0, '.', ',')}}&nbsp;₫</span>
                                                        </td>
                                                        @if($val->price_sale)
                                                            <td align="center" valign="top" style="padding:3px 9px">
                                                                <span>-{{number_format($val->price_sale, 0, '.', ',')}}&nbsp;₫</span>
                                                            </td>
                                                        @else
                                                            <td align="center" valign="top" style="padding:3px 9px">
                                                                <span>0&nbsp;₫</span>
                                                            </td>
                                                        @endif
                                                        <td align="center" valign="top"
                                                            style="padding:3px 9px">{{$val->amount}}</td>

                                                        <td align="right" valign="top" style="padding:3px 9px">
                                                            <span>{{number_format($val->temp, 0, '.', ',')}}&nbsp;₫</span>


                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                @endforeach

                                                <tfoot
                                                    style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
                                                <tr>
                                                    <td colspan="3"
                                                        style="font-size: 13px;color: #000000;padding-left: 20px;text-align:left;border-right: unset;">
                                                        Tổng phí tạm tính :
                                                    </td>
                                                    {{--<td colspan="4" class="price" style="line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">{{number_format($totaltempcost, 2, '.', ',')}} ₫</td>--}}
                                                    <td colspan="6" class="price"
                                                        style="line-height: 30px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">{{number_format($totaltempcost, 0, '.', ',')}}
                                                        ₫
                                                    </td>

                                                </tr>
                                                @foreach($data as $value)

                                                    <tr>
                                                        <td colspan="3"
                                                            style="font-size: 13px;color: #000000;padding-left: 20px;text-align:left;border-right: unset;">

                                                            Giảm giá :
                                                        </td>
                                                        {{--<td colspan="4" class="price"--}}
                                                        {{--style="line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">{{$totaltempcost*(($value->discount->percent)/100)}}--}}
                                                        {{--₫--}}
                                                        {{--</td>--}}
                                                        <td colspan="6" class="price"
                                                            style="line-height: 30px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">
                                                            {{number_format($discount, 0, '.', ',')}} ₫
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="font-size: 13px;color: #000000;
                                    padding-left: 20px;text-align:left;border-right: unset;">Phí vận chuyển :
                                                        </td>
                                                        <td colspan="6" class="price" style="
                                        line-height: 30px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">{{number_format($value->ship->cost, 0, '.', ',')}}
                                                            ₫
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"
                                                            style="font-size: 13px;color: #000000;padding-left: 20px;text-align:left;border-right: unset;">
                                                            Tổng cộng :
                                                        </td>
                                                        <td colspan="6" class="price"
                                                            style="line-height: 30px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">{{number_format($value->total, 0, '.', ',')}}
                                                            ₫
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tfoot>

                                            </table>


                                            <br>


                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                Nếu sản phẩm có dấu hiệu hư hỏng/ bể vỡ hoặc không đúng với thông tin
                                                trên website, bạn vui lòng liên hệ với chúng tôi trong vòng 48 giờ kể từ thời
                                                điểm nhận hàng để được hỗ trợ.
                                            </p>
                                            <br>
                                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                Qúy khách vui lòng giữ lại hóa đơn để đổi trả hàng hoặc bảo hành khi cần thiết.
                                            </p>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <br>
                                            <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">
                                                <a>Yachin flower</a> cảm ơn quý khách,<br>

                                            </p>
                                            <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;text-align:right">

                                            </p>
                                        </td>
                                    </tr>


                                    </tbody>
                                </table>
                            </td>
                        </tr>


                        </tbody>

                    </table>


                </td>

            </tr>

            <tr>
                <td align="center">
                    <table width="600">
                        <tbody>
                        <tr>
                            <td>
                                <p style="font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:18px;color:#4b8da5;padding:10px 0;margin:0px;font-weight:normal"
                                   align="left">
                                    Quý khách nhận được email này vì đã mua hàng tại yachin flower.<br>
                                    <b>Văn phòng Yachin flower:</b> 68 Thái Phiên, Nha Trang, Khánh Hòa
                                    , Việt Nam
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>


    </div>
</body>

</html>
