<?php

/**
 * Модель для работы с контактной формой
 */
class Contact {

    /**
     * Сохранение запроса пользователя в БД
     *
     * @param $user_name
     * @param $user_email
     * @param $subject
     * @param $content
     * @param $contact_id
     * @return bool
     */
    public static function save ($userName, $userEmail, $subject, $userText) {
        $db = Db::getConnection();

        
        $sql = "
                INSERT INTO contact(user_name, user_email, subject, content)
                VALUES (:user_name, :user_email, :subject, :content)
                ";

        $res = $db->prepare($sql);

        $res->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $res->bindParam(':user_email', $userEmail, PDO::PARAM_STR);
        $res->bindParam(':subject', $subject, PDO::PARAM_STR);
        $res->bindParam(':content', $userText, PDO::PARAM_STR);
        

        return $res->execute();
    }
}