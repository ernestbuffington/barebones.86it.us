<?php
/**
PHP needs: questionID and answer (text) as parameters
questionID: the id of the question the user is adding the answer to.
answer: Text of the answer the user wrote
**/

$data = [];

session_start();

if(!isset($_SESSION["ACCID"])) {
  $data = array("RESULT"=> 2, "MESSAGE" => "Please login before answering a question.");
  return;
}

if(isset($_POST["questionID"]) && isset($_POST["answer"])){
      require_once("././inserter.php");

      $questionID = $_POST["questionID"];
      $answer = $_POST["answer"];
      $accid = $_SESSION["ACCID"];

      $bindings = [];

      $bindings["BINDING_TYPES"] = "iis";
      $bindings["VALUES"] = array(
                                $questionID,
                                $accid,
                                $answer
                              );

      $result = insertData("answers/add/sql.txt", $bindings);

      if ($result["RESULT"] === 1) {
        $data = array("RESULT"=> 1, "MESSAGE" => "nuke_answer was successfully posted!", "user_id" => $result["LAST_INSERTED_ID"]);
      } else {
        $data = array("RESULT"=> 2, "MESSAGE" => "nuke_answer was not saved. Please try again. If this problem reoccurs, please contact support.");
      }
 } else {
    $data = array("RESULT"=> 2, "MESSAGE" => "Please input all required data.");
 }
