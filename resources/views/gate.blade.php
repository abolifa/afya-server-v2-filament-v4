<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الهيئة الوطنية لأمراض الكلى - البوابة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>

        * {
            font-family: Cairo, sans-serif;
        }

        body {
            background-color: #242427;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            color: #ffffff;
        }

        .container {
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .card-link {
            text-decoration: none;
        }

        .card {
            min-width: 270px;
            min-height: 180px;
            border: 1px solid #353535;
            border-radius: 18px;
            padding: 35px 30px;
            background: #1b1b1e;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            font-size: 1.3rem;
            box-shadow: 0 2px 18px #0004;
            transition: transform 0.2s, box-shadow 0.2s, border 0.2s;
        }

        .card:hover {
            border: 1px solid #4ade80;
            box-shadow: 0 4px 28px #4ade80, 0 2px 18px #0005;
            transform: scale(1.04) translateY(-4px);
            color: #4ade80;
        }

        .card i {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="/admin" class="card-link">
        <div class="card">
            <i class="fa-solid fa-users"></i>
            نظام عافية الصحي
        </div>
    </a>
    <a href="/site" class="card-link">
        <div class="card">
            <x-gmdi-code style="width: 80px; height: 80px;"/>
            إدارة الموقع الإلكتروني
        </div>
    </a>
</div>
</body>
</html>
