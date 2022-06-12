<?php
include (ROOT . '/views/parts/header.php');
include_once (ROOT . '/components/SessionHelper.php');
?>

<section>
    <div class="container">

            <?php if (isset($errors) && is_array($errors)):?>
                <ul class="errors">
                    <?php foreach($errors as $error):?>
                        <li> - <?php echo $error;?></li>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>

	    //<?php SessionHelper::flash('reset') ?>

            <form action="#" method="post" id="enter_form">
                <h2>Изменить пароль</h2>
                <input type="hidden" name="type" value ="send"/>
                <input required type="email" name="email" placeholder="Введите email">
                <input type=submit name="submit" value="Получить email" id="enter_btn">
            </form>
    </div>
</section>
<div class="appendix"></div>
<?php
include (ROOT . '/views/parts/footer.php');
?>
