<!DOCTYPE html>
<html>
<head>
    <title>Şifre Sıfırlama</title>
</head>
<body>
    <h1>Şifre Sıfırlama Talebi</h1>
    <p>Aşağıdaki bağlantıya tıklayarak şifrenizi sıfırlayabilirsiniz:</p>
    <a href="{{ url('password/reset/'.$token) }}">Şifrenizi Sıfırlayın</a>
    <p>Bu bağlantı 60 dakika süreyle geçerlidir.</p>
</body>
</html>
