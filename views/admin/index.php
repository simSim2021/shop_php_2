<?php
include (ROOT . '/views/parts/header_admin.php');
?>

<div class="container_admin">
    <h4>Привет, администратор!</h4>

    <br/>

    <p>Вам доступны следующие функции:</p>

    <ul>
        <li><a  href="/admin/product">Управление товарами</a></li>
        <li><a  href="/admin/category">Управление категориями</a></li>
        <li><a  href="/admin/orders">Управление заказами</a></li>
    </ul>
</div>
<div class="appendix"></div>

<?php
include (ROOT . '/views/parts/footer_admin.php');
?>
