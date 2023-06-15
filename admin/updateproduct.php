<?php include_once __DIR__ . '/header.php'; ?>
<?php include_once __DIR__ . '/function.php'; ?>
<div class="account-main">
    <h1>Панель управления</h1>
    <?php
    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];

        $user = getDataUser($pdo,$userid);
    if($user['rols']) {
        echo  "Привет, Администратор";
        ?>

        <div class="main-sidebar">
            <div class="header-menu-admin">
                <nav>
                    <ul>
                        <li><a href="categories.php">Категории</a></li>
                        <li><a href="products.php">Товары</a></li>
                        <li><a href="orders.php">Заказы</a></li>
                        <li><a href="users.php">Пользователи</a></li>

                    </ul>
                </nav>
            </div>

        </div> <!-- class="main-sidebar" -->
        <div class="main-content">
            <div class="main-product-admin">
                <div class="main-sidebar-products">
                    <h3>Товары</h3>
                    <?php getAdminProducts($pdo);?>
                </div>
                <div class="main-content-products">
                    <h2>Изменение товара</h2>
                    <?php $updatproduct = getAdminProductUpdate($pdo);?>
                    <form method="post" action="" name="adminproduct" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Наименование товара</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $updatproduct['name'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание товара</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $updatproduct['description'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Цена товара</label>
                            <input type="number" class="form-control" id="price" name="price" value="<?php echo $updatproduct['price'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Страна производитель</label>
                            <input type="text" class="form-control" id="country" name="country" value="<?php echo $updatproduct['country'];?>">
                        </div>
                        <p class="cat-color">Выбранные категории
                            <?php
                            $sql = "SELECT * FROM categories";
                            $result = $pdo->query($sql);
                            while($category = $result->fetch()){
                                ?>
                               <?= $category['name']?>
                                <?php echo " , ";?>
                                <?php
                            }
                            ?>
                        </p>
                        <select class="form-select" multiple aria-label="multiple select example" name="categories[]" required>
                            <?php
                            $sql = "SELECT * FROM categories";
                            $result = $pdo->query($sql);
                            while($category = $result->fetch()){
                                ?>
                                <option value="<?= $category['id']?>"><?= $category['name']?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="mini-img-admin-product">
                            <img src="../images/<?= $updatproduct['image'];?>" alt="<?php echo $updatproduct['name'];?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                            <div class="form-file">
                                <input type="file" class="form-file-input" id="image" name="image">
                                <label class="form-file-label" for="image">
                                    <span class="form-file-text">Размер файла не более 4Мб</span>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="upproductid" value="<?= $updatproduct['id'];?>" />
                        <input type="hidden" name="tupproduct" value="<?php echo(rand(10000,99999));?>" />
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                    <?php upAdminProduct($pdo);?>
                </div>
            </div>

        </div><!-- class="main-content" -->
        <?php
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
    }
    ?>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
