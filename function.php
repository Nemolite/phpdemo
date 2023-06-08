<?php
/**
 * Функции
 */

function dd($param){
    print_r($param);
}

function shtml($param){
    return htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
}

function redirect($page){
    include_once __DIR__ . '/'.$page.'.php';
}

// Получение товаров
function getProduct($pdo){
    if (isset($_GET['id'])&&($_GET['id']!=NULL)){
        $id = shtml($_GET['id']);

        $sql = "SELECT products.id,products.name,products.description,products.price,products.country FROM categories 
        LEFT JOIN category_product ON categories.id = category_product.category_id 
        LEFT JOIN products ON category_product.product_id = products.id 
        WHERE categories.id = :id";

        $result = $pdo->prepare($sql);
        $result->bindValue(":id", $id);
        $result->execute();
        ?>
         <div class="container">
          <div class="row">
        <?php while($product = $result->fetch()){ ?>
              <div class="col-4">
                  <h3><?= $product['name'];?></h3>
                  <p><?= $product['price'];?></p>
                  <div class="main-content-img">
                      <img src="images/img1.jpg" alt="<?php echo $product['name'];?>">
                  </div>
                  <div class="btn-show-product">
                      <form method="post" action="http://<?= $_SERVER["SERVER_NAME"].'/showproduct.php'?>" name="showproduct">
                          <input type="hidden"  name="showprodid" value="<?= $product['id'];?>">
                          <button type="submit" class="btn btn-success">Подробно</button>
                      </form>
                  </div>

                  <form method="post" action="cartproduct">
                      <input type="hidden"  name="prodid" value="<?= $product['id'];?>">
                      <button type="submit" class="btn btn-success">Добавить в корзину</button>
                  </form>
              </div>
         <?php } ?>
          </div>
        </div>
            <?

    } else {
        // Вывод всех товаров
        $sql = "SELECT * FROM products";
        $result = $pdo->query($sql);
        ?>
        <div class="container">
          <div class="row">
        <?php while($product = $result->fetch()){ ?>
            <div class="col-4">
                <h3><?= $product['name'];?></h3>
                <p><?= $product['price'];?></p>
                <div class="main-content-img">
                    <img src="images/img1.jpg" alt="<?= $product['name'];?>">
                </div>
                <div class="btn-show-product">
                    <form method="post" action="http://<?= $_SERVER["SERVER_NAME"].'/showproduct.php'?>">
                        <input type="hidden"  name="showprodid" value="<?= $product['id'];?>">
                        <button type="submit" class="btn btn-success">Подробно</button>
                    </form>
                </div>

                <form method="post" action="cartproduct">
                    <input type="hidden"  name="prodid" value="<?= $product['id'];?>">
                    <button type="submit" class="btn btn-success">Добавить в корзину</button>
                </form>
            </div>
          <?php } ?>
          </div>
        </div>
            <?
       }
}

// Получение категории
function getCategory($pdo){
    $pdo->exec("set names utf8");
    $sql = "SELECT * FROM categories";
    $result = $pdo->query($sql);
    while($category = $result->fetch()){
        ?>
        <a href="http://<?= $_SERVER["SERVER_NAME"].'/?id='.$category['id']?>"><h3><?php echo $category['name'];?></h3></a>
        <p><?= $category['description'];?></p>
        <?
    }
}
?>
