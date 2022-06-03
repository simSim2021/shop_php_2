<?php

/**
 * Модель для работы с пользователями
 */
class User {

    /**
     * Если в контроллере все ОК, принимаем данные и записываем в БД
     *
     * @param $name имя
     * @param $email email
     * @param $password пароль
     * @return bool  возвращает true/false
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

    /**
     * Проверяем поле Имя на корректность
     *
     * @param $name
     * @return bool
     */
    public static function checkName (string $name) {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем поле Телефон на корректность
     *
     * @param $phone
     * @return bool
     */
    public static function checkPhone (string $phone) {
        if (strlen($phone) > 9) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем поле Пароль на корректность
     *
     * @param $password
     * @return bool
     */
    public static function checkPassword (string $password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем поле Email на корректность
     *
     * @param $email
     * @return bool
     */
    public static function checkEmail (string $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверем email на доступность
     *
     * @param $email
     * @return bool
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

    /**
     * Проверка на существовние введенных данных при авторизации
     *
     * @param $email
     * @param $password
     * @return bool
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

    /**
     *Запись пользователя в сессию
     *
     * @param $userId
     */
    public static function auth (int $userId) {

        $_SESSION['user'] = $userId;
    }

    /**
     * Проверяем, авторизован ли пользователь при переходе в личный кабинет
     *
     * @return mixed
     */
    public static function checkLog () {

        //Если сессия есть, то возвращаем id пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        header('Location: user/login');
	exit();
    }

    /**
     * Проверяем наличие открытой сессии у пользователя для
     * отображения на сайте необходимой информации
     *
     * @return bool
     */
    public static function isGuest () {

        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Вытягиваем информацию о пользователе по id
     *
     * @param $userId
     * @return mixed
     */
    public static function getUserById (int $userId) {

	if ($userId) {
            $db = Db::getConnection();

            $sql = 'SELECT * FROM user WHERE id = :userid';

            $res = $db->prepare($sql);

            $res->bindParam(':userid', $userId, PDO::PARAM_INT);

            $res->execute();

        return $res->fetch(PDO::FETCH_ASSOC);
            }
	
	return false;
    }



    /**
     * редактируем информацию из личного кабинета
     *
     * @param $userId
     * @param $new_name
     * @param $new_email
     * @return bool
     */
    public static function edit (int $userId, string $name, string $email){
	
	
        $db = Db::getConnection();

        $sql = 'UPDATE user SET name = :name, email = :email WHERE id = :id';

        $res = $db->prepare($sql);

        $res->bindParam(':name', $name, PDO::PARAM_STR);
        $res->bindParam(':email', $email, PDO::PARAM_STR);
        $res->bindParam(':id', $userId, PDO::PARAM_INT);

        return $res->execute();
    }

}