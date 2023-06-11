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

function setAdminCategory($pdo) {
    if ($_POST['tokenadmincategory'] == $_SESSION['lasttokenadmincategory'])
    {
        echo "";
    } else {
        $_SESSION['tokenadmincategory'] = $_POST['tokenadmincategory'];

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
?>