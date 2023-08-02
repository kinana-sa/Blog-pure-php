<?php

require_once 'post.php';
require_once 'scientific_post.php';
require_once "connect.php";

session_start();
$post = Post::get_post($_GET['id'], 'scientific');
if (!$post) {
  echo "Invalid post ID.";
  exit;
}
if ($_SESSION['user_id'] != $post['owner_id']) {
  echo "You don't have permission to edit this post.";
  exit;
}

if ($_SESSION['type'] == 'premium') {

  $title = $keywords = $content = $post_id = "";
  $title_err = $keywords_err = $content_err = "";
  // Processing form data when form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST["post_id"];

    if (empty(trim($_POST["title"]))) {
      $title_err = "Please enter a title.";
    } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', trim($_POST["title"]))) {
      $title_err = "title can only contain letters, numbers, and underscores.";
    } else {
      $title = trim(htmlspecialchars($_POST["title"]));
    }
    if (empty(trim($_POST["content"]))) {
      $content_err = "Please enter a content.";
    } else {
      $content = trim(htmlspecialchars($_POST["content"]));
    }

    if (empty(trim($_POST["keywords"]))) {
      $keywords_err = "Please enter a keyword.";
    } elseif (!preg_match('/^[a-zA-Z0-9 _\,]+$/', trim($_POST["keywords"]))) {
      $keywords_err = "keywords can only contain letters, numbers, and underscores.";
    } else {
      $keywords = trim(htmlspecialchars($_POST["keywords"]));
    }
    if (empty($title_err) && empty($keywords_err) && empty($content_err)) {

      Scientific\ScientificPost::update_post($post_id, $title, $content, $keywords);

      echo "Post updated successfully!";
      header("Location: index.php");
    }
  }
}

?>
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
  $post_id = $_GET['id'];
  $post = \Post::get_post($post_id, 'scientific');
  ?>
  <div class="wrapper">
    <h2>Edit Post</h2>
    <form action="" method="post">
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo $post['title']; ?>" class="form-control <?php echo !empty($title_err) ? 'is-inv
alid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $title_err; ?></span>
      </div>

      <div class='form-group'>
        <label>Keywords:</label><br>
        <input type='text' name='keywords' value="<?php echo $post['keywords']; ?>" class="form-control <?php echo !empty($keywords_err)
? 'is-invalid' : ''; ?>"><br>
        <span class='invalid-feedback'><?php echo $keywords_err; ?></span>
      </div>
      <div class="form-group">
        <label>Content</label>
        <input type="text" name="content" value="<?php echo $post['content']; ?>" class="form-control <?php echo !empty($content_err) ? '
is-invalid' : ''; ?>"">
        <span class=" invalid-feedback"><?php echo $content_err; ?></span>
      </div>
      <input type='hidden' name='post_id' value="<?php echo $_GET['id']; ?>">
      <div class="form-group">
        <input type="submit" name="update" class="btn btn-outline-primary btn-sm" value="Update">
      </div>

    </form>
  </div>
</body>

</html>