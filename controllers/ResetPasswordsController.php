<?php

//класс ResetPasswordsController для работы с формой 

use PHPMailer\PHPMailer\PHPMailer;
//Require PHP Mailer
require_once (ROOT . '/PHPMailer/src/PHPMailer.php');
require_once (ROOT . '/PHPMailer/src/Exception.php');
require_once (ROOT . '/PHPMailer/src/SMTP.php');




class ResetPasswordsController{
	
	private $resetModel;
    	private $userModel;
    	private $mail;
    
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


    public function actionSendEmail (){

        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $email = trim($_POST['email']);

        if(empty($email)){
            (new SessionHelper)->flash("reset", "Please input email");
            (new SessionHelper)->redirect("../resetPassword.php");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            (new SessionHelper)->flash("reset", "Invalid email");
            (new SessionHelper)->redirect("../resetPassword.php");
        }

        //Will be used to query the user from the database
        $selector = bin2hex(random_bytes(8));

        //Will be used for confirmation once the database entry has been matched
        $token = random_bytes(32);

        //URL will vary depending on where the website is being hosted from
        $url = 'http://localhost/views/user/createNewPassword.php?selector='.$selector.'&validator='.bin2hex($token);
        
	//Expiration date will last for half an hour
        $expires = date("U") + 1800;

        if(!$this->resetModel->deleteEmail($email)){
            die("There was an error");
        }
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        if(!$this->resetModel->insertToken($email, $selector, $hashedToken, $expires)){
            die("There was an error");
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

        (new SessionHelper)->flash("reset", "Check your email", 'form-message form-message-green');
        (new SessionHelper)->redirect("../resetPassword.php");
    }

	//require_once(ROOT . '/views/user/resertPassword.php');

	//return true;
   //}

    public function actionReset(){

        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'selector' => trim($_POST['selector']),
            'validator' => trim($_POST['validator']),
            'password' => trim($_POST['password']),
            'password-repeat' => trim($_POST['password-repeat'])
        ];
        $url = '../createNewPassword.php?selector='.$data['selector'].'&validator='.$data['validator'];

        if(empty($_POST['password'] || $_POST['password-repeat'])){
            (new SessionHelper)->flash("newReset", "Please fill out all fields");
            (new SessionHelper)->redirect($url);
        }else if($data['password'] != $data['password-repeat']){
            SessionHelper::flash("newReset", "Passwords do not match");
            SessionHelper::redirect($url);
        }else if(strlen($data['password']) < 6){
            (new SessionHelper)->flash("newReset", "Invalid password");
            (new SessionHelper)->redirect($url);
        }

        $currentDate = date("U");

        if(!$row = $this->resetModel->reset($data['selector'], $currentDate)){
            (new SessionHelper)->flash("newReset", "Sorry. The link is no longer valid");
            (new SessionHelper)->redirect($url);
        }

        $tokenBin = hex2bin($data['validator']);
        $tokenCheck = password_verify($tokenBin, $row->pwdResetToken);

        if(!$tokenCheck){
            (new SessionHelper)->flash("newReset", "You need to re-Submit your reset request");
            (new SessionHelper)->redirect($url);
        }

        $tokenEmail = $row->pwdResetEmail;

        if(!User::findUserByEmail($tokenEmail)){
            (new SessionHelper)->flash("newReset", "There was an error");
            (new SessionHelper)->redirect($url);
        }

        $newPwdHash = password_hash($data['pwd'], PASSWORD_DEFAULT);

        if(!$this->userModel->reset($newPwdHash, $tokenEmail)){
            (new SessionHelper)->flash("newReset", "There was an error");
            (new SessionHelper)->redirect($url);
        }

        if(!$this->resetModel->deleteEmail($tokenEmail)){
            (new SessionHelper)->flash("newReset", "There was an error");
            (new SessionHelper)->redirect($url);
        }

        (new SessionHelper)->flash("newReset", "Password Updated", 'form-message form-message-green');
        (new SessionHelper)->redirect($url);
    }

	//require_once(ROOT . '/views/user/createNewPassword.php');

	//return true;
 }

//$init = new ResetPasswords;

//Ensure that user is sending a post request
//if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //switch($_POST['type']){
        //case 'send':
            //$init->sendEmail();
            //break;
        //case 'reset':
            //$init->resetPassword();
            //break;
        //default:
        //header("Location: /");
    //}
//}else{
    //header("Location: /");
//}

