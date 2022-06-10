<?php

//класс ResetPasswordsController для работы с формой 

use PHPMailer\PHPMailer\PHPMailer;
//Require PHP Mailer
require_once (ROOT . '/PHPMailer/src/PHPMailer.php');
require_once (ROOT . '/PHPMailer/src/Exception.php');
require_once (ROOT . '/PHPMailer/src/SMTP.php');




class PwdResetController{

public function __construct(){

		//$this->resetModel = new ResetPassword;
        	//$this->userModel = new User;
		
		//Setup PHPMailer
        	$this->mail = new PHPMailer();
        	$this->mail->isSMTP();
       		$this->mail->Host = 'smtp.mailtrap.io';
        	$this->mail->SMTPAuth = true;
        	$this->mail->Port = 2525;
        	$this->mail->Username = '086d285e641fb5';
        	$this->mail->Password = '09c3eb38c18237';
    }


    public function actionSendEmail(){
 
//Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

	$res = false;
        $email = '';

if (isset($_POST['submit']) and (!empty($_POST))) {
            
            $email = trim(strip_tags($_POST['email']));
            
            //Флаг ошибок
            $errors = false;

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errors[] = "Некорректный Email";

	//Возвращаем пользователя на страницу
       		$referrer = $_SERVER['HTTP_REFERER'];

        header("Location: $referrer");

            }

	if(empty($email)){
		$errors[] = "Пожалуйста, укажите Email";

	//Возвращаем пользователя на страницу
       		$referrer = $_SERVER['HTTP_REFERER'];

        header("Location: $referrer");
	    }

if ($errors == false) {

	//Will be used to query the user from the database
        $selector = bin2hex(random_bytes(8));

        //Will be used for confirmation once the database entry has been matched
        $token = random_bytes(32);

        //URL will vary depending on where the website is being hosted from
        $url = 'http://localhost/views/user/createNewPassword.php?selector='.$selector.'&validator='.bin2hex($token);
        
	//Expiration date will last for half an hour
        $expires = date("U") + 1800;

	if(!(new ResetPassword)->deleteEmail($email)){
            $errors[] = "There was an error";
        }
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        if(!(new ResetPassword)->insertToken($email, $selector, $hashedToken, $expires)){
            $errors[] = "There was an error";
        }
        //Can Send Email Now
        $subject = "Reset your password";
        $message = "<p>We recieved a password reset request.</p>";
        $message .= "<p>Here is your password reset link: </p>";
        $message .= "<a href='".$url."'>".$url."</a>";

        $this->mail->setFrom('TheShopping@gmail.com');
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->addAddress($email);

        $this->mail->send();
}
 require_once(ROOT . 'views/resetPassword/resetPassword.php');
return true;
}
}
}
