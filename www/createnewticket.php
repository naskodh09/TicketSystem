<?php
    require 'php/autoloader.php';
    $tickets = new Tickets();
    $accounts = new Accounts();
    $userID = $_SESSION['userID'];

    if(!isset($userID) || $accounts->getUserApproved($userID) == 0)
    {
        header('location: index.php', true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/style.css">
	<meta charset="utf-8">
	<title>Create new ticket</title>
</head>
<body>

<header>
        <div class="logo-wrap">
            <a href="viewticket.php"><h1 class="logo">ForexNinja Help Desk</h1></a>

            <svg class="triangle">
                <polygon points="0,0 50,0 0,100" />
            </svg>
        </div>

        <p id="ticket-burger-button" onclick="openTicket()">☰ Your Tickets</p>

        <img class="placeholder" src="assets/stocks-placeholder.png" alt="placeholder">

        <div class="login-name">
            <p>Welcome <?= $accounts->getUsersName($userID) ?></p>

            <button id="openbtn" onclick="openNav()">☰</button>
        </div>


    </header>

    <div id="sidepanel">
            <button id="closebtnticket" onclick="closeTicket()">Your Tickets ☰</button>
            <div class="burger">
                <div class="ticket-list">
                    <div class="scrollable">
                        <?php
                        $ticketID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

                        if (isset($ticketID) && !empty($ticketID)) {
                            $ticketContent = $tickets->getTicket($ticketID);
                        }

                        $ticketExists = false;
                        if (isset($ticketContent) && $ticketContent !== []) {
                            $ticketExists = true;
                            if (isset($_POST['delete'])) {
                                $status = 1;
                                if ($ticketContent[3])
                                    $status = 0;

                                $tickets->setTicketStatus($ticketID, $status);
                                header('location: viewticket.php?id=' . $ticketID, true);
                            }

                        }

                        if($accounts->getUserAdmin($userID) == 1)
                            $allTickets = $tickets->getAllTickets();
                        else
                            $allTickets = $tickets->getUsersTickets($userID);

                        foreach ($allTickets as $ticket) :
                        ?>
                            <div class="ticket content-box <?php if($_GET['id'] == $ticket[0]){echo("selected");}  ?>">
                                <a href="viewticket.php?id=<?= $ticket[0] ?>">
                                    <div class="ticket-list-top">
                                        <p>ID: <?= $ticket[0] ?></p>
                                        <p><?= $accounts->getUsersName($ticket[4]) ?></p>
                                    </div>

                                    <div class="ticket-list-title">
                                        <p class="ticket-list-p"><?= $ticket[1] ?></p>
                                    </div>

                                    <div class="status-circle <?= ($ticket[3] == 0) ? 'open' : 'closed' ?>"></div>

                                    <div class="ticket-list-bottom">
                                        <p>CREATED ON: <?= $ticket[5] ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    </div>
    <div id="cover-menu" onclick="closeNav()"></div>

    <div id="sidepanel-menu">
            <button id="closebtn" onclick="closeNav()">☰</button>
            <nav class="burger">
                <div>
                    <h2>Menu</h2>

                    <div class="nav-link-wrapper">
                        <a href="createnewticket.php">Create New Ticket</a>
                        <a href="settings.php">Settings</a>
                    </div>
                </div>

                <div class="nav-logout-line">
                    <a id="logout" class="button logout" href="login.php">LOGOUT</a>
                </div>
            </nav>
    </div>
    <div id="cover" onclick="closeTicket()"></div>
    <script>
            /*Opens and closes the sidebars; Creates the dimming effect on the rest of the page*/
            function openNav() {
                    document.getElementById("sidepanel-menu").style.width = "230px";
                    document.getElementById("cover-menu").style.width = "1000px";
                    document.getElementById("cover-menu").style.backgroundColor = "rgba(0,0,0,0.4)";
            }

            function closeNav() {
                    document.getElementById("sidepanel-menu").style.width = "0";
                    setTimeout(function(){document.getElementById("cover-menu").style.width = "0";}, 200);
                    document.getElementById("cover-menu").style.backgroundColor = "rgba(0,0,0,0)";
            }

            function openTicket() {
                    document.getElementById("sidepanel").style.width = "230px";
                    document.getElementById("cover").style.width = "1000px";
                    document.getElementById("cover").style.backgroundColor = "rgba(0,0,0,0.4)";
            }

            function closeTicket() {
                    document.getElementById("sidepanel").style.width = "0";
                    setTimeout(function(){document.getElementById("cover").style.width = "0";}, 200);
                    document.getElementById("cover").style.backgroundColor = "rgba(0,0,0,0)";
            }
    </script>

<div class="wrapper">
    <div class="ticket-list">
		<p class="ticket-list-p">Your Tickets</p>
		<div class="scrollable">
            <?php
                if($accounts->getUserAdmin($userID) == 1)
                $allTickets = $tickets->getAllTickets();
            else
                $allTickets = $tickets->getUsersTickets($userID);

                foreach($allTickets as $ticket):
            ?>
			<div class="ticket content-box">
				<a href="viewticket.php?id=<?= $ticket[0] ?>">
					<div class="ticket-list-top">
						<p>ID: <?= $ticket[0] ?></p>
						<p><?= $accounts->getUsersName($ticket[4]) ?></p>
					</div>

					<div class="ticket-list-title">
						<p class="ticket-list-p"><?= $ticket[1] ?></p>
					</div>

					<div class="status-circle <?= ($ticket[3] == 0) ? 'open' : 'closed' ?>"></div>

					<div class="ticket-list-bottom">
						<p>CREATED ON: <?= $ticket[5] ?></p>
					</div>
				</a>
			</div>
            <?php endforeach; ?>
		</div>
	</div>

	<div class="create-wrapper">
		<h2>Create New Ticket</h2>
		<div class="create-ticket content-box">
            <form class="create-form" action="submit.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" id="title" placeholder="Title">
                <textarea name="question" placeholder="Enter question" id="create-textarea"></textarea>
                <input type="file" name="file">

                <input class="button" type="submit" name="submit" value="Submit" id="submit">
            </form>
		</div>
	</div>

	<nav class="content-box">

		<div>
			<h2>Menu</h2>

			<div class="nav-link-wrapper">
				<a href="createnewticket.php">Create New Ticket</a>
				<a href="settings.php">Settings</a>
                <?= ($accounts->getUserAdmin($_SESSION['userID']) == 1) ? '<a href="admin.php">Admin</a>' : '' ?>
			</div>
		</div>

		<div class="nav-logout-line">
			<a class="button logout" href="logout.php">LOGOUT</a>
		</div>

	</nav>

</div>

</body>
</html>
