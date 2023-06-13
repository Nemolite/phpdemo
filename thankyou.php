<?php include_once __DIR__ . '/header.php'; ?>
<div class="chekout-main">
    <h1>Спасибо за заказ!</h1>
    <?php
    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];
        getOrders($pdo,$userid,'last');
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
