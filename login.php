<?php include_once __DIR__ . '/header.php'; ?>
<div class="main">
    <div class="main-login">
        <form method="post" action="" id="loginrform" name="loginrform">
            <legend>Вход в панель управления</legend>
            <div class="mb-3">
                <label for="name" class="form-label">Имя пользователя</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Имя пользователя" required>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Пароль</label>
                <input type="password" id="pass" name="pass" class="form-control" required>
            </div>

            <input type="hidden" name="token" value="<?php echo(rand(10000,99999));?>" />
            <button type="submit" id="login" name="login" class="btn btn-primary">Вход</button>

        </form>
        <p id="register-msg"></p>
        <?php loginUsers($pdo); ?>

    </div>
</div>
<?php include_once __DIR__ . '/footer.php'; ?>
