<?php
header('Content-Type: application/json');

function db_connect() {
  $servername = "localhost";
  $dbusername 	= "root";
  $dbpassword 	= "";
  $dbname 		= "usersdb";

  $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
  return $conn;
}

function authunticate($username, $password, $conn) {

  $sql = "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'";
  $con_results = mysqli_query($conn, $sql);
  $userdata = mysqli_fetch_assoc($con_results);

  if(empty($userdata)) {

    $sql = "SELECT * FROM users WHERE username = '{$username}'";
    $con_results = mysqli_query($conn, $sql);
    $userdata = mysqli_fetch_assoc($con_results);

    if(empty($userdata)){
      $response['status'] = array("code"=>"401","message"=>"User not found");
      $response['data'];
      return $response
    } else {
      $response['status'] = array("code"=>"401","message"=>"User credentials are not correct");
      $response['data'];
      return $response
    }
  } else {
    $response['status'] = array("code"=>"200","message"=>"User found");
    $response['data'] = $userdata;
    return $response
  }

}









switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':{

  }
  break;
  case 'PUT': {

  }
  break;
  case 'POST': {


    $username = '';
    $password = '';
    $data = array();

    if (file_get_contents('php://input', true)) {
      $data = json_decode(file_get_contents('php://input', true), true);
      $username = $data["username"];
      $password = $data['password'];

      if(empty($username) || empty($password)) {
        $response['status'] = array("code"=>"400","message"=>"Empty username or password");
        $response['data'];
        echo $response;
        die();
      }

    } else {
      $response['status'] = array("code"=>"400","message"=>"Empty body");
      $response['data'];
      echo $response;
      die();
    }

    $conn = db_connect();

    if(!$conn) {
      $response['status'] = array("code"=>"503","message"=>"Can't connect to database");
      $response['data'];
      echo $response;
      die ();
    }

    $response = authunticate($username, $password, $conn) {

    }

  }
  break;






  case 'delete':{

  }
  break;
  default {
    $response['status'] = array("code"=>"405","message"=>"Request method not allowed");
    $response['data'];
    echo $response;

  }
}





?>
