<?php
/**
 * Функции для Администратора
 */
/**
 * Получение категории в админке
 * @param $pdo
 * @return void
 */
function getAdminCategory($pdo){
    $pdo->exec("set names utf8");
    $sql = "SELECT * FROM categories";
    $result = $pdo->query($sql);
    while($category = $result->fetch()){
        ?>
        <h3><?php echo $category['name'];?></h3>
        <p><?= $category['description'];?></p>
            <div class="category-event">
                <form method="post" action="" name="delcategory<?php echo $category['id']?>">
                    <input type="hidden" name="delidcategory" value="<?php echo $category['id']?>" />
                    <input type="hidden" name="tokendelidcategory" value="<?php echo(rand(10000,99999));?>" />
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
                <form method="post" action="updatecategory.php" name="updatecategory<?php echo $category['id']?>">
                    <input type="hidden" name="updatidcategory" value="<?php echo $category['id']?>" />
                    <input type="hidden" name="tokenupdatidcategory" value="<?php echo(rand(10000,99999));?>" />
                    <button type="submit" class="btn btn-warning">Изменить</button>
                </form>
            </div>
        <?php delAdminCategory($pdo)?>
        <?php
        }
    }

/**
 * Добавление категори в админке
 * @param $pdo
 * @return void
 */
function setAdminCategory($pdo) {
    if ($_POST['tokenadmincategory'] == $_SESSION['lasttokenadmincategory'])
    {
        echo "";
    } else {
        $_SESSION['lasttokenadmincategory'] = $_POST['tokenadmincategory'];

        if (isset($_POST['name']) && (!empty($_POST['name']))) {
            $name = shtml($_POST['name']);
            unset($_POST['name']);
        }
        if (isset($_POST['description']) && (!empty($_POST['description']))) {
            $description = shtml($_POST['description']);
            unset($_POST['description']);
        }

        $sqlinsert = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $st = $pdo->prepare($sqlinsert);

        $st->bindValue(":name", $name);
        $st->bindValue(":description", $description);
        if ($name){
            $row = $st->execute();
        }

        if ($row > 0) {
            echo "Категория добавлена";
            unset($row);
            ?>
            <script> window.setTimeout(function() { window.location = 'categories.php'; }, 2000) </script>
            <?php
        }
    } // tokenadmincategory
}

/**
 * Вывод товаров в админке
 * @param $pdo
 * @return void
 */
function getAdminProducts($pdo){
    $pdo->exec("set names utf8");
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    ?>
   <table class="table">
        <thead>
        <tr>
            <th scope="col">№пп</th>
            <th scope="col">Наименование</th>
            <th scope="col">Стоимость</th>
            <th scope="col">Описание</th>
            <th scope="col">Миниатюра</th>
            <th scope="col">Страна производитель</th>
            <th scope="col">Действие</th>
        </tr>
        </thead>
        <tbody>
                <?php
                $index = 1;
            while($product = $stmt->fetch()){
                        ?>
                <tr>
                    <th scope="row"><?php echo $index;?></th>
                    <td><?php echo $product['name']?></td>
                    <td><?php echo $product['price']?></td>
                    <td><?php echo $product['description']?></td>
                    <td>
                        <div class="mini-img-admin-product">
                            <img src="../images/<?= $product['image'];?>" alt="<?php echo $product['name'];?>">
                        </div>
                    </td>
                    <td><?php echo $product['country']?></td>
                    <td>
                        <div class="category-event">
                            <form method="post" action="" name="delproduct<?php echo $product['id']?>">
                                <input type="hidden" name="delidproduct" value="<?php echo $product['id']?>" />
                                <input type="hidden" name="tokendelproduct" value="<?php echo(rand(10000,99999));?>" />
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                            <form method="post" action="updateproduct.php" name="updatecategory<?php echo $product['id']?>">
                                <input type="hidden" name="upidproduct" value="<?php echo $product['id']?>" />
                                <input type="hidden" name="tokenupproduct" value="<?php echo(rand(10000,99999));?>" />
                                <button type="submit" class="btn btn-warning">Изменить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php
                $index++;
                }
          ?>
            </tbody>
        </table>
    <?php delAdminProduct($pdo);?>
<?php
}

/**
 * Добавление товаров
 * @param $pdo
 * @return void
 */
