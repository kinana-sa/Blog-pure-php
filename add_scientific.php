<?php
require_once "connect.php";
require_once "scientific_post.php";

session_start();

$user_id = $_SESSION['user_id'];
$db = Database::getInstance();
$connection = $db->getConnection();

if ($_SESSION['type'] == 'premium') {

    $title = $keywords = $content = "";
    $title_err = $keywords_err = $content_err = "";
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

        if (empty(trim($_POST["keywords"]))) {
            $keywords_err = "Please enter a keyword.";
        } elseif (!preg_match('/^[a-zA-Z0-9 ,]+$/', trim($_POST["keywords"]))) {
            $keywords_err = "keywords can only contain letters, numbers, and underscores.";
        } else {
            $keywords = trim(htmlspecialchars($_POST["keywords"]));
        }

        if (empty(trim($_POST["content"]))) {
            $content_err = "Please enter a content.";
        } else {
            $content = trim(htmlspecialchars($_POST["content"]));
        }

        if (empty($title_err) && empty($keywords_err) && empty($content_err)) {
            $post = new Scientific\ScientificPost($user_id, $title, $content, $keywords);
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
    <title>Science</title>
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
        <h2>Add Post</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title"  class="form-control <?php echo !empty($title_err) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Keywords</label>
                <input type="text" name="keywords"  class="form-control <?php echo !empty($keywords_err) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $keywords_err; ?></span>
            </div>
            <div class="form-group">
                <label>Content</label>
                <input type="text" name="content" class="form-control <?php echo !empty($content_err) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $content_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="add" class="btn btn-outline-primary btn-sm" value="Add">
            </div>

        </form>
    </div>
</body>

</html>