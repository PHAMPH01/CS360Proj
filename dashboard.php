<?php
session_start();
include_once('navBar.php');
include_once('phongUtil.php')
?>
<!DOCTYPE html>
<html>      
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php
    echo "<div class='greet'>Hello, " . $_SESSION['userName'] ."</div>";
    ?>
    <div class="content">
        <div class="row">
            <div class="box"><a href="https://google.com">Past recipes</a></div>
            <div class="box"><a href="https://google.com">Preferences</a></div>
        </div>
        <div class="row">
            <div class="box"><a href="https://google.com">Setting</a></div>
            <div class="box"><a href="home.php?op=pantry">Pantry</a></div>
        </div>
        <div class="recommended-box">
            Recommended Recipes
        </div>
    </div>
</body>
</html>
