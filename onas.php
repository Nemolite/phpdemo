<?php include_once __DIR__ . '/header.php'; ?>

<div class="main">
    <div class="main-sidebar">
    </div>
    <div class="main-content">
        <h1>О нас</h1>
        <div class="slider-wrapper">
            <h2 class="onas-title">Новинки компании</h2>
            <div class="slider">
                <?php getSliderProducts5($pdo)?>

            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
