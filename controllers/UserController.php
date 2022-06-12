<?php

/**
 * Class UserController для работы с пользователем
 */

class UserController {

    /*
     * Регистрация пользователя
     */
    public function actionRegister () {

	//Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $res = false;
        $name = '';
        $email = '';
        $password = '';
	$passwordRepeat = '';

        if (isset($_POST) and (!empty($_POST))) {
            $name = trim(strip_tags($_POST['name']));
            $email = trim(strip_tags($_POST['email']));
            $password = trim(strip_tags($_POST['password']));
	    $passwordRepeat = trim(strip_tags($_POST['passwordRepeat']));
            //Флаг ошибок
            $errors = false;

            //Валидация полей
            if (!User::checkName($name)) {
                $errors[] = "Имя не может быть короче 2-х символов";
            }

            if (!User::checkEmail($email)) {
                $errors[] = "Некорректный Email";
            }

            if (!User::checkPassword($password, $passwordRepeat)) {
                $errors[] = "Пароль не может быть короче 6-ти символов. Дважды введенный пароль должен совпадать.";
            }

            if (User::checkEmailExists($email)) {
                $errors[] = "Такой email уже используется";
            }

            if ($errors == false) {
                $res = User::register($name, $email, password_hash($password, PASSWORD_DEFAULT));
            }
        }

        require_once(ROOT . '/views/user/register.php');

        return true;
    }

    /**
     * Авторизация пользователя
     *
     * @return bool
     */
    public function actionLogin () {
        ob_start();

        $email = '';
        $password = '';

        if (isset($_POST) and (!empty($_POST))) {

            $email = trim(strip_tags($_POST['email']));
            $password = $_POST['password'];

            //Флаг ошибок
            $errors = false;

            //Валидация полей
            if (!User::checkEmail($email)) {
                $errors[] = "Некорректный Email";
            }

            //Проверяем, существует ли пользователь
            $userId = User::checkUserData($email, $password);

            if ($userId == false) {
                $errors[] = "Пользователя с таким email или паролем не существует";
            }else{
                User::auth($userId); //записываем пользователя в сессию

                header("Location: /cabinet/"); //перенаправляем в личный кабинет
            }
        }

        require_once(ROOT . '/views/user/login.php');

        return true;
    }

    /**
     * Выход из учетной записи
     *
     * @return bool
     */
    public function actionLogout () {

        unset($_SESSION['user']);
        header('Location: /');

        return true;
    }
	public function actionPassword()
    {
        if (!empty($_POST)) {
            $this->validate([
                'data' => 'required|min:2'
            ]);
            if ($token = User::createToken()) {
                User::mailPassword($token);
            } else {
                $_SESSION['error'][] = "Email or Username aren't found";
            }
	header('Location: /password/');
            //redirect();
        }
	require_once(ROOT . '/views/user/password.php');

        return true;
}
        

public function actionReset()
    {
        if (isset($_GET['token']) AND !empty($_GET['token'])) {
            if (User::checkPwdForReset()) {
                if (!empty($_POST)) {
                    if ($_POST['password'] == $_POST['password-repeat']) {
                        User::updatePwdForReset();

                        $_SESSION['success'][] = 'The password updated successfully!';
                       header('Location: /login/');
			 //redirect('login');
                    } else {
                        $_SESSION['error'][] = "The passwords don't match";
                       header('Location: /reset/'); 
			//redirect();
                    }
                }
            } else header('Location: /'); //redirect('/');
        } else header('Location: /'); //redirect('/');
}
}
