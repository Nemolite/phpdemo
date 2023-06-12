<?php include_once __DIR__ . '/header.php'; ?>
<div class="chekout-main">
    <h1>Оформление заказа</h1>
    <?php
    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];

        if (isset($_SESSION['orders']) && !empty($_SESSION['orders'])) {
            $session = $_SESSION['orders'];
            foreach ($session as $value) {
                foreach ($value as $user_id => $productid) {
                    if ($user_id == $userid) {
                        $productids[] = $productid;
                    }

                }

            }
            $strproducts = implode(",", $productids);
            $sql = "SELECT * FROM products WHERE id IN ( $strproducts )";
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
                    <td>
                        <div class="product-mini">
                            <img src="images/<?= $product['image'];?>" alt="<?php echo $product['name'];?>">
                        </div>
                    </td>
                    <td><?php echo $product['country']?></td>
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
    }
                ?>
        <?php
        $user = getDataUser($pdo,$userid)
        ?>
            <h2>Данные заказа</h2>
        <form method="post" action="" name="checkout">
            <div class="mb-3">
                <label for="name" class="form-label">Получатель</label>
                <input type="text" class="form-control" id="name" name="name" required value="<?=$user['name']?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">email</label>
                <input type="text" class="form-control" id="email" name="email" required value="<?=$user['email']?>">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Адрес доставки</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Телефон</label>
                <input type="number" class="form-control" id="phone" name="phone" required>
            </div>
            <input type="hidden" name="tokencheckout" value="<?php echo(rand(10000,99999));?>" />
            <button type="submit" name="checkout" class="btn btn-primary checkout">Подтвердить заказ</button>
        </form>
        <?php
        sendOrders($pdo);
    }
    else {
        echo "Вам необходимо пройти авторизацию";
        ?>
        <p>Через 5 секунд будет произведено перенаправление на страницу авторизации</p>
        <script> window.setTimeout(function() { window.location = 'login.php'; }, 5000) </script>
        <?php
    }
    ?>

</div>
<?php include_once __DIR__ . '/footer.php'; ?>
