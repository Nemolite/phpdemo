<?php include_once __DIR__ . '/header.php'; ?>
<?php echo $_SESSION['id'];?>
<?php
if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {



?>
<?php
}
else {
    echo "Вам необходимо пройти авторизацию";
    ?>
    <p>Через 5 секунд будет произведено перенаправление на страницу авторизации</p>
    <script> window.setTimeout(function() { window.location = 'login.php'; }, 5000) </script>
    <?php
    //header('Location: ../chek.php'); exit();
}
?>

<?php include_once __DIR__ . '/footer.php'; ?>
