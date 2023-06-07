<?php
/**
 * Функции
 */

function dd($param){
    print_r($param);
}

// Получение товаров
function getProduct($pdo){
    if (isset($_GET['id'])&&($_GET['id']!=NULL)){
        $id = htmlspecialchars($_GET['id']);

        $sql = "SELECT products.name,products.description,products.price,products.country FROM categories 
        LEFT JOIN category_product ON categories.id = category_product.category_id 
        LEFT JOIN products ON category_product.product_id = products.id 
        WHERE categories.id = :id";

        $result = $pdo->prepare($sql);
        $result->bindValue(":id", $id);
        $result->execute();
        while($product = $result->fetch()){
            ?>
            <h3><?php echo $product['name'];?></h3>
            <p><?php echo $product['description'];?></p>
            <p><?php echo $product['price'];?></p>
            <p><?php echo $product['country'];?></p>
            <?
        }

    } else {
        // Вывод всех товаров
        $sql = "SELECT * FROM products";
        $result = $pdo->query($sql);
        while($product = $result->fetch()){
            ?>
            <h3><?php echo $product['name'];?></h3>
            <p><?php echo $product['description'];?></p>
            <p><?php echo $product['price'];?></p>
            <p><?php echo $product['country'];?></p>
            <?
        }
    }
}
?>
