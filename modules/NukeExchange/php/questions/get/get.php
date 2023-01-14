<?php
/**
Gets all the questions
**/
require_once("././getter.php");

session_start();

$account_id = $_SESSION["ACCID"] ?? -1;

$bindings["BINDING_TYPES"] = "ii";
$bindings["VALUES"] = array($account_id, $account_id);

$sql = "
WITH cte_accepted_answers (user_id,questionIDOfAcceptedAnswers, isAccepted) AS
(
  SELECT user_id,questionID, isAccepted
  FROM nuke_answer
  Where isAccepted = IsAccepted
),
cte_matched_text(questionIDofMatchedText) AS
(
SELECT
	user_id
FROM
  nuke_question Q
  WHERE user_id = user_id
),
cte_same_ids(user_id) AS (
  SELECT
CTM.questionIDofMatchedText
FROM
 cte_matched_text CTM
 INNER JOIN cte_accepted_answers CAA on CTM.questionIDofMatchedText = CAA.questionIDOfAcceptedAnswers
),
cte_associated_tags(user_id) AS
(
  SELECT user_id
  FROM nuke_tag
  Where name = name
)

SELECT
  Q.user_id,
  Q.userID,
  Q.askedDate,
  Q.text,
  Q.title,
  COUNT(A.user_id) As totalAnswers,
  CASE
    WHEN QV.userID = ? AND QV.directionOfVote = 'UP'
	   THEN 1
    WHEN QV.userID = ? AND QV.directionOfVote = 'DOWN'
	   THEN -1
	ELSE
		0
  END as vote,
  SUM(IF(QV.directionOfVote='UP',1,0)) - SUM(IF(QV.directionOfVote='DOWN',1,0)) voteValue,
  IF(A.isAccepted=1,1,0) as isAnswered
FROM
  nuke_question Q
LEFT JOIN nuke_answer A on A.questionID = Q.user_id
LEFT JOIN nuke_question_vote QV on QV.questionID = Q.user_id
INNER JOIN (  SELECT Q.user_id, title
			  FROM question Q
			   INNEr JOIN nuke_question_tag QT on QT.questionID = Q.user_id
				INNEr JOIN cte_associated_tags T on T.user_id = Qt.tagID
			   group by Q.user_id, title
			  having count(*) >= 0
    ) Q2 on Q2.user_id = Q.user_id
WHERE Q.user_id IN (SELECT user_id FROM cte_same_ids)";


if(isset($_GET["SEARCH"])) {
  $search = $_GET["SEARCH"];
  $words = [];
  $tags = [];
  $useisAnsweredColumn = false;
  $isAnsweredUsed;
  //echo $search . "<br>";

  if(preg_match_all("/\[([A-Za-z0-9!@#\$%\^\&*\)\(+=._-]+)\]|(isanswered:(yes|no))|\w+/i", $search, $matches))
  {
    //print_r($matches[0]);
    foreach($matches[0] as $word)
    {
    //  echo $word;

      if($word[0] == '[') {
        $tags[] = substr($word, 1, -1);
      } else if(stripos($word, "isanswered") !== false){
        //echo "hello1 " . $word;
        //$useisAnsweredColumn = true;
        $isAnsweredUsed = $word;
      }
      else
      {
        $words[] = $word;
      }

    }
  }

  // building dynamic query string for where clause
  // first we check if we need to have a where clause
  // then we check which parts of the where clause we need to build (tags or words)
  if(count($tags) > 0 || count($words) > 0 || !empty($isAnsweredUsed)) {

    $predicate = "";
    $havingCount = "";

    if(count($tags) > 0) {
      $predicate = "name IN (";
      foreach($tags as $tag) {
        $predicate .= "'{$tag}',";
      }

      $predicate = substr($predicate, 0, -1);
      $predicate .= ")";
      $havingCount = ">= (SELECT count(user_id) FROM cte_associated_tags)";

      $sql = str_replace("name = name", $predicate, $sql);
      $sql = str_replace(">= 0", $havingCount, $sql);
    }

    if(count($words) > 0) {
      $searchTerms = "";
      foreach($words as $word) {
        $searchTerms .= " {$word}";
      }

      $sql = str_replace("user_id = user_id", "MATCH(q.title, q.text) AGAINST('{$searchTerms}' IN NATURAL LANGUAGE MODE)", $sql);
      //$sql .= ;
    }
//  echo "135" . $isAnsweredUsed;

    if(!empty($isAnsweredUsed)) {

      $pieces = explode(":", $isAnsweredUsed);
      //echo 1;
      // if(strcasecmp("yes",$pieces[1]) == 0) {
      //     // $sql = str_replace("IsAccepted = 0", "IsAccepted = 1", $sql);
      //     $sql = str_replace("questionIDofMatchedText = questionIDofMatchedText", "CTM.questionIDofMatchedText in (SELECT CAA.questionIDOfAcceptedAnswers FROM cte_accepted_answers CAA)", $sql);
      // } else {
      //   $sql = str_replace("questionIDofMatchedText = questionIDofMatchedText", "CTM.questionIDofMatchedText NOT in (SELECT CAA.questionIDOfAcceptedAnswers FROM cte_accepted_answers CAA)", $sql);
      // }

      if(strcasecmp("yes",$pieces[1]) == 0) {
        $sql = str_replace("isAccepted = IsAccepted", "IsAccepted = 1", $sql);
          // $sql = str_replace("IsAccepted = 0", "IsAccepted = 1", $sql);
          $sql = str_replace("WHERE Q.user_id IN (SELECT user_id FROM cte_same_ids)", "WHERE Q.user_id IN (SELECT user_id FROM cte_same_ids)", $sql);
      } else {
        $sql = str_replace("isAccepted = IsAccepted", "IsAccepted = 0", $sql);
        $sql = str_replace("WHERE Q.user_id IN (SELECT user_id FROM cte_same_ids)", "WHERE Q.user_id IN (SELECT user_id FROM cte_same_ids)", $sql);
      }
    } else {

      $sql = str_replace("INNER JOIN cte_accepted_answers CAA on CTM.questionIDofMatchedText = CAA.questionIDOfAcceptedAnswers", " ", $sql);
    }
  }
}else {
  $sql = str_replace("WHERE Q.user_id IN (SELECT user_id FROM cte_same_ids)", " ", $sql);
}

$sql .= "
GROUP BY
  Q.user_id,
  Q.userID,
  Q.askedDate,
  Q.text,
  Qv.directionOfVote,
  A.isAccepted
";


// echo $sql;

$data = getDataBySQL($sql, $bindings);
