<?php
require_once "connect.php";
require_once "social_post.php";

session_start();
$user_id = $_SESSION['user_id'];
$db = Database::getInstance();
$connection = $db->getConnection();

if ($_SESSION['type'] == 'premium') {

    $title = $image = $content = "";
    $title_err = $image_err = $content_err = "";
    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty(trim($_POST["title"]))) {
            $title_err = "Please enter a title.";
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', trim($_POST["title"]))) {
            $title_err = "title can only contain letters, numbers, and underscores.";
        } else {
            $titl = trim(htmlspecialchars($_POST["title"]));
            $sql = "SELECT id FROM scientific WHERE title = '$titl'";
            $res = mysqli_query($connection, $sql);
            if (mysqli_num_rows($res) > 0) {
                $title_err = "title Already Exists.";
            } else {
                $title = trim(htmlspecialchars($_POST["title"]));
            }
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

        if (empty(trim($_POST["content"]))) {
            $content_err = "Please enter a content.";
        } else {
            $content = trim(htmlspecialchars($_POST["content"]));
        }

        if (empty($title_err) && empty($image_err) && empty($content_err)) {

            $post = new Social\SocialPost($user_id, $title, $content, $image);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            $post->add_post();

            header('Location: index.php');
        }
    } //if _POST

} //SESSION
else {
    echo "You don't have permission to add post.";
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Social</title>
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
    <div class="wrapper">
        <h2> Add Post</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                <span class="invalid-feedback"><?php echo $image_err; ?></span>
            </div>
            <div class="form-group">
                <label>Content</label>
                <input type="text" name="content" class="form-control">
                <span class="invalid-feedback"><?php echo $content_err; ?></span>

            </div>


            <div class="form-group">
                <input type="submit" name="add" class="btn btn-outline-primary btn-sm" value="Add">
                <!-- <input type="submit" name="update" class="btn btn-outline-success btn-sm" value="Update">
                <input type="submit" name="delete" class="btn btn-outline-danger btn-sm" value="Delete">
                 -->
            </div>

        </form>
    </div>
</body>

</html>