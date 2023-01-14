<?php
/**
** functon that retrieves data from the db, supply sql path + binding params and it does the rest
**/

function getData($path, $bindings = null) {
  require 'connection.php';

  $myfile = fopen($path, "r") or die("Unable to open file!");

  $sql = fread($myfile,filesize($path));

  fclose($myfile);

   $stmt = $conn->prepare($sql);

  if($bindings !== null && !empty($bindings)) {
     $stmt->bind_param($bindings["BINDING_TYPES"], ...$bindings["VALUES"]);
  }

  $stmt->execute();

  $result = $stmt->get_result();

  $data = [];

  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  $conn->close();

  // returns data as an array even if one element is fetched. Ex: array(array("last_name"=>"hello"));
  // access data needed using $data[0];
  return $data;
}

function getDataBySQL($sql, $bindings = null) {
  require 'connection.php';

  $stmt = $conn->prepare($sql);

  if($bindings !== null && !empty($bindings)) {
     $stmt->bind_param($bindings["BINDING_TYPES"], ...$bindings["VALUES"]);
  }

  $stmt->execute();

  $result = $stmt->get_result();

  $data = [];

  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  $conn->close();

  // returns data as an array even if one element is fetched. Ex: array(array("last_name"=>"hello"));
  // access data needed using $data[0];
  return $data;
}
