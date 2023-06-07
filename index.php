<?php include_once __DIR__ . '/header.php'; ?>

<div class="main">
    <div class="main-sidebar">
        <h2>Категории товаров</h2>
        <?php
        $pdo->exec("set names utf8");
        $sql = "SELECT * FROM categories";
        $result = $pdo->query($sql);
        while($category = $result->fetch()){
        ?>
            <a href=""><h3><?php echo $category['name'];?></h3></a>
            <p><?php echo $category['description'];?></p>
        <?
        }
        ?>
    </div>
    <div class="main-content">
        <h2>Каталог товаров</h2>
        <?php
        $pdo->exec("set names utf8");
        $sql = "SELECT * FROM products";
        $result = $pdo->query($sql);
        while($product = $result->fetch()){
            ?>
            <h3><?php echo $product['name'];?></h3>
            <p><?php echo $product['description'];?></p>
            <p><?php echo $product['description'];?></p>
            <p><?php echo $product['price'];?></p>
            <p><?php echo $product['country'];?></p>
            <?
        }
        ?>

    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
