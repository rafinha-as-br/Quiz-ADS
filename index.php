<!-- index.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Quiz ADS - Login / Cadastro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <h2>Bem-vindo ao Quiz de ADS</h2>

    <div class="form-toggle">
        <button id="show-login">Login</button>
        <button id="show-register">Cadastro</button>
    </div>

    <div id="login-form" class="form-section">
        <h3>Login</h3>
        <form id="form-login">
            <input type="email" name="email" placeholder="Seu email" required>
            <input type="password" name="password" placeholder="Sua senha" required>
            <button type="submit">Entrar</button>
        </form>
        <div class="msg" id="login-msg"></div>
    </div>

    <div id="register-form" class="form-section" style="display:none;">
        <h3>Cadastro</h3>
        <form id="form-register">
            <input type="email" name="email" placeholder="Seu email" required>
            <input type="password" name="password" placeholder="Sua senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <div class="msg" id="register-msg"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
