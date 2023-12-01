<div class="top-bar">
    <div class="logo"><a href="home.php?op=main">Logo</a></div>
    <div class="popular"><a href="home.php?op=popular">Today's Popular</a></div>
    <div class="about"><a href="home.php?op=aboutUs">About Us</a></div>
    <div class="resources"><a href="home.php?op=resource">Resources</a></div>
    <?php
    // Check if the user is logged in
    include_once("phongUtil.php");
    session_start();
    if (isset($_SESSION['userID'])) {
        echo '<div class="logout"><a href="dashboard.php">Dashboard</a> | <a href="home.php?op=logout">Logout</a></div>';
        // echo '<div class="logout"><a href="home.php?op=logout">Logout</a></div>';
    } else {
        echo '<div class="logout"><a href="home.php?op=loginForm">Login</a> | 
        <a href=home.php?op=signUp>Signup</a></div>';
    }
    ?>
</div>