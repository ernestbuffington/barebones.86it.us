<?php
require_once("././inserter.php");

/**
PHP needs: user_email, user_password, username
user_email: user_email of users
user_password: plaintext of the passowrd
username: inputted username
**/

$data = [];
if(isset($_POST["user_email"]) &&
      isset($_POST["user_password"]) &&
      isset($_POST["username"])){

   //if(strcmp($user_email,$_POST["user_email-conf"]) === 0 && strcmp($pass,$_POST["pass-conf"]) === 0) {

      $user_email = $_POST["user_email"];
      $user_password = $_POST["user_password"];
      $username = $_POST["username"];
      $user_avatar = '';//$_POST["image"];
      $encrypted_pass = password_hash($user_password, PASSWORD_DEFAULT);

      $bindings = [];

      $bindings["BINDING_TYPES"] = "ssss";
      $bindings["VALUES"] = array(
                                      $username,
                                      $user_email,
                                      $encrypted_pass,
                                     $user_avatar
                              );



      $result = insertData("user/add/sql.txt", $bindings);



      if ($result["RESULT"] === 1) {
         session_start();
        $_SESSION["ACCID"] = $result["LAST_INSERTED_ID"];
        $data = array("RESULT"=> 1, "MESSAGE" => "Account was successfully created!", "user_id" => $result["LAST_INSERTED_ID"]);
      } else {
        if (strpos($result["MESSAGE"], 'Duplicate entry') !== false) {
            $data = array("RESULT"=> 2, "MESSAGE" => "Account was not created. Duplicate user_email or username.");
        } else {
            $data = array("RESULT"=> 2, "MESSAGE" => "Account was not created. Please try again. If this problem reoccurs please contact support.");
        }
      }
   // } else {
   //    $data = array("RESULT"=> 2, "MESSAGE" => "Email and/or passwords do not match.");
   // }

 } else {
    $data = array("RESULT"=> 2, "MESSAGE" => "Please input all required data.");
 }
