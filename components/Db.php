<?php

/**
 * Class Db инициализируем подключение к БД
 */
class Db {
    public static function getConnection(){
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include ($paramsPath);

        $dsn = "mysql:host={$params['host']};dbname={$params['db_name']}";
        $db = new PDO($dsn, $params['user'], $params['pass']);
        $db->exec("set names utf8");
        $db->query("SET time_zone = 'Europe/Berlin'");

        return $db;
    }
    static function query($sql, $params = [])
    {
        $stmt = self::connect()->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $val) $stmt->bindValue(':'.$key, $val);
        }

        $stmt->execute();
        return $stmt;
    }

    static function row($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static function rowAll($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }






	//Get row count
    public function rowCount(){
        return $this->stmt->rowCount();
    }


}
