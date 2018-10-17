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
      http_response_code(401);
      $response['status'] = array("code"=>"401","message"=>"User not found");
      $response['data'] = array();
      return $response;
    } else {
      http_response_code(401);
      $response['status'] = array("code"=>"401","message"=>"User credentials are not correct");
      $response['data'] = array();
      return $response;
    }
  } else {
    http_response_code(200);
    $response['status'] = array("code"=>"200","message"=>"User found");
    $response['data'] = $userdata;
    return $response;
  }
}







$username = '';
$password = '';
$token = '';
$data = array();

if (file_get_contents('php://input', true)) {
  $data = json_decode(file_get_contents('php://input', true), true);
  $username = $data["username"];
  $password = $data['password'];
  $headers = getallheaders();
  $token = $headers['token'];

  if(empty($username) || empty($password)) {
    http_response_code(400);
    $response['status'] = array("code"=>"400","message"=>"Empty username, password or both");
    $response['data'] = array();
    echo json_encode($response);
    die();
  }
  if(empty($token) || $token !== 'FEBB222BFE78A') {
    http_response_code(401);
    $response['status'] = array("code"=>"401","message"=>"Unauthorized access");
    $response['data'] = array();
    echo json_encode($response);
    die();
  }
} else {
  http_response_code(400);
  $response['status'] = array("code"=>"400","message"=>"Empty body");
  $response['data'] = array();
  echo json_encode($response);
  die();
}

$conn = db_connect();

if(!$conn) {
  http_response_code(503);
  $response['status'] = array("code"=>"503","message"=>"Can't connect to database");
  $response['data'];
  echo json_decode($response);
  die ();
}

$response = authunticate($username, $password, $conn);

echo json_encode($response);

$conn -> close();

?>
