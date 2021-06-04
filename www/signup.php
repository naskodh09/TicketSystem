<?php require 'php/autoloader.php'; ?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Sign-up</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/form.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
            if(isset($_POST['submit']))
            {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = filter_input(INPUT_POST, 'password');
                $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm');

                if(!empty($username) && !empty($email) && !empty($password) && !empty($passwordConfirm))
                {
                    if($password === $passwordConfirm)
                    {
                        $accounts = new Accounts();
                        $returnValue = $accounts->register($username, $email, $password);
                    }
                }
                header("Location: login.php");
            }
        ?>
        <!--  This is for the yellow box -->
        <div class ="mainBox">
            <!-- Main header -->
            <h1>ForexNinja</h1>

            <!-- Sign-up form -->
            <form action="signup.php" method="POST">
                <div class="input-wrap">
                    <input type="text" name="username" placeholder="Username">
                    <input type="email" name="email" placeholder="Email">
                    <?php if(isset($returnValue) && $returnValue === "Email taken") echo "Email taken"  ?>
                    <input type="password" name="password" placeholder="Password">
                    <input type="password" name="passwordConfirm" placeholder="Confirm password">
                    <input type="submit" name="submit" value="Sign up">
                </div>

                <a href="login.php">Login</a>
            </form>
        </div>
    </body>
</html>
