<?php

/**
 * Class IndexController
 * Контроллер главной страницы
 */
class IndexController {

    public function actionIndex () {

        //Список категорий
        $categories = Category::getCategory();

        //Последние продукты
        $latestProducts = Product::getLatestProducts();

        require_once(ROOT . '/views/index/index.php');

        return true;
    }

 public function actionContact() {

	   $userEmail = '';
           $userText = '';
           $result = false;

        if (isset($_POST['submit'])) {

            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];
                       
            $errors = false;

            if (!User::checkEmail($userEmail)) {
                $errors[] = 'Email указан некорректно';
            }

            if ($errors === false) {
	        $adminEmail = 'yul1579@gmail.com';
                $message = "Текст: {$userText}. От {$userEmail}";
                $subject = 'Обратная связь';
                $result = mail($adminEmail, $subject, $message);
               
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
