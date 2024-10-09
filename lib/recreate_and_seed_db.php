<?php
  require_once('connectdb.php');
  if (getenv('DATABASE_URL')) {
    $db_props = parse_url(getenv('DATABASE_URL'));
    $database = substr($db_props['path'], 1);
  } else {
    $database = 'inject_demodb';
  }
  $db = connectdb('information_schema');
  mysqli_query($db, "CREATE DATABASE IF NOT EXISTS $database;");
  mysqli_close($db);
  // coffee table
  $drop_sql = 'DROP TABLE IF EXISTS staff;';
  $create_sql = file_get_contents('../sql/create_db.sql');
  $data_sql = file_get_contents('../sql/staff_data.sql');



  $db = connectdb();
  $result = mysqli_query($db, $drop_sql);
  if (!$result) {
    die("ERROR: " . mysqli_error($db));
  }
  $result = mysqli_query($db, $create_sql);
  if (!$result) {
    die("ERROR: " . mysqli_error($db));
  }
  $result = mysqli_query($db, $data_sql);
  if (!$result) {
    die("ERROR: " . mysqli_error($db));
  }

  // user table
  $recreate_users = file_get_contents('../sql/users.sql');
  $result = mysqli_multi_query($db, $recreate_users);
  if (!$result) {
    die("ERROR: " . mysqli_error($db));
  }
  mysqli_close($db);
  header('Location: ../index.php');
?>