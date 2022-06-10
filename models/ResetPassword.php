<?php

class ResetPassword{

    
    public function deleteEmail($email){

	$db = Db::getConnection();


        $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = :email";
	$res = $db->prepare($sql);
        $res->bindParam(':email',$email);
        //Execute
        if($res->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function insertToken($email, $selector, $hashedToken, $expires){
        
	$db = Db::getConnection();
	
	$sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, 
        pwdResetExpires) VALUES (:email, :selector, :token, :expires)";

	$res = $db->prepare($sql);

        $res->bindParam(':email', $email, PDO::PARAM_STR);
        $res->bindParam(':selector', $selector, PDO::PARAM_STR);
        $res->bindParam(':token', $hashedToken, PDO::PARAM_STR);
        $res->bindParam(':expires', $expires, PDO::PARAM_STR);
        //Execute
        if($res->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function reset($selector, $currentDate){

	$db = Db::getConnection();

        $sql = "SELECT * FROM pwdreset WHERE  pwdResetSelector=:selector AND pwdResetExpires >= :currentDate";
        
	$res = $db->prepare($sql);
	
	$res->bindParam(':selector',$selector, PDO::PARAM_STR);
        $res->bindParam(':currentDate',$currentDate, PDO::PARAM_STR);
        //Execute
	$res->execute();
        $row = $res->fetch(PDO::FETCH_OBJ);

	$dbrow = Db::rowCount();
        //Check row
        if($dbrow > 0){
            return $row;
        }else{
            return false;
        }
    }
}
