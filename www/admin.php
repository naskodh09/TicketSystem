<?php
    require 'php/autoloader.php';
    $accounts = new Accounts();
    // checks if user is logged in and is an admin and if the value returns 0 or false then directs the user back to index.php which clears the users session
    if(!isset($_SESSION['userID']) || $accounts->getUserAdmin($_SESSION['userID']) == 0)
    {
        header('location: index.php', true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Forex Ninja Admin Page</title>
</head>
<body>

    <header>
        <h1>Admin page</h1>
        <a href="viewticket.php">Head back to tickets</a>
    </header>

    <?php
    //gets all users information from the database
        $allUsers = $accounts->getAllUsers();
    ?>
    <div id="tableadmin">
    <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Admin Level</th>
                <th>Approved</th>
                <th>Last Login</th>
                <th>Registration Date</th>
                <th></th>
            </tr>
            <?php 
            //takes all users information and uses a foreach loop to display all users information in a table by using the variable element and then each column from the array as well as passes the element zero for the users id in the url when the user clicks edit and directs to adminedit.php
            foreach ($allUsers as $element):   
            ?>
                <tr><td><?= $element[0] ?></td><td><?= $element[1] ?></td><td><?= $element[2] ?></td><td><?= $element[3] ?></td><td><?= $element[4] ?></td><td><?= $element[5] ?></td><td><?= $element[6] ?></td><td><a href="admineditpage.php?id=<?= $element[0] ?>">EDIT</a></td></tr>
            <?php endforeach; ?>
        </table>
        </div>
        
</body>
</html>
