<?php
include (ROOT . '/views/parts/header.php');
?>

<section>
    <div class="container">

            <?php if($res):?>
                <h4 id="edit_thanks">Данные успешно изменены!</h4>
                <h3 id="to_cabinet">Вернуться в <a href="/cabinet" id="reg_main_a">Кабинет</a></h3>
            <?php else: ?>

            <?php if (isset($errors) && is_array($errors)):?>
                <ul class="errors">
                    <?php foreach($errors as $error):?>
                        <li> - <?php echo $error;?></li>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>

            <form action="#" method="post" id="edit_form">
                <h2>Изменить данные</h2>
                <p>Имя</p>
                <input required type="text" name="name" placeholder="Введите новое имя">
                <p>E-mail</p>
                <input required type="email" name="email" placeholder="Введите новый email">
                <input type=submit name="submit" value="Сохранить" id="save_btn">
            </form>
        <?php endif;?>
    </div>
</section>
<div class="appendix"></div>
<?php
include (ROOT . '/views/parts/footer.php');
?>
