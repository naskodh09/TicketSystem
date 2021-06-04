<?php
    require 'php/autoloader.php';

    $accounts = new Accounts();
    $tickets = new Tickets();

    $userID = $_SESSION['userID'];
    if(!isset($userID) || $accounts->getUserApproved($userID) == 0)
    {
        header('location: index.php', true);
    }

    if(isset($_POST['submit']))
    {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);

        if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name']))
        {
            $file = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $filehandler = new Filehandler();

            if($return = $filehandler->uploadfile($file, $fileName))
            {
                if(!empty($title) && !empty($question))
                {
                    if(in_array($return[1], ['png', 'jpeg'], true))
                    {
                        $question = $question . '<br><img src="' . $return[0] . '" alt="Question image">';
                    }
                    elseif (in_array($return[1], ['pdf'], true))
                    {
                        $question = $question . '<br><a href="' . $return[0] . '" target="_blank"">PDF</a>';
                    }
                    $tickets->submitTicket($title, $question, $userID);
                }
            }
        }
        else
        {
            if(!empty($title) && !empty($question))
            {
                $tickets->submitTicket($title, $question, $userID);
            }
        }
    }
    header ('location: viewticket.php');
?>
