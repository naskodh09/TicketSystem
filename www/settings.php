<?php
    require 'php/autoloader.php';
    $accounts = new Accounts();
// checks if user is logged in and if the value returns 0 or false then directs the user back to index.php which clears the users session
   //gets users id from the session id
    $userID = $_SESSION['userID'];
    if (!isset($userID) || $accounts->getUserApproved($userID) == 0) {
        header('location: index.php', true);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form.css">
    <title>Settings</title>
</head>

<body>
    <div class="mainBox">

        <h1>User Settings</h1>

        <?php
            // gets the users information from the database using the users id from the session
            $userInfo = $accounts->getUserInfo($userID);
        ?>

        <form name="Input" action="settings.php" method="POST">

            <!-- dynamically inputs all data from the database into the form -->

            <div class="input-wrap">
                <input type="Text" name="name" value="<?= $userInfo[1] ?>" placeholder="Name">
                <input type="Text" name="email" value="<?= $userInfo[2] ?>" placeholder="Email">
                <input type="Submit" name="submit" value="Submit">
                <a href="passwordchange.php">Change password</a>
                <a href="viewticket.php">Back</a>
            </div>

        </form>

    </div>

    <?php

    //filters all data and edits the database on submit to what was entered in the form
    if (isset($_POST["submit"])) {
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        // enters users information into the database
        $accounts->editAccountSettings($userID, $name, $email);
        header("Location: settings.php");
    }
    ?>
</body>

</html>
