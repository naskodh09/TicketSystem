<?php require 'php/autoloader.php'; ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Searchbox</title>
</head>

<body>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
        <input type="text" name="searchBox" value="">
        <input type="submit" name="search" value="search">
    </form>
    <?php
    //this function searches for items

    $config = config::getDBConfig();
    $servername = $config->db_host;
    $username = $config->db_user;
    $password = $config->db_pass;
    $dbname = $config->db_name;
    $searchBox = filter_input(INPUT_POST, 'searchBox');


    if (isset($_POST['search'])) {

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname)
            or die("error");
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error($conn));
        }

        // getting data from the database

        $sql_Insert = "SELECT name from `accounts` Where name like '%$searchBox%'";

        $stmt_Insert = mysqli_prepare($conn, $sql_Insert);
        if ($stmt_Insert = mysqli_prepare($conn, $sql_Insert)) {
            mysqli_stmt_execute($stmt_Insert);
            mysqli_stmt_bind_result($stmt_Insert, $a);
            mysqli_stmt_store_result($stmt_Insert);
        } else {
            echo mysqli_error($conn);
        }
        if (mysqli_stmt_num_rows($stmt_Insert) >= 0) {
            while (mysqli_stmt_fetch($stmt_Insert)) {
                echo $a . "<br>";
            }
        } else {
            echo "There was an error:" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    ?>
</body>

</html>
