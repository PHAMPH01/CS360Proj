<?php
session_start();
include_once('db_connect.php');
include_once("navBar.php");
// include_once("phongUtil.php");
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
  <main>
    <?php
    if ($op == 'main' || $op == '') {
      ?>
      <section class="hero">
        <h1>We Are Cooking!</h1>
        <p>We are going to make something yummy! Give us information so that this meal will be very extra NICE!</p>
        <div class="cta">
          <button>LET'S COOK</button>
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
    }
    ?>
  </main>
</body>
<footer>
  <p class="copyright">&copy; 2023 We Are Cooking!</p>
</footer>

</html>