<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>Zamar Music Academy </title>


</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0"
       style="background:#dde5c0; padding:30px 30px; color:#000; border-radius:25px;  margin-top:20px; max-width: 100%; font-family:Arial, Helvetica, sans-serif; font-size:16px; ">

    <tr>
        <td>
            <center><h2>Donation Receipt </h2></center>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><h4>Official donation receipt for income tax purposes</h4></td>
                    <td style="padding:10px 20px; border-radius:15px; background:#fff;"> Receipt # {{$data['id']}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="60%"><img src="https://www.zamarmusicacademy.ca/files/logo.png"
                                         style="float:left; margin-right:15px;"></td>
                    <td width="40%"><p>Zamar Music Academy<br>
                            8920 Highway 50, Brampton, Ontario, L6P 3A3<br>
                            Charitable Registration No. 749998290 RR 0001 </p></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>

                    <td width="14%">Receipt issued</td>
                    <td width="36%">
                        <div
                            style="width:100%; display:inline-block; border:none; background:none; border-bottom:1px solid #000; border-radius:0">
                            {{$data['created_at']}}
                        </div>
                    </td>
                    <td width="16%">Location issued</td>
                    <td width="34%"
                        style="width:100%; display:inline-block; border:none; background:none; border-bottom:1px solid #000; border-radius:0">
                        {{$data['location']}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="14%">Donated By</td>
                    <td width="86%"
                        style="width:100%; display:inline-block; border:none; background:none; border-bottom:1px solid #000; border-radius:0">
                        {{$data['name']}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>(First and Last name, and initial)</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="14%">Address</td>
                    <td width="86%"
                        style="width:100%; display:inline-block; border:none; background:none; border-bottom:1px solid #000; border-radius:0">
                        {{$data['address']}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="14%">Donation Received</td>
                    <td width="36%"
                        style="width:100%; display:inline-block; border:none; background:none; border-bottom:1px solid #000; border-radius:0">
                        $&nbsp; {{number_format($data['amount'],2)}}
                    </td>

                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="45%">&nbsp;</td>
                    <td width="55%" style="padding:10px 20px; border-radius:15px; background:#fff;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="38%">Authorized Signature</td>
                                <td width="62%"
                                    style="width:100%; display:inline-block; border:none; background:none; border-bottom:1px solid #000; border-radius:0">
                                    &nbsp;<img src="https://www.zamarmusicacademy.ca/files/signature.png"
                                               style="float:left;margin:2px;width:120px;height:60px;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="center"> Zamar Music Academy</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
