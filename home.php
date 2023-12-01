<?php
session_start();
include_once('db_connect.php');
include_once("navBar.php");
include_once("phongUtil.php");
include_once("benUtil.php");
if (isset($_GET['op'])) {
  $op = $_GET['op'];
} else {
  $op = '';
}

?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <title>We Are Cooking!</title>
</head>

<body>
  <div class="wrapper">
    <main>
      <?php
      if ($op == 'main' || $op == '') {
        ?>
        <section class="hero">
          <h1>We Are Cooking!</h1>
          <p>We are going to make something yummy! Give us information so that this meal will be very extra NICE!</p>
          <div class="cta">
          <button onclick= 'window.location.href = "?op=searchForm"'>LET'S COOK</button>
          </div>
        </section>
        <?php
      } else if ($op == 'loginForm') {
        logInForm();
      } else if ($op == 'signUp') {
        signUp();
      } else if ($op == 'processSignUp') {
        addToUser($db, $_POST);
      } else if ($op == 'processLogin') {
        logIn($db, $_POST);
      } else if ($op == 'logout') {
        // Unset individual session variables
        unset($_SESSION['userID']);
        unset($_SESSION['userName']);
        unset($_SESSION['fname']);
        unset($_SESSION['lname']);
        unset($_SESSION['email']);
        // Destroy the session
        session_destroy();
        // Regenerate session ID (optional)
        session_regenerate_id(true);
        header("Location: home.php");
        exit();
      } else if ($op == 'aboutUs') {
        aboutUs();
      } else if ($op == 'resource') {
        resource();
      } else if ($op == 'pantry') {
        pantryForm($db);
      } else if ($op == 'processPantry') {
        processPantry($db, $_POST);
      } else if ($op == 'removeFromPantry') {
        removeFromPantryForm($db);
      } else if ($op == 'processRemoveFromPantry') {
        processRemoveFromPantry($db, $_POST);
      } else if ($op == 'searchForm') {
      	ben_genSearchForm($db, $_SESSION['userID']);
      } else if ($op == 'search'){
      	//runs sql search and creates a table with results
      	ben_search($db, $_POST);
      }

      ?>
    </main>
  </div>
</body>
<footer>
  <p class="copyright">&copy; 2023 We Are Cooking!</p>
</footer>

</html>
