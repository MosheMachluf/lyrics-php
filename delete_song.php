<?php 

session_start();
require_once 'app/helpers.php';

$sid = !empty($_GET['sid']) ? trim($_GET['sid']) : null;
$uid = $_SESSION['user_id'] ?? null;

if($uid && $sid && is_numeric($sid)) {

  $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
  $sid = mysqli_real_escape_string($link, $sid); 
  $sql ="DELETE FROM songs WHERE id = $sid AND user_id = $uid";
  $result = mysqli_query($link, $sql);
  header('location: ./');

}