<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #a5f9fb;
            color: #fff;
            text-align: center;
        }
        .container {
            padding: 30px;
        }
        .button {
            display: inline-block;
            background: #21c4ff;
            color: #fff;
            padding: 15px 25px;
            margin: 20px 0;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }
        .button:hover {
            background: #2957fd;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> Hello {{ $user->name }},</h1>
        <p>You requested to reset your password for <strong>NextLevelGaming</strong>.</p>
        <a href="{{ $url }}" class="button">Reset My Password</a>
        <p class="footer">
            If you did not request this action, you can safely ignore this email.<br>

        </p>
    </div>
</body>
</html>
