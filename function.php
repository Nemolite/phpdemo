<?php
/**
 * Функции
 */

function dd($param){
    echo '<pre>';
    print_r($param);
    echo '</pre>';

}

function shtml($param){
    return htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
}

function redirect($page){
    include_once __DIR__ . '/'.$page.'.php';
}

/**
 * Получение товаров
 * @param $pdo
 * @return void
 */
function getProduct($pdo){
    if (isset($_GET['id'])&&($_GET['id']!=NULL)){
        $id = shtml($_GET['id']);
        unset($_GET['id']);
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

                  <form method="post" action="" name="cartproductform">
                      <input type="hidden"  name="prodid" value="<?= $product['id'];?>">
                      <button type="submit"  name="cartproduct" class="btn btn-success cartproduct">Добавить в корзину</button>
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

                <form method="post" action="" name="cartproductform">
                    <input type="hidden"  name="prodid" value="<?= $product['id'];?>">
                    <button type="submit" class="btn btn-success cartproduct">Добавить в корзину</button>
                </form>

            </div>
          <?php } ?>
          </div>
        </div>
            <?
       }
    ?>
     <p id="result_output"></p>
    <?php
    sendProductToCart();
}

/**
 * Получение категории
 * @param $pdo
 * @return void
 */
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

/**
 * Регистрация пользователя
 * @param $pdo
 * @return void
 */

function registerUsers($pdo){
    if ($_POST['token'] == $_SESSION['lastToken'])
    {
        echo "";
    }

    else {
        $_SESSION['lastToken'] = $_POST['token'];

        if (isset($_POST['register'])) {
            if (isset($_POST['name']) && (!empty($_POST['name']))) {
                $name = shtml($_POST['name']);
                unset($_POST['name']);
            }
            if (isset($_POST['email']) && (!empty($_POST['email']))) {
                $email = shtml($_POST['email']);
                unset($_POST['email']);
            }
            if (isset($_POST['pass1']) && (!empty($_POST['pass1']))) {
                $pass = md5(shtml($_POST['pass1']));
                unset($_POST['pass1']);
            }

            $stmt = $pdo->prepare('SELECT * FROM users WHERE email=:email OR name=:name');
            $stmt->execute([
                'email' => $email,
                'name' => $name,
            ]);

                while($user = $stmt ->fetch()){
                if(isset($user['id'])&&(!empty($user['id']))) {
                    $id = $user['id'];
                    break;
                } else {
                    unset($id);
                }
            }
            if (isset($id)&&(!empty($id))) {
                echo "Такой пользователь уже существует";
            } else {

                $sqlinsert = "INSERT INTO users (name, email,pass) VALUES (:name, :email, :pass)";
                $st = $pdo->prepare($sqlinsert);

                $st->bindValue(":name", $name);
                $st->bindValue(":email", $email);
                $st->bindValue(":pass", $pass);

                $row = $st->execute();

                if ($row > 0) {
                    echo "Вы зарегистрированы";
                    unset($row);
                }

            } // Проверка на существование пользователя в БД
        } // Проверка нажатия кнопки $_POST['register']
    } // Проверка токена $_POST['token']
    die();
    header('register.php');
}

/**
 * Функция авторизации пользователя
 * @param $pdo
 * @return void
 */
function loginUsers($pdo){
    if ($_POST['token'] == $_SESSION['lastToken'])
    {
        echo "";
    } else {
        $_SESSION['lastToken'] = $_POST['token'];

        if (isset($_POST['login'])) {
            if (isset($_POST['name']) && (!empty($_POST['name']))) {
                $name = shtml($_POST['name']);
                unset($_POST['name']);
            }
            if (isset($_POST['pass']) && (!empty($_POST['pass']))) {
                $pass= md5(shtml($_POST['pass']));
                unset($_POST['pass']);
            }

            $sql = "SELECT users.id,users.rols,users.name FROM users WHERE users.name = :name AND users.pass = :pass";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":pass", $pass);

            $stmt->execute();

            while($user = $stmt ->fetch()){
                if(isset($user['id'])&&(!empty($user['id']))){
                    $id= $user['id'];
                    $name= $user['name'];
                }
            }
            if (isset($id)&&(!empty($id))) {
                $_SESSION['id'] = $id;
                echo "Добро пожаловать, $name !";
                ?>
                <script>
                window.setTimeout(function(){
                    window.location.href = "account.php" + window.location.search.substring(1);
                }, 3000);
                </script>
                <?php
                die();
            } else {
                print "Нет такого пользователя";
                ?>
                <script>
                    window.setTimeout(function(){
                        window.location.href = "login.php" + window.location.search.substring(1);
                    }, 3000);
                </script>
                <?php
                die();
            }
        }
    }
}

/**
 * Получение имени пользователя
 * @param $pdo
 * @return mixed
 */
function getNameUser($pdo){
    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];
    }

    $sql = "SELECT users.name FROM users WHERE users.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id", $userid);

    $stmt->execute();

    while($user = $stmt ->fetch()) {
        if (isset($user['name']) && (!empty($user['name']))) {
            $name = $user['name'];
            break;
        }
    }

    return $name;
}

/**
 * Получение данных пользователя
 * @param $pdo
 * @param $userid
 * @return array
 */
function getDataUser($pdo,$userid){
    $sql = "SELECT * FROM users WHERE users.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id", $userid);
    $stmt->execute();

    while($user = $stmt ->fetch()){
        if(isset($user['id'])&&(!empty($user['id']))){
            $data = [
                'id'=> $user['id'],
                'name'=> $user['name'],
                'email'=> $user['email']
            ];

        }
    }

    return $data;
}

/**
 * Отправление товаров в корзину
 * @return void
 */
function sendProductToCart()
{
    if (isset($_POST['prodid']) && !empty($_POST['prodid'])) {
        $productid = $_POST['prodid'];
    }

    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $userid = $_SESSION['id'];
    }

    if ((isset($productid)) && (isset($userid))) {
        $data = [$userid=>$productid];
        $_SESSION['orders'][] = $data;

        echo "Товар добавлен в корзину";
    }
}

/**
 * Формирование заказа в таблицах orders и order_product
 * @param $pdo
 * @return void
 */
function sendOrders($pdo){
        $userid = $_SESSION['id'];
        if (isset($_SESSION['orders'])&&!empty($_SESSION['orders'])) {
            $session = $_SESSION['orders'];
        }
        $datauser = getDataUser($pdo,$userid);

        // Создаем в заказ
        $sqlinsert = "INSERT INTO orders (userid) VALUES (:userid)";
        $st = $pdo->prepare($sqlinsert);
        $st->bindValue(":userid", $userid);
        $st->execute();

        // Получаем id заказа
        $sth = $pdo->prepare("SELECT orders.id FROM orders WHERE orders.userid = ?");
        $sth->execute(array($userid));
        $orderid = $sth->fetch(PDO::FETCH_COLUMN);

        // Заполняем промежуточную таблицу order_product
        $sqlinsert = "INSERT INTO order_product (product_id,order_id) VALUES (:productid,:orderid)";
        foreach ($session as $value){
                foreach ($value as $user_id=>$productid){
                    if($user_id==$userid){
                        $st = $pdo->prepare($sqlinsert);
                        $st->bindValue(":productid",$productid );
                        $st->bindValue(":orderid", $orderid);
                        $st->execute();

                    }
                } // foreach

            } // foreach
}
?>