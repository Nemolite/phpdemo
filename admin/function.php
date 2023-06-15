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
                    <td><?php echo $product['image']?></td>
                    <td><?php echo $product['country']?></td>
                    <td>
                        <a href="" class="btn btn-warning">Изменить</a>
                        <a href="" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
                <?php
                $index++;
                }
          ?>
            </tbody>
        </table>
<?php
}

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

        if (isset($_POST['name']) && (!empty($_POST['name']))) {
            $name = shtml($_POST['name']);
            unset($_POST['name']);
        }
        if (isset($_POST['description']) && (!empty($_POST['description']))) {
            $description = shtml($_POST['description']);
            unset($_POST['description']);
        }

        if (isset($_POST['upadmincategoryid']) && (!empty($_POST['upadmincategoryid']))) {
            $categoryid = shtml($_POST['upadmincategoryid']);
            unset($_POST['upadmincategoryid']);
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
?>

