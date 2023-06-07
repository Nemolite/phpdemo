
<?php include_once __DIR__ . '/header.php'; ?>

<div class="main">
    <div class="main-sidebar">
        <h2>Категории товаров</h2>
        <a href="http://<?= $_SERVER["SERVER_NAME"]?>"><h3>Все категории</h3></a>
        <?php getCategory($pdo);?>
    </div>
    <div class="main-content">
        <h2>Каталог товаров</h2>
        <?php getProduct($pdo);?>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
