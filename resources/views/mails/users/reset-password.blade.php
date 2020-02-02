<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirm registration</title>
</head>
<body>
<p>
    Hello {{$user->name}}!
</p>
<p>
    You have been requested for a new password in {{ $appName }}! This link will provide you to reset password form:
    <a href="{{ $resetPasswordLink }}">{{ $resetPasswordLink }}</a>
</p>
</body>
</html>
