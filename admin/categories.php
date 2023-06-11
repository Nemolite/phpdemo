<?php include_once __DIR__ . '/header.php'; ?>
<?php include_once __DIR__ . '/function.php'; ?>
<div class="account-main">
    <h1>Панель управления</h1>
    <?php
    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];

        $user = getDataUser($pdo,$userid);
    if($user['rols']) {
        echo  "Привет, Администратор";
        ?>
    <div class="main-admin">
        <div class="main-sidebar">
            <div class="header-menu-admin">
                <nav>
                    <ul>
                        <li><a href="categories.php">Категории</a></li>
                        <li><a href="products.php">Товары</a></li>
                        <li><a href="orders.php">Заказы</a></li>
                        <li><a href="users.php">Пользователи</a></li>

                    </ul>
                </nav>
            </div>

        </div> <!-- class="main-sidebar" -->
        <div class="main-content">
        <div class="main">
            <div class="main-sidebar">
                <h3>Категории товаров</h3>
                <?php getAdminCategory($pdo);?>
            </div>
            <div class="main-content">
                <h2>Добавление категории</h2>
                <form method="post" action="" name="admincategory">
                    <div class="mb-3">
                        <label for="name" class="form-label">Категория товаров</label>
                        <input type="text" class="form-control" id="name" name="name">

                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание категории</label>
                        <input type="hidden" name="tokenadmincategory" value="<?php echo(rand(10000,99999));?>" />
                        <input type="text" class="form-control" id="description" name="description">

                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
                <?php setAdminCategory($pdo);?>
            </div>
        </div>

        </div><!-- class="main-content" -->
    </div><!-- class="main-admin" -->
        <?php
    }
        ?>
    <?php
    }
    else {
    echo "Вам необходимо пройти авторизацию";
    ?>
        <p>Через 5 секунд будет произведено перенаправление на страницу авторизации</p>
        <script> window.setTimeout(function() { window.location = 'login.php'; }, 5000) </script>
        <?php
    }
    ?>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
