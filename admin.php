<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Post</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font: 14px sans-serif;
    }

    .wrapper {
      width: 360px;
      padding: 20px;
    }
  </style>
</head>

<body>

  <?php
  include "nav.php";

  require_once 'user.php';
  require_once 'scientific_post.php';
  require_once 'social_post.php';
  session_start();
  if ($_SESSION['type'] == 'premium') {

    $users = User::show_users();
    echo "<h2> USERS</h2>";

    foreach ($users as $user) {
      if ($user['type'] != 'premium') {
        echo "<p>Name is :" . $user['name'] . "<br> ";
        echo "Email is :" . $user['email'] . "</p>";

  ?><a href="update.php?id=<?php echo $user['id']; ?>"><button class="btn btn-primary">premium</button></a><br>
  <?php
      }
    }
  } else {
    echo '<div class="alert alert-danger">' . 'You Are Not Admin' . '</div>';
  }

  ?>

</body>

</html>