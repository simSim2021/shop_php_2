<?php

//Модель
use PHPMailer\PHPMailer\PHPMailer;
//Require PHP Mailer
require_once (ROOT . '/PHPMailer/src/PHPMailer.php');
require_once (ROOT . '/PHPMailer/src/Exception.php');
require_once (ROOT . '/PHPMailer/src/SMTP.php');

class User {

	public function __construct(){
			
		//Setup PHPMailer
        	$this->mail = new PHPMailer();
        	$this->mail->isSMTP();
       		$this->mail->Host = 'smtp.mailtrap.io';
        	$this->mail->SMTPAuth = true;
        	$this->mail->Port = 2525;
        	$this->mail->Username = '086d285e641fb5';
        	$this->mail->Password = '09c3eb38c18237';
    }

    /*
     * Если в контроллере все ОК, принимаем данные и записываем в БД
     */
     public static function register ($name, $email, $password) {

        $db = Db::getConnection();

        $sql = "
                INSERT INTO user(name, email, password)
                VALUES(:name, :email, :password)
                ";

        $res = $db->prepare($sql);
        $res->bindParam(':name', $name, PDO::PARAM_STR);
        $res->bindParam(':email', $email, PDO::PARAM_STR);
        $res->bindParam(':password', $password, PDO::PARAM_STR);

        return $res->execute();
    }

    /*
     * Проверяем поле Имя на корректность
     */
    public static function checkName (string $name) {
        if ((strlen($name) >= 2) and (preg_match("/^[a-zA-Z0-9]*$/", $name))) {
            return true;
        }
        return false;
    }

    /*
     * Проверяем поле Телефон на корректность
     */
    public static function checkPhone (string $phone) {
        if (strlen($phone) > 9) {
            return true;
        }
        return false;
    }

    
     /* Проверяем поле Пароль и Повторить пароль на корректность
     */
    public static function checkPassword (string $password, string $passwordRepeat) {
        if ((strlen($password) >= 6) and ($password == $passwordRepeat)) {
            return true;
        }
        return false;
    }

    /*
     * Проверяем поле Email на корректность
     */
    public static function checkEmail (string $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /*
     * Проверем email на доступность
     */
    public static function checkEmailExists (string $email) {

        $db = Db::getConnection();
        $sql = 'SELECT count(*) FROM user WHERE email = :email';

        $res = $db->prepare($sql);
        $res->bindParam(':email', $email, PDO::PARAM_STR);
        $res->execute();

        if ($res->fetchColumn()) {
            return true;
	}

        return false;
    }

    /*
     * Проверка на существовние введенных данных при авторизации
     */
    public static function checkUserData (string $email, string $password) {

        $db = Db::getConnection();

       $sql = "
                SELECT id, name, email, password, role
                FROM user
                WHERE email = :email
                ";

        $res = $db->prepare($sql);

        $res->bindParam(':email', $email, PDO::PARAM_STR);
	
        $res->execute();

        $user = $res->fetch(PDO::FETCH_ASSOC);

	if (password_verify($password, $user['password'])) {
            return $user['id'];
        }
	
        return false;
    }

    /*
     *Запись пользователя в сессию
     */
    public static function auth (int $userId) {

        $_SESSION['user'] = $userId;
    }

    /*
     * Проверяем, авторизован ли пользователь при переходе в личный кабинет
     */
    public static function checkLog () {

        //Если сессия есть, то возвращаем id пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        header('Location: user/login');
	exit();
    }

    /*
     * Проверяем наличие открытой сессии у пользователя для
     * отображения на сайте необходимой информации
     */
    public static function isGuest () {

        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /*
     * Вытягиваем информацию о пользователе по id
     */
    public static function getUserById (int $userId) {

	if ($userId) {
            $db = Db::getConnection();

            $sql = 'SELECT * FROM user WHERE id = :id';

            $res = $db->prepare($sql);

            $res->bindParam(':id', $userId, PDO::PARAM_INT);

            $res->execute();

        return $res->fetch(PDO::FETCH_ASSOC);
            }
	
	return false;
    }

    /*
     * Вытягиваем информацию о пользователе по name
     */
    public static function getUserByName (string $name) {

	if ($name) {
            $db = Db::getConnection();

            $sql = 'SELECT * FROM user WHERE name = :name';

            $res = $db->prepare($sql);

            $res->bindParam(':name', $name, PDO::PARAM_STR);

            $res->execute();

        return $res->fetch(PDO::FETCH_ASSOC);
            }
	
	return false;
    }

    
/*
     * Вытягиваем информацию о пользователе по email
     */
    public static function findUserByEmail (string $email) {

	if ($email) {
            $db = Db::getConnection();

            $sql = 'SELECT * FROM user WHERE email = :email';

            $res = $db->prepare($sql);

            $res->bindParam(':email', $email, PDO::PARAM_STR);

            $res->execute();

        return $res->fetch(PDO::FETCH_ASSOC);
            }
	
	return false;
    }


    /*
     * редактируем информацию из личного кабинета
     */
    public static function edit (int $id, string $name, string $email){
	
	
        $db = Db::getConnection();

        $sql = 'UPDATE user SET name = :name, email = :email WHERE id = :id';

        $res = $db->prepare($sql);

        $res->bindParam(':name', $name, PDO::PARAM_STR);
        $res->bindParam(':email', $email, PDO::PARAM_STR);
        $res->bindParam(':id', $id, PDO::PARAM_INT);

        return $res->execute();
    }

	/*
	 *изменить пароль
	 */
	//public function reset ($newPwdHash, $tokenEmail){
        

	//$db = Db::getConnection();

	//$sql = 'UPDATE user SET password = :password WHERE email = :email';
        
	//$res = $db->prepare($sql);
	//$res->bindParam(':password', $newPwdHash, PDO::PARAM_STR);
        //$res->bindParam(':email', $tokenEmail, PDO::PARAM_STR);

	//Execute
        //if($res->execute()){
            //return true;
        //}else{
            //return false;
        //}
    //}


public static function checkPwdForReset(){
	$stmt = Db::row("SELECT * FROM `user` WHERE `remember_token` = :token", ['token' => $_GET['token']]);
        if ($stmt) {
            $time = $stmt['time_token'] + 60*60;

            if ($time > time()) {
                return $stmt;
            } else {
                Db::query("UPDATE `user` SET `remember_token` = null, `time_token` = null WHERE `remember_token` = :token", ['token' => $_GET['token']]);
                return false;
            }
        }

        return false;
     }

public static function updatePwdForReset()
    {
        $stmt = Db::query("UPDATE `user` SET `password` = :password, `remember_token` = null, `time_token` = null WHERE `remember_token` = :token", [
            'token'    => $_GET['token'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);
        return $stmt->rowCount();
    }

public static function createToken()
    {
        $stmt = Db::row("SELECT * FROM `user` WHERE `email` = :useremail", ['useremail' => $_POST['email']]);
        if ($stmt) {
            $token = bin2hex(random_bytes(50));
            $time  = time();

            $stmt = Db::query("UPDATE `user` SET `remember_token` = '{$token}', `time_token` = '{$time}' WHERE `email` = :useremail", ['useremail' => $_POST['email']]);
            return $token;
        }

        return false;
    }

//public static function mailPassword($token){
	
	//$path = "http://{$_SERVER['HTTP_HOST']}";
	
	//$subject = "Reset your password";
        //$message = "<p>We recieved a password reset request.</p>";
	//$message = "<p>Token valid 1 hour.<p>";
        //$message = "<br><p>Here is your password reset link: </p>";
        //$message = "<a href=\"' . $path . '/reset?token=' . $token . '\">$path . '/reset?token=' . $token . '</a>'";

        //$this->mail->setFrom('TheShopping@gmail.com');
        //$this->mail->isHTML(true);
        //$this->mail->Subject = $subject;
        //$this->mail->Body = $message;
        //$this->mail->addAddress($email);

        //$this->mail->send();
	//$result = true;
	//$_SESSION['success'][] = 'The letter was sent. Check your Email';

	//}

}