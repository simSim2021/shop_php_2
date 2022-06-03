<?php
include(ROOT . '/views/parts/header.php');
?>
<section>
    <div class="container">
        <h2>Личный кабинет</h2>
        <h4 id="cabinet_greeting">Привет, <?php echo $user['name']; ?></h4>
        <ul id="cabinet_list">
           <li><a  href="/cabinet/edit">Редактировать персональные данные</a></li>
           <li><a  href="/cabinet/orders">Список покупок</a></li>
	    <?php if ($user['role'] === 'admin'): ?>
	   <li><a target="_blank" href="/admin/index">Администрирование сайта</a></li>
           <?php endif; ?>
        </ul>
    </div>
</section>
<div class="appendix"></div>
<?php
include(ROOT . '/views/parts/footer.php');
?>
