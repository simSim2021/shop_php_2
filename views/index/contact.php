<?php
include (ROOT . '/views/parts/header.php');
?>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4 padding-right">
                    <?php if (isset($result) && $result): ?>
                        <p>Сообщение отправлено! Мы ответим Вам на указанный email.</p>
                    <?php else: ?>
                        <?php if (isset($errors) && is_array($errors)): ?>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li> - <?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif ?>

                        <div class="signup-form"><!--sign up form-->
                            <h2>Обратная связь</h2>
                            <h5>Есть вопрос? Напишите нам</h5>
                            <form action="#" method="post">
                                <p>Ваша почта</p>
                                <input type="email" name="userEmail" placeholder="Email" value="<?php echo $userEmail; ?>"/>
                                <p>Сообщение</p>
                                <input type="text" name="userText" placeholder="Сообщение" value="<?php echo $userText; ?>"/>
                                <br/>
                                <input type="submit" name="submit" class="btn btn-default" value="Отправить">
                            </form>
                        </div><!--/sign up form-->
                    <?php endif; ?>
                    <br/>
                    <br/>
                </div>

            </div>
        </div>
    </section><!--/form-->

<?php
include (ROOT . '/views/parts/footer.php');
?>
