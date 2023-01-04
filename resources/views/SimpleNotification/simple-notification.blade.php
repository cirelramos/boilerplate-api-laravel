<!DOCTYPE html>
<html>
<head>
    <title>Simple Notification</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
</head>
<body style="background-color: #ffffff; margin: 0 !important; padding: 0 !important;">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
    <!-- COPY -->
    <tr>
        <td align="left" style="padding: 20px 10px 20px 10px; color: #000000; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.3em; font-weight:normal;">
            <p style="padding: 15px; background: #f1f1f1; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px;">
                <b>Time:</b> {{now()}} <br>
                @foreach($endpoint as $key => $oneEndpoint)
                    @if(is_array($oneEndpoint) === false)
                        <b>{{strtoupper($key)}}:</b> {{$oneEndpoint}} <br>
                    @endif
                    @if(is_array($oneEndpoint))
                        <b>{{strtoupper($key)}}:</b><br>
                        @foreach($oneEndpoint as $keyOne => $valueOne)
                            {{$valueOne}} <br>
                        @endforeach
                    @endif
                @endforeach
            </p>
        </td>
    </tr>
</table>
</body>
</html>
