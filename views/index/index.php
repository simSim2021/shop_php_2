<?php
include (ROOT . '/views/parts/header.php');
?>

    <section>
    <div class="container">
        <!--left sidebar-->
        <div class="sidebar">
            <h4>Дополнительная информация</h4>
            <ul class="left_sidebar">
                
                <li><a href="/index/about">О нас </a></li>                     
                <li><a href="/index/contact">Контакты</a></li> 
                
            </ul>
        </div>
        <!--main content-->
        <div class="content">
            <div class="features_items">
                <h2>Наши новые поступления</h2>
                <!--single item-->
                <?php foreach($latestProducts as $singleItem): ?>
                <div class="item">
                    <?php
                    if($singleItem['is_new'])
                        echo "<img alt='' src='template/images/new.png' class='new'/>";
                    ?>
                    <a target="_blank" href="/product/<?php echo $singleItem['id']?>">
                    <img width="268px" height="249px" alt="" src="<?php echo Product::getImage($singleItem['id']); ?>" />
                    </a>
                    <p class="item_price"><?php echo $singleItem['price'] ?> бел.руб.</p>
                    <a target="_blank" href="/product/<?php echo $singleItem['id']?>">
                        <p class="item_name"><?php echo $singleItem['name']?></p>
                    </a>
                    <a href="#" class="glow-button" data-id="<?php echo $singleItem['id'];?>">
                        В корзину
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<div class="appendix"></div>

<?php
include (ROOT . '/views/parts/footer.php');
?>
