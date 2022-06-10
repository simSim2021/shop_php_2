<?php 
    if(empty($_GET['selector']) || empty($_GET['validator'])){
        echo 'Не удалось подтвердить ваш запрос!';
    }else{
        $selector = $_GET['selector'];
        $validator = $_GET['validator'];
        
        if(ctype_xdigit($selector) && ctype_xdigit($validator)) { ?>

<?php
include (ROOT . '/views/parts/header.php');
include (ROOT . '/components/SessionHelper.php');
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

	    <?php SessionHelper::flash('reset') ?>

            <form action="#" method="post" id="enter_form">
                <h2>Введите новый пароль</h2>
                <input type="hidden" name="type" value ="reset"/>
		<input type="hidden" name="selector" value="<?php echo $selector ?>" />
        	<input type="hidden" name="validator" value="<?php echo $validator ?>" />
                <input type="password" name="password" placeholder="Введите новый пароль">
		<input type="password" name="password-repeat" placeholder="Повторите новый пароль">
                <input type=submit name="submit" value="Получить email" id="enter_btn">
            </form>
    </div>
</section>
<div class="appendix"></div>
<?php
include (ROOT . '/views/parts/footer.php');
?>
<?php 
    }else{
        echo 'Не удалось подтвердить ваш запрос!';
    }
}
?>