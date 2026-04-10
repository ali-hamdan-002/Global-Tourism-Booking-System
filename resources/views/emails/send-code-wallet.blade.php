@component('mail::message')



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #00d5ff37;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>welcome </h1>
        </div>
        <div class="content">

            <p>Thank you for joining<span style="color: #007bff;">j-path</span>.We are happy to have you as a part of our community.</p>
            <p>If you have any questions or need assistance, do not hesitate to contact us.</p>
            <p>You presonal account confirmation code is :</p>
            @component('mail::panel')
{{ $code }}
@endcomponent

            <p>مع تحيات,<br>  الباك ايند دوفيلوبر حمدون/p>
        </div>
        <div class="footer">
            <p>&copy; 2024    . جميع الحقوق محفوظة.          </p>
        </div>
    </div>
</body>
</html>
@endcomponen
