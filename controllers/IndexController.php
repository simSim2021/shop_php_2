<?php

/*
 * Контроллер главной страницы
 */

use PHPMailer\PHPMailer\PHPMailer;
//Require PHP Mailer
require_once (ROOT . '/PHPMailer/src/PHPMailer.php');
require_once (ROOT . '/PHPMailer/src/Exception.php');
require_once (ROOT . '/PHPMailer/src/SMTP.php');


class IndexController {

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

    public function actionIndex () {

        //Список категорий
        $categories = Category::getCategory();

        //Последние продукты
        $latestProducts = Product::getLatestProducts();

        require_once(ROOT . '/views/index/index.php');

        return true;
    }

 public function actionContact() {

	   $userName = '';
	   $userEmail = '';
           $userText = '';
           $result = false;

        if (isset($_POST['submit'])) {

            $userName = $_POST['userName'];
	    $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];
            $subject =  'Contuct us';          
            $errors = false;

            if (!User::checkEmail($userEmail)) {
                $errors[] = 'Email указан некорректно';
            }

            if ($errors === false) {

		$res = Contact::save($userName, $userEmail, $subject, $userText);
	        }

	    if ($res) {
		$adminEmail = 'test1@test.com';
                $message = "Text: {$userText}. From: {$userEmail}. UserName: {$userName}";
                $subject = 'Contuct us';
                //$result = mail($adminEmail, $subject, $message);
                //$result = true;
 		$this->mail->setFrom('TheShopping@gmail.com');
                $this->mail->isHTML(true);
                $this->mail->Subject = $subject;
                $this->mail->Body = $message;
                $this->mail->addAddress($adminEmail);

                $this->mail->send();
		$result = true;
            }


	}

		require_once (ROOT . '/views/index/contact.php');
        	return true;
    }

	public function actionAbout(){
		// Подключаем вид
        	require_once(ROOT . '/views/index/about.php');
        	return true;
		}


    
}
