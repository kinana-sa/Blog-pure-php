<!DOCTYPE html>
<html>

<head>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Averia+Serif+Libre|Noto+Serif|Tangerine" rel="stylesheet">
        <!-- Styling for public area -->
        <link rel="stylesheet" href="images/style.css">
        <meta charset="UTF-8">

</head>

<body>

        <div class="container">

                <div class="navbar">


                        <ul>
                                <li><a href="add_scientific.php">add scientific post</a></li>
                                <li><a href="add_social.php">add social post</a></li>
                                <li><a class="active" href="login.php">LogIn</a></li>
                                <li><a class="active" href="logout.php">Logout</a></li>
                                <li><a href="#about">About</a></li>
                        </ul>

                </div>
        </div>
        <?php echo date('Y-m-d H:i:s'); ?>
</body>

</html>