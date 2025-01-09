<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f4f7fc;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #005395;
            font-size: 32px;
            margin: 0;
        }
        .content {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .content p {
            margin: 10px 0;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #005395;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            font-size: 14px;
            text-align: center;
            color: #777;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #005395;
            text-decoration: none;
        }
        .note {
            font-size: 14px;
            background-color: #f9f9f9;
            border: 1px solid #e3e3e3;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>