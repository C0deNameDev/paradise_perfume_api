<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }
        .pattern {
            background: url('https://www.transparenttextures.com/patterns/notebook.png');
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.15;
            z-index: -1;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        h3 {
            font-size: 28px;
            color: #007bff;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pattern"></div>
        <h1>Signup Confirmation</h1>
        <p>Thank you for registering!</p>
        <p>Below is your confirmation code:</p>
        <h3>{{ $code }}</h3>
    </div>
</body>
</html>