function setAdminProduct($pdo){
    if ($_POST['tokenadminproduct'] == $_SESSION['lasttokenadminproduct'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttokenadminproduct'] = $_POST['tokenadminproduct'];

        if (isset($_POST['name']) && (!empty($_POST['name']))) {
            $name = shtml($_POST['name']);
            unset($_POST['name']);
        }
        if (isset($_POST['price']) && (!empty($_POST['price']))) {
            $price = (integer)shtml($_POST['price']);
            unset($_POST['price']);
        }
        if (isset($_POST['description']) && (!empty($_POST['description']))) {
            $description = shtml($_POST['description']);
            unset($_POST['description']);
        }

        if (isset($_POST['country']) && (!empty($_POST['country']))) {
            $country = shtml($_POST['country']);
            unset($_POST['country']);
        }

        $filename = basename($_FILES['image']['name']);
        $uploadfile = $_SERVER["DOCUMENT_ROOT"] . '/images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

        $sqlinsert = "INSERT INTO products (name, price, description,country,image) 
                      VALUES (:name, :price,:description, :country, :image)";
        $st = $pdo->prepare($sqlinsert);

        $st->bindValue(":name", $name);
        $st->bindValue(":price", $price);
        $st->bindValue(":description", $description);
        $st->bindValue(":country", $country);
        $st->bindValue(":image", $filename);
        $row = $st->execute();

        // Добавление связей с категориями
        $productid =  $pdo->lastInsertId();
        if (isset($_POST['categories']) && (!empty($_POST['categories']))) {
            $categories = $_POST['categories'];
            unset($_POST['categories']);
        }

        $sqlinsert = "INSERT INTO category_product (category_id, product_id ) 
                      VALUES (:categoryid, :productid)";
        $st = $pdo->prepare($sqlinsert);

        foreach ($categories as $categoryid){
            $st->bindValue(":categoryid", $categoryid);
            $st->bindValue(":productid", $productid);
            $st->execute();
        }

        if ($row > 0) {
            $msg =  "Товар добавлена";
            unset($row);
        }

    } //tokenupadminproduct
    echo $msg;

}

/**
 * Получение товаров в форму обновления
 * @param $pdo
 * @return mixed|void
 */
function getUpdateCategory($pdo){
    if ($_POST['tokenupdatidcategory'] == $_SESSION['lasttokenupdatidcategory'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttokenupdatidcategory'] = $_POST['tokenupdatidcategory'];

        if (isset($_POST['updatidcategory']) && (!empty($_POST['updatidcategory']))) {
            $updatidcategory = shtml($_POST['updatidcategory']);
            unset($_POST['updatidcategory']);
        }

        $sth = $pdo->prepare("SELECT * FROM categories WHERE categories.id = ?");
        $sth->execute(array($updatidcategory));
        $updatcategory =  $sth->fetch();

        return $updatcategory;
    }
}

/**
 * Обновление категории в админке
 * @param $pdo
 * @return void
 */
function upAdminCategory($pdo){
    if ($_POST['tokenupadmincategory'] == $_SESSION['lasttokenupadmincategory'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttokenupadmincategory'] = $_POST['tokenupadmincategory'];

        if (isset($_POST['upadmincategoryid']) && (!empty($_POST['upadmincategoryid']))) {
            $categoryid = shtml($_POST['upadmincategoryid']);
            unset($_POST['upadmincategoryid']);
        }

        if (isset($_POST['name']) && (!empty($_POST['name']))) {
            $name = shtml($_POST['name']);
            unset($_POST['name']);
        }

        if (isset($_POST['description']) && (!empty($_POST['description']))) {
            $description = shtml($_POST['description']);
            unset($_POST['description']);
        }

        $sqlupdate = "UPDATE categories SET name = :name, description= :description WHERE id = :categoryid";
        $st = $pdo->prepare($sqlupdate);
        $st->bindValue(":name", $name);
        $st->bindValue(":description", $description);
        $st->bindValue(":categoryid", $categoryid);
        $row = $st->execute();
        if ($row > 0) {
            $msg = "Категория изменена";
            unset($row);

        }
        echo $msg;
        ?>
        <script> window.setTimeout(function() { window.location = 'categories.php'; }, 2000) </script>
        <?php

    } // tokenadmincategory
}

/**
 * Удаление категории
 * @param $pdo
 * @return void
 */
function delAdminCategory($pdo){
    if ($_POST['tokendelidcategory'] == $_SESSION['lasttokendelidcategory'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttokendelidcategory'] = $_POST['tokendelidcategory'];


        if (isset($_POST['delidcategory']) && (!empty($_POST['delidcategory']))) {
            $delcatid = shtml($_POST['delidcategory']);
            unset($_POST['delidcategory']);
        }

        // Удаление из таблицы category_product
        $sqldelcp = "DELETE FROM category_product WHERE category_id = :categoryid";
        $stcp = $pdo->prepare($sqldelcp);
        $stcp->bindValue(":categoryid", $delcatid);
        $stcp->execute();

        // Удаление из таблицы categories
        $sqldel = "DELETE FROM categories WHERE id = :categoryid";
        $st = $pdo->prepare($sqldel);
        $st->bindValue(":categoryid", $delcatid);
        $row = $st->execute();
        if ($row > 0) {
            $msg = "Категория удалена";
            unset($row);

        }
        echo $msg;
        ?>
        <script> window.setTimeout(function() { window.location = 'categories.php'; }, 2000) </script>
        <?php

    }
}

/**
 * Получение товара для обновления
 * @param $pdo
 * @return mixed|void
 */
function getAdminProductUpdate($pdo){

    if ($_POST['tokenupproduct'] == $_SESSION['lasttokenupproduct'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttokenupproduct'] = $_POST['tokenupproduct'];

        if (isset($_POST['upidproduct']) && (!empty($_POST['upidproduct']))) {
            $upidproduct = shtml($_POST['upidproduct']);
            unset($_POST['upidproduct']);
        }

        $sth = $pdo->prepare("SELECT * FROM products WHERE products.id = ?");
        if ($upidproduct) {
            $sth->execute(array($upidproduct));
        }

        $updatproduct =  $sth->fetch();

        return $updatproduct;
    } // tokenupproduct
}

/**
 * Обновление товара
 * @param $pdo
 * @return void
 */
function upAdminProduct($pdo) {
    if ($_POST['tupproduct'] == $_SESSION['lasttupproduct'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttupproduct'] = $_POST['tupproduct'];

        if (isset($_POST['upproductid']) && (!empty($_POST['upproductid']))) {
            $upproductid = shtml($_POST['upproductid']);
            unset($_POST['upproductid']);
        }

        if (isset($_POST['name']) && (!empty($_POST['name']))) {
            $name = shtml($_POST['name']);
            unset($_POST['name']);
        }
        if (isset($_POST['price']) && (!empty($_POST['price']))) {
            $price = (integer)shtml($_POST['price']);
            unset($_POST['price']);
        }
        if (isset($_POST['description']) && (!empty($_POST['description']))) {
            $description = shtml($_POST['description']);
            unset($_POST['description']);
        }

        if (isset($_POST['country']) && (!empty($_POST['country']))) {
            $country = shtml($_POST['country']);
            unset($_POST['country']);
        }

        $filename = basename($_FILES['image']['name']);
        $uploadfile = $_SERVER["DOCUMENT_ROOT"] . '/images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

        $sqlupdate = "UPDATE products 
                      SET name = :name, description= :description,price= :price,country= :country,image= :image 
                      WHERE id = :upproductid";

        $st = $pdo->prepare($sqlupdate);

        $st->bindValue(":name", $name);
        $st->bindValue(":price", $price);
        $st->bindValue(":description", $description);
        $st->bindValue(":country", $country);
        $st->bindValue(":image", $filename);
        $st->bindValue(":upproductid", $upproductid);

        $row = $st->execute();

        // Добавление связей с категориями
        if (isset($_POST['categories']) && (!empty($_POST['categories']))) {
            $categories = $_POST['categories'];
            unset($_POST['categories']);
        }

        $sqlinsert = "INSERT INTO category_product (category_id, product_id ) 
                      VALUES (:categoryid, :productid)";
        $st = $pdo->prepare($sqlinsert);

        foreach ($categories as $categoryid){
            $st->bindValue(":categoryid", $categoryid);
            $st->bindValue(":productid", $upproductid);
            $st->execute();
        }

        if ($row > 0) {
            $msg =  "Товар добавлена";
            unset($row);
        }
        echo $msg;
        ?>
        <script> window.setTimeout(function() { window.location = 'products.php'; }, 2000) </script>
        <?php

    } //tokenupadminproduct
}

/**
 * Удаление товара
 * @param $pdo
 * @return void
 */
function delAdminProduct($pdo) {
    if ($_POST['tokendelproduct'] == $_SESSION['lasttokendelproduct'])
    {
        $msg = "";
    } else {
        $_SESSION['lasttokendelproduct'] = $_POST['tokendelproduct'];


        if (isset($_POST['delidproduct']) && (!empty($_POST['delidproduct']))) {
            $delidproduct = (integer)shtml($_POST['delidproduct']);
            unset($_POST['delidproduct']);
        }

        // Удаление из таблицы category_product
        $sqldelcp = "DELETE FROM category_product WHERE product_id  = :productid ";
        $stcp = $pdo->prepare($sqldelcp);
        $stcp->bindValue(":productid", $delidproduct);
        $stcp->execute();

        // Удаление из таблицы category_product
        $sqldelop = "DELETE FROM order_product WHERE product_id  = :productid ";
        $stcpop = $pdo->prepare($sqldelop);
        $stcpop->bindValue(":productid", $delidproduct);
        $stcpop->execute();

        // Удаление из таблицы products
        $sqldel = "DELETE FROM products WHERE id = :delidproduct";
        $st = $pdo->prepare($sqldel);
        $st->bindValue(":delidproduct", $delidproduct);
        $row = $st->execute();
        if ($row > 0) {
            $msg = "Товар удален";
            unset($row);

        }
        echo $msg;
        ?>
        <script> window.setTimeout(function() { window.location = 'products.php'; }, 2000) </script>
        <?php

    }
}
?>
