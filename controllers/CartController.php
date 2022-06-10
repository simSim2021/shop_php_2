<?php

/**
 * Контроллер для работы с корзиной
 */

use PHPMailer\PHPMailer\PHPMailer;
//Require PHP Mailer
require_once (ROOT . '/PHPMailer/src/PHPMailer.php');
require_once (ROOT . '/PHPMailer/src/Exception.php');
require_once (ROOT . '/PHPMailer/src/SMTP.php');






class CartController {
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

    
     /* Добавление товара в корзину
     */
    public function actionAdd ($id){

        //Добавляем товар в корзину
        Cart::addProduct($id);

        //Возвращаем пользователя на страницу
        $referrer = $_SERVER['HTTP_REFERER'];

        header("Location: $referrer");

    }

    /**
     *  -//- AJAX
     *
     * @param $id
     * @return bool
     */
    public function actionAddAjax ($id){
        echo Cart::addProduct($id);
        return true;
    }

    /**
     * Главная страница корзины
     *
     * @return bool
     */
    public function actionIndex (){

        $categories = array();
        $categories = Category::getCategory();

        $productsInCart = false;

        //Получаем данные из корзины
        $productsInCart = Cart::getProducts();

        if($productsInCart){

            //Получаем полную информацию о товаре
            $productsId = array_keys($productsInCart);

            $products = Product::getProductsByIds($productsId);

            //Получаем общую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }

        require_once(ROOT . '/views/cart/index.php');

        return true;
    }

    /**
     * Удаление товара из корзины
     *
     * @param $id
     */
    public function actionDelete ($id){

        Cart::deleteProduct($id);
	$referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
    }

	//function redirect($page)
	//{
           //header('location: ' . URLROOT . '/' . $page);
	//}

    /**
     * Оформление заказа
     *
     * @return bool
     */
    public function actionCheckout (){

        //Получаем данные из корзины
        $productsInCart = Cart::getProducts();
        if($productsInCart == false){
            header('Location: /');
        }

        //Список категорий для сайдбара
        $categories = Category::getCategory();

        //Находим общую стоимость
        $productsIds = array_keys($productsInCart);
        $products = Product::getProductsByIds($productsIds);
        $totalPrice = Cart::getTotalPrice($products);

        //Кол-во товаров
        $totalQuantity = Cart::itemsCount();

        //Поля для формы
        $userName ='';
        $userPhone = '';
        $userText = '';

        //Статус успешного оформления заказа
        $res = false;

        //Проверяем, авторизован ли пользователь
        if(!User::isGuest()){
            //Если не гость, получаем данные о пользователе из БД
            $userId = User::checkLog();
            $user = User::getUserById($userId);
            $userName = $user['name'];
        }else{
            //Если гость, то поля формы будут пустыми
            //$userId = false;
	    //Если гость, то перенаправляем на страницу регистрации
	    //redirect ('views.user.register');
	      echo '<script type="text/javascript">
                    window.location = "/user/register"
                    </script>';


        }

        //Обработка формы
        if(isset($_POST) and !empty($_POST)){
            $userName = trim(strip_tags($_POST['name']));
            $userPhone = trim(strip_tags($_POST['tel']));
            $userText = trim(strip_tags($_POST['comment']));

            //Флаг ошибок
            $errors = false;

            //Валидация полей
            if (!User::checkName($userName)) {
                $errors[] = 'Имя не может быть короче 2-х символов';
            }

            if (!User::checkPhone($userPhone)) {
                $errors[] = 'Введите корректный номер';
            }

            if($errors == false){
                // Если ошибок нет
                // Сохраняем заказ в базе данных
                $res = Order::save($userName, $userPhone, $userText, $userId, $productsInCart);

                if ($res) {
                    // Если заказ успешно сохранен
                    // Оповещаем администратора о новом заказе по почте
                    $adminEmail = 'test1@test.com';
                    //$message = '<a href="http://shop.com/admin/orders">Список заказов</a>';
                    $message = 'New order has been placed. Time to check it!';
		    $subject = 'New order!';
                    //mail($adminEmail, $subject, $message);
		    $this->mail->setFrom('TheShopping@gmail.com');
                    $this->mail->isHTML(true);
                    $this->mail->Subject = $subject;
                    $this->mail->Body = $message;
                    $this->mail->addAddress($adminEmail);

                    $this->mail->send();




                    // Очищаем корзину
                    Cart::clear();
                }
            }
        }

        require_once(ROOT . '/views/cart/checkout.php');
        return true;
    }
}