
<?php include_once __DIR__ . '/header.php'; ?>

<div class="main">
    <div class="main-sidebar">
        <h2>Категории товаров</h2>
        <a href="http://<?= $_SERVER["SERVER_NAME"]?>"><h3>Все категории</h3></a>
        <?php
        $pdo->exec("set names utf8");
        $sql = "SELECT * FROM categories";
        $result = $pdo->query($sql);
        while($category = $result->fetch()){
        ?>
            <a href="http://<?= $_SERVER["SERVER_NAME"].'/?id='.$category['id']?>"><h3><?php echo $category['name'];?></h3></a>
            <p><?php echo $category['description'];?></p>
        <?
        }
        ?>
    </div>
    <div class="main-content">
        <h2>Каталог товаров</h2>
        <?php getProduct($pdo);?>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
