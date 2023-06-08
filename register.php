<?php include_once __DIR__ . '/header.php'; ?>
<div class="main">
    <div class="main-register">
        <form method="post" action="" id="registerform" name="registerform">
                <legend>Регистрация нового пользователя</legend>
                <div class="mb-3">
                    <label for="name" class="form-label">Имя пользователя</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Имя пользователя" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Электрнная почта" required>
                </div>
                <div class="mb-3">
                    <label for="pass1" class="form-label">Пароль</label>
                    <input type="password" id="pass1" name="pass1" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pass2" class="form-label">Пароль</label>
                    <input type="password" id="pass2" name="pass2" class="form-control" required>
                </div>
                <input type="hidden" name="token" value="<?php echo(rand(10000,99999));?>" />
                <button type="submit" id="register" name="register" class="btn btn-primary">Зарегистрировать</button>

        </form>
        <p id="register-msg"></p>
        <?php registerUsers($pdo); ?>

    </div>
</div>
<?php include_once __DIR__ . '/footer.php'; ?>
