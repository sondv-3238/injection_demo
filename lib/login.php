<?php
  session_start();
  require_once('helpers.php');
  
  if (logged_in()) {
    header('Location: ../index.php');
    exit;    
  }

  // // Define the sanitizeInput function using regex and htmlspecialchars 
  // function sanitizeInput($input) {
  //   $input = preg_replace("/[^a-zA-Z0-9]/", "", $input);
  //   return htmlspecialchars(strip_tags(trim($input)));
  // }

  // // Sanitize user inputs
  // $name = sanitizeInput($_POST['username']);
  // $password = sanitizeInput($_POST['password']);
  
  $name = $_POST['username'];
  $password = $_POST['password'];
  $query = "SELECT * FROM users WHERE name='$name' AND password='$password';"; //cmt
  
  require_once('connectdb.php');
  $db = connectdb();
  $result = mysqli_multi_query($db, $query); // cmt

  // Sử dụng câu truy vấn có tham số để tránh SQL Injection (Prepare statement)
  // $query = "SELECT * FROM users WHERE name = ? AND password = ?";
  // $stmt = $db->prepare($query);
  // $stmt->bind_param('ss', $name, $password);
  // $stmt->execute();
  // $result = $stmt->get_result();

  if ($result) {
    $result = mysqli_use_result($db);
  }
  if ($result) {
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if (isset($user['id'])) {
      $_SESSION['user_id'] = $user['id'];
    }
  }
  if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = true;
  }
  mysqli_close($db);


  header('Location: ../index.php');
?>