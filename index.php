<?php 
define('INFOX',  TRUE); // used to protect includes
session_start();
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
  $data = [
    "username" => $_POST['username'],
    "password" => $_POST['password']
  ];
  $curl = curl_init('https://console.nooneducare.in/' . $_POST['role'] . '/login');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
  curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'X-NoonEducare-Host:' . $_SERVER['HTTP_HOST'],
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data)),
  ]);
  $response = curl_exec($curl);
  curl_close($curl);
  $response = json_decode($response, true);
  if ($response['status'] == 1) {
    $_SESSION['_token'] = $response['data']['token'];
    $_SESSION['_role'] =  $_POST['role'];
    $_SESSION['_name'] = $response['data']['user']['name'];
    if (isset($_POST['test']) && $_POST['test'] == '1') {
      $_SESSION['_test'] =  $_POST['role'];
    }
    header('Location: dashboard');
  } else {
    $error = "Something went Wrong! Contact/Technical";
  }
}
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header('Location: /');
  exit;
}
if (isset($_SESSION["_token"])) {
  define('INFOX_TOKEN', $_SESSION['_token']);
  define('INFOX_PATH', $_SESSION['_role']);
  if (isset($_SESSION["_test"])) {
    define('INFOX_CONSOLE_URL', 'http://127.0.0.1:3001/' . INFOX_PATH);
  } else {
    define('INFOX_CONSOLE_URL', 'https://console.nooneducare.in/' . $_SESSION['_role']);
  }
  include('infox-edu.php');
} else {
  include('login.php');
}
