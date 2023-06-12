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
        $row = $st->execute();
        if ($row > 0) {
            echo "Категория добавлена";
            unset($row);
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
    } //tokenadminproduct
    echo $msg;
}
?>