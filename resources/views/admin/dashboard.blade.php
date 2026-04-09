<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
    <h1>Selamat datang, Admin!</h1>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
</body>
</html>