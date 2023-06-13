<?php include_once __DIR__ . '/header.php'; ?>
<div class="account-main">
<h1>Аккаунт пользователя</h1>
<?php
if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
    $userid = $_SESSION['id'];
    $user = getDataUser($pdo,$userid);
    printf('<h3>Пользователь %s</h3>',$user['name']);
    printf('<h3>Email %s</h3>',$user['email']);
?>
    <h2 class="account-title">Мои заказы</h2>
<?php getOrders($pdo,$userid);?>
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
