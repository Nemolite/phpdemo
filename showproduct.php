<?php include_once __DIR__ . '/header.php'; ?>

<div class="main">
    <div class="main-sidebar">
        <h2>Категории товаров</h2>
        <a href="http://<?= $_SERVER["SERVER_NAME"]?>"><h3>Все категории</h3></a>
        <?php getCategory($pdo);?>
    </div>
    <div class="main-content">
  <?php
    if (isset($_POST['showprodid'])&&($_POST['showprodid']!=NULL)) {
        $id = shtml($_POST['showprodid']);

        $sql = "SELECT * FROM products        
        WHERE products.id = :id";

        $result = $pdo->prepare($sql);
        $result->bindValue(":id", $id);
        $result->execute();
    ?>
        <!-- Вывод карточки товара -->
        <?php
        while($product = $result->fetch()){
        ?>
        <div class="showproduct-main">
            <h1>Карточка товара</h1>
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="showproduct-img">
                            <div class="main-content-img-product">
                                <img src="images/img1.jpg" alt="<?php echo $product['name'];?>">
                            </div>
                        </div> <!-- class="showproduct-img" -->
                    </div>
                    <div class="col-6">
                        <div class="showproduct-info">

                              <h3><?= $product['name'];?></h3>
                              <p>Цена:<?= $product['price'];?></p>
                              <p>Страна производитель<?= $product['country'];?></p>
                              <p>Описание товара:<?= $product['description'];?></p>
                        </div> <!-- class="showproduct-info" -->
                    </div>
                </div> <!-- class="row" -->
            </div> <!-- class="container" -->
                              <form method="post" action="cartproduct">
                                  <input type="hidden"  name="prodid" value="<?= $product['id'];?>">
                                  <button type="submit" class="btn btn-success">Добавить в корзину</button>
                              </form>

                <?php
              } // while
        } // if
      ?>
        </div><!-- class="showproduct-main" -->
    </div><!-- class="main-sidebar" -->
</div><!-- class="main" -->

<?php include_once __DIR__ . '/footer.php'; ?>
