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
        <h2>Заказы</h2>
        </div><!-- class="main-content" -->
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
