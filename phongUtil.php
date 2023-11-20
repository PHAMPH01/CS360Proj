<?php

session_start();
include_once('db_connect.php');

function logInForm()
{
    ?>
    <h2>Login</h2>
    <form id='loginForm' method='POST' action='?op=processLogin'>
        <label for="userName">User name</label>
        <input type='text' name='userName' required><br>
        <label for="password">Password</label>
        <input type='text' name='password' required><br>
        <input type='submit' value='Login' />
    </form>
    <p>Don't have an account? <a href="?op=signUp" >Sign up now</a></p>

    <?php
}

function logIn($db, $post_data){
    // Handle login form submission
    if (
        isset($post_data['userName'])
        && isset($post_data['password'])
    ) {
        // Process the user's input and create an account
        $uname = $post_data['userName'];
        $pwd = $post_data['password'];

        $sql = "SELECT * FROM account WHERE userName = '$uname' AND password = '$pwd'";
        $res = $db->query($sql);
        if ($res != false) {
            $row = $res->fetch();
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['userName'] = $row['userName'];
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];
            $_SESSION['email'] = $row['email'];
            header("refresh:3;url=?op=main");
            echo "<H3>Welcome " . $_SESSION['userName'] . "!</H3>";
        } else {
            header("refresh:3;url=?op=loginForm");
            echo "<h3>Error logging in<h3>";
        }

    }

}

function signUp()
{
    // Display the sign-up form
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Sign Up</title>
    </head>

    <body>
        <h2>Sign Up</h2>
        <form name='fmSignup' method='POST' action='?op=processSignUp'>
            <label for="userName">User name</label>
            <input type='text' name='userName' required><br>
            <label for="password">Password</label>
            <input type='text' name='password' required><br>
            <label for="fname">First Name</label>
            <input type='text' name='fname' required><br>
            <label for="lname">Last Name</label>
            <input type='text' name='lname' required><br>
            <label for="mail">Email</label>
            <input type='text' name='mail'><br>
            <input type='submit' value='Sign Up' />
        </form>
    </body>

    </html>
    <?php
}

function addToUser($db, $post_data)
{
    // Handle sign-up form submission
    if (
        isset($post_data['userName'])
        && isset($post_data['password'])
    ) {
        // Process the user's input and create an account
        $uname = $post_data['userName'];
        $pwd = $post_data['password'];
        $fname = $post_data['fname'];
        $lname = $post_data['lname'];
        $email = $post_data['mail'];

        $sql = "INSERT INTO account (userName, password, fname, lname, email) VALUES ('$uname', '$pwd', '$fname', '$lname', '$email')";
        $res = $db->query($sql);
        if ($res != false) {
            header("refresh:2;url=?op=viewProfile");
            echo "<H3>Welcome new user " . $fname . "!.</H3>";
        } else {
            header("refresh:2;url=?op=signUp");
            echo "<h3>Error creating new user<h3>";
        }

    }
}

function aboutUs(){
    
}
?>