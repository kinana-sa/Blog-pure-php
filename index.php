<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Show Posts</title>
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

  require_once 'post.php';
  require_once 'user.php';
  require_once 'scientific_post.php';
  require_once 'social_post.php';

  session_start();

  $db = Database::getInstance();
  $connection = $db->getConnection();

  if (isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];
    $query = "SELECT * FROM users WHERE  remember_me='$token'";
    $stmt = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($stmt);
    if ($user) {
      // log the user in
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['type'] = $user['type'];
    }
  } elseif (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
  }

  $scien_posts = Post::show_posts('scientific');
  echo "<h3> Scientific Posts</h3>";
  foreach ($scien_posts as $post) {
    $user = User::get_user($post['owner_id']);
    echo "<h4>" . $post['title'] . "</h4>";
    echo "<b>Keywords: </b><p>" . $post['keywords'] . "</p>";
    echo "<p>" . $post['content'] . "</p>";
    echo "<p>Posted by: " . $user['name'] . "</p>";
  ?>
    <?php
    if ($_SESSION['type'] == 'premium' && $_SESSION['user_id'] == $post['owner_id']) { ?>
      <a href="edit_scientific.php?id=<?php echo $post['id']; ?>"><button class="btn btn-outline-success btn-sm">Edit</button></a>
      <a href="delete_post.php?id=<?php echo $post['id']; ?>"><button class="btn btn-outline-danger btn-sm">delete</button></a>
    <?php  }
    ?>
  <?php
  }

  $soc_posts = Post::show_posts('social');
  echo "<h3> Social Posts</h3>";

  foreach ($soc_posts as $post) {
    $user = User::get_user($post['owner_id']);
    echo "<h4>" . $post['title'] . "</h4>";
    echo "<p>" . $post['content'] . "</p>";
    echo "<img src='" . $post['image'] . "' width='200'>";
    echo "<p>Posted by: " . $user['name'] . "</p>";
  ?>
    <?php
    if ($_SESSION['type'] == 'premium'  && $_SESSION['user_id'] == $post['owner_id']) { ?>
      <a href="edit_social.php?id=<?php echo $post['id']; ?>&file=<?php echo $post['image']; ?>"><button class="btn btn-outline-success btn-sm">Edit</button></a>
      <a href="delete_post.php?id=<?php echo $post['id']; ?>&file=<?php echo $post['image']; ?>"><button class="btn btn-outline-danger btn-sm">delete</button></a>
    <?php  }
    ?>
  <?php
  }


  ?>