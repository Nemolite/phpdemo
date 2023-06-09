<?php include_once __DIR__ . '/header.php'; ?>
<div class="cart-main">
    <h1>Ваша корзина</h1>
    <?php
    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];
        $user = getDataUser($pdo,$userid);
        printf('<h3>Пользователь %s</h3>',$user['name']);
        ?>
        <?php
        if (isset($_SESSION['orders'])&&!empty($_SESSION['orders'])) {
            $session = $_SESSION['orders'];
            foreach ($session as $value){
                foreach ($value as $user=>$productid){
                    $productids[] = $productid;
                }

            }
            $strproducts = implode(",", $productids);
            $sql ="SELECT * FROM products WHERE id IN ( $strproducts )";
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
                $total = 0;
            while($product = $stmt->fetch()){
                        ?>
                <tr>
                    <th scope="row"><?php echo $index;?></th>
                    <td><?php echo $product['name']?></td>
                    <td><?php echo $product['price']?></td>
                    <td><?php echo $product['description']?></td>
                    <td><?php echo $product['image']?></td>
                    <td><?php echo $product['country']?></td>
                    <td>Удалить</td>
                </tr>
                <?php
                $index++;
                $total+=(int)$product['price'];
                }
          ?>
            </tbody>
        </table>
                <?php
                printf("Общая стоимость заказа = %d",$total);
        } else {
            echo "Ваша корзина пуста";
        }
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

</div>
<?php include_once __DIR__ . '/footer.php'; ?>
