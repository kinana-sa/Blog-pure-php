<?php

require_once 'post.php';
require_once 'social_post.php';
require_once "connect.php";

session_start();

$post = Post::get_post($_GET['id'], 'social');
if (!$post) {
    echo "Invalid post ID.";
    exit;
}
if ($_SESSION['user_id'] != $post['owner_id']) {
    echo "You don't have permission to edit this post.";
    exit;
}
if ($_SESSION['type'] == 'premium') {

    $title = $image = $content = $post_id = "";
    $title_err = $image_err = $content_err = "";
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
        $image_file = "images/" . $_FILES["image"]["name"];

        $type = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        if ($type != 'jpg' && $type != 'jpeg' && $type  != "png") {
            $image_err = 'Not accepted File Type. <br>';
        } elseif (file_exists($image_file)) {
            $image_err = 'File already exist. <br>';
        } elseif ($_FILES['image']['size'] > 500000) {
            $image_err = 'File is too large. <br>';
        } else {
            $image = $image_file;
        }
        if (empty($title_err) && empty($image_err) && empty($content_err)) {

            Social\SocialPost::update_post($post_id, $title, $content, $image);
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
    require_once 'post.php';
    $post_id = $_GET['id'];
    $post = \Post::get_post($post_id, 'social');
    ?>
    <div class="wrapper">
        <h2>Edit Post</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $post['title']; ?>" class="form-control <?php echo !empty($title_err) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>

            <div class="form-group">
                <label>Content</label>
                <input type="text" name="content" value="<?php echo $post['content']; ?>" class="form-control <?php echo !empty($content_err) ? 'is-invalid' : '';
 ?>">
                <span class="invalid-feedback"><?php echo $content_err; ?></span>
            </div>
            <div class='form-group'>
                <label>Image:</label><br>
                <input type='file' name='image' value="<?php echo $post['image']; ?>" class="form-control <?php echo !empty($image_err) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $image_err; ?></span>
            </div>
            <input type='hidden' name='post_id' value="<?php echo $_GET['id']; ?>">
            <div class="form-group">
                <input type="submit" name="update" class="btn btn-outline-primary btn-sm" value="Update">
            </div>

        </form>
    </div>
</body>

</html>