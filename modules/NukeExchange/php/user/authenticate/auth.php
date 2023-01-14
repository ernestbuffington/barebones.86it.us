<?php
/**
PHP needs: user_email, user_password
user_email: user_email of users
user_password: plaintext of the passowrd
**/

require_once("././getter.php");

$data = [];

$user_email = $_POST["user_email"];
$user_password = $_POST["user_password"];

$bindings = [];

$bindings["BINDING_TYPES"] = "s";
$bindings["VALUES"] = array($user_email);

if(!empty($user)) {
    $user = getData("user/authenticate/sql.txt", $bindings)[0];

    session_start();

    if(!empty($user)){
        //$encrypted_pass = sha1($user_password);

        if(password_verify($user_password, $user["PASSWORD"])){

            $_SESSION["ACCID"] = $user["user_id"];
            $data = array("RESULT" => "1", "MESSAGE" => "Successfully logged in", "user_id" => $user["user_id"]);
        }else{
           $data = array("RESULT"=>"2", "MESSAGE" => "Password is incorrect");
        }

    } else {
        $data = array("RESULT"=>"2", "MESSAGE" => "Email does not exist");
    }
} else {
    $data = array("RESULT"=>"2", "MESSAGE" => "Email does not exist");
}
