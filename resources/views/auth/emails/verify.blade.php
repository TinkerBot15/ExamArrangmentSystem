<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification</h1>
    <p>Hi {{ $user->name }},</p>
    <p>Please click the following link to verify your email:</p>
    <p><a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></p>
</body>
</html>
