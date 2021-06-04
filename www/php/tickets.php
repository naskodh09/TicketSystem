<?php
    class Tickets extends DB
    {
        // Gets all tickets from specific user
        public function getUsersTickets($userID, $flagged = false)
        {
            $query = "SELECT id, subject, content, closed, user_id, created_at, updated_at, closed_at FROM tickets WHERE user_id = ?";
            if($flagged)
                $query = "SELECT id, subject, content, closed, user_id, created_at, updated_at, closed_at FROM tickets WHERE user_id = ? AND flagged = '1'";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $userID);

                $stmt->execute();

                $stmt->bind_result($id, $subject, $content, $closed, $userID, $createdAt, $updatedAt, $closedAt);

                $stmt->store_result();

                $result = [];
                if($stmt->num_rows !== 0)
                {
                    while($stmt->fetch())
                    {
                        array_push($result, [$id, $subject, $content, $closed, $userID, $createdAt, $updatedAt, $closedAt]);
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        // Gets all tickets for the admin
        // or only the flagged ones if
        // flagged paramater is true
        public function getAllTickets($flagged = false)
        {
            $query = "SELECT id, subject, content, closed, user_id, created_at, updated_at, closed_at FROM tickets";
            if($flagged)
                $query = "SELECT id, subject, content, closed, user_id, created_at, updated_at, closed_at FROM tickets WHERE flagged = '1'";


            if($stmt = $this->connect($query))
            {
                $stmt->execute();

                $stmt->bind_result($id, $subject, $content, $closed, $userID, $createdAt, $updatedAt, $closedAt);

                $stmt->store_result();

                $result = [];
                if($stmt->num_rows !== 0)
                {
                    while($stmt->fetch())
                    {
                        array_push($result, [$id, $subject, $content, $closed, $userID, $createdAt, $updatedAt, $closedAt]);
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function getTicket($ticketID)
        {
            $query = "SELECT id, subject, content, closed, user_id, created_at, updated_at, closed_at FROM tickets WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $ticketID);

                $stmt->execute();

                $stmt->bind_result($id, $subject, $content, $closed, $userID, $createdAt, $updatedAt, $closedAt);

                $stmt->store_result();

                $result = [];
                if($stmt->num_rows === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = [$id, $subject, $content, $closed, $userID, $createdAt, $updatedAt, $closedAt];
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function submitTicket($title, $content, $userID)
        {
            $query = "INSERT INTO tickets (subject, content, user_id) VALUES (?, ?, ?)";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('ssi', $title, $content, $userID);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }

        public function commentTicket($comment, $ticketID, $userID)
        {
            $query = "INSERT INTO comments (content, ticket_id, user_id) VALUES (?, ?, ?)";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('sii', $comment, $ticketID, $userID);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }

        public function getTicketComments($ticketID)
        {
            $query = "SELECT id, content, ticket_id, user_id, created_at, updated_at FROM comments WHERE ticket_id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $ticketID);

                $stmt->execute();

                $stmt->bind_result($id, $content, $ticketID, $userID, $createdAt, $updatedAt);

                $stmt->store_result();

                $result = [];
                if($stmt->num_rows !== 0)
                {
                    while($stmt->fetch())
                    {
                        array_push($result, [$id, $content, $ticketID, $userID, $createdAt, $updatedAt]);
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        // Sets whether the ticket is
        // flagged or not depending
        // on the parameters
        public function setTicketFlag($id, $flagged)
        {
            $query = "UPDATE tickets SET flagged = ? WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('si', $flagged, $id);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }

        public function setTicketStatus($id, $status)
        {
            $timestamp = date("Y-m-d H:i:s");
            $query = "UPDATE tickets SET closed = ?, closed_at = ? WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('ssi', $status, $timestamp, $id);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }
    }
?>
