<?php
include (ROOT . '/views/parts/header.php');
?>
<section>
    <div class="container">
        <!--left sidebar-->
        <div class="sidebar">
            <h2>Категории</h2>
            <ul class="left_sidebar">
                <?php foreach ($categories as $catItem): ?>
                    <li><a href="/category/<?php echo $catItem['id']?>">
                            <?php echo $catItem['name']?>
                        </a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!--main content-->
        <div class="content">
            <?php if($productsInCart):?>
                <h2 style="color:#C71585">Корзина</h2></br>
                <p>Вы выбрали следующие товары:</p></br>

                <table id="cart_products"cellspacing="0">
                    <tr>
                        <th>Код товара</th>
                        <th>Название</th>
                        <th>Цена, бел.руб.</th>
                        <th>Кол-во, шт.</th>
                        <th>Удалить</th>
                    </tr>

                    <?php foreach($products as $product):?>
                    <tr>
                        <td style="background: #FFC0CB"><?php echo $product['code'];?></td>
                        <td style="background: #FFC0CB"><a target="_blank" href="/product/<?php echo $product['id'];?>" class="cart_item">
                                <?php echo $product['name'];?>
                        </a></td>
                        <td style="background: #FFC0CB"><?php echo $product['price']?></td>
                        <td style="background: #FFC0CB"><?php echo $productsInCart[$product['id']];?></td>
                        <td style="background: #FFC0CB"><a href="/cart/delete/<?php echo $product['id'];?>" class="del">
                            <img src="../../template/images/del.png">
                        </a></td>
                    </tr>
                    <?php endforeach;?>
                    <tr id="total_sum">
                        <td style="background: #CFC7B0">Общая стоимость, бел.руб.: </td>
                        <td style="background: #CFC7B0"></td>
                        <td style="background: #CFC7B0"></td>
                        <td style="background: #CFC7B0"></td>
                        <td style="background: #CFC7B0"><?php echo $totalPrice;?></td>
                    </tr>
                </table>

                <a href="/cart/checkout" class="checkout" style="background: #C71585">
                    Оформить заказ
                </a>

                <?php else:?>
                    <h2 id="empty_cart" style="color:#C71585">Ваша корзина пуста</h2></br></br></br></br></br>
                    <a href="/" id="empty_cart_to_main">Продолжить покупки</a>
            <?php endif;?>
        </div>
    </div>
</section>
<div class="appendix"></div>
<?php
include (ROOT . '/views/parts/footer.php');
?>
