<?php

$username="";
$password="";
$database="";

$func = $_POST["func"];

if ($func == "download") {

  mysql_connect("localhost",$username,$password);
  @mysql_select_db($database) or die( "Unable to select database");
  $query = "SELECT * FROM nightlife";
  $result = mysql_query($query);
  $num = mysql_numrows($result);
  mysql_close();

  $going = array();
  while(($row =  mysql_fetch_assoc($result))) {
    $going[] = array('userID' => $row['userID'], 'locationID' => $row['locationID']);
  }
  echo json_encode($going);

}


if ($func == "update") {

  $userID = $_COOKIE["ezchxNightlife"];
  $locationID = $_POST["locationID"];

  mysql_connect("localhost",$username,$password);
  @mysql_select_db($database) or die( "Unable to select database");
  $query = "SELECT * FROM nightlife WHERE userID = '$userID' AND locationID = '$locationID'";
  $result = mysql_query($query);
  $num = mysql_numrows($result);
  if ($num != '') {
    $query = "DELETE FROM nightlife WHERE userID = '$userID' AND locationID = '$locationID'";
    mysql_query($query);
  } else {
    $query = "INSERT INTO nightlife VALUES (
      '',
      '$userID',
      '$locationID')";
    mysql_query($query);
  }
  mysql_close();

  echo json_encode($userID);

}

exit;

?>