<?php
    class Accounts extends DB
    {
        // Takes an email, and password and checks
        // whether the password matches the password in
        // the database, returns true on correct password
        public function login($email, $password)
        {
            $query = "SELECT password FROM accounts WHERE email = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('s', $email);

                $stmt->execute();

                $stmt->bind_result($passwordDB);

                $stmt->store_result();

                $result = false;
                if($stmt->num_rows === 1)
                {
                    while($stmt->fetch())
                    {
                        if(password_verify($password, $passwordDB))
                        {
                            $result = true;
                        }
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        // Takes username, email, password
        // stores it in the database with
        // the password hashed
        public function register($username, $email, $password)
        {
            $query = "SELECT email FROM accounts WHERE email = ?";
            $emailTaken = false;
            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('s', $email);

                $stmt->execute();

                $stmt->store_result();

                if($stmt->num_rows >= 1)
                {
                    $emailTaken = true;
                }
            }

            if(!$emailTaken)
            {
                $query = "INSERT INTO accounts (name, email, password) VALUES (?, ?, ?)";
                $password = password_hash($password, PASSWORD_DEFAULT);

                if($stmt = $this->connect($query))
                {
                    $stmt->bind_param('sss', $username, $email, $password);

                    $stmt->execute();

                    $stmt->close();
                }
                $this->close();
            } else {
                return "Email taken";
            }
        }

        public function getAllUsers()
        {
            $query = "SELECT id, name, email, adminlevel, approved, last_login, insert_time FROM accounts";

            if($stmt = $this->connect($query))
            {
                $stmt->execute();

                $stmt->bind_result($id, $name, $email, $adminLevel, $approved, $lastLogin, $registerDate);

                $stmt->store_result();

                $result = [];
                if($stmt->num_rows !== 0)
                {
                    while($stmt->fetch())
                    {
                        array_push($result, [$id, $name, $email, $adminLevel, $approved, $lastLogin, $registerDate]);
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function getUserInfo($id)
        {
            $query = "SELECT id, name, email, password, adminlevel, approved, last_login, insert_time FROM accounts WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($id, $name, $email, $password, $adminLevel, $approved, $lastLogin, $registerDate);

                $stmt->store_result();

                $result = [];
                if($stmt->num_rows !== 0)
                {
                    while($stmt->fetch())
                    {
                        $result = [$id, $name, $email, $password, $adminLevel, $approved, $lastLogin, $registerDate];
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function setLastLogin($email)
        {
            $timestamp = date("Y-m-d H:i:s");
            $query = "UPDATE accounts SET last_login = ? WHERE email = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('ss', $timestamp, $email);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }

        public function getUsersID($email)
        {
            $query = "SELECT id FROM accounts WHERE email = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('s', $email);

                $stmt->execute();

                $stmt->bind_result($id);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = $id;
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        // Gets the user's username
        public function getUsersName($id)
        {
            $query = "SELECT name FROM accounts WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($username);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows !== 0)
                {
                    while($stmt->fetch())
                    {
                        $result = $username;
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function editAccounts($id, $name, $email, $password, $adminlevel, $approved)
        {
            $query = "UPDATE accounts
            SET name = ?, email= ?, password = ?, adminlevel = ?, approved = ? WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param("ssssss", $name, $email, $password, $adminlevel, $approved, $id);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }

        public function editAccountSettings($id, $name, $email)
        {
            $query = "UPDATE accounts
            SET name = ?, email= ? WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param("sss", $name, $email, $id);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }

        public function getUserApproved($id)
        {
            $query = "SELECT approved FROM accounts WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($approved);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = $approved;
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function getUserAdmin($id)
        {
            $query = "SELECT adminlevel FROM accounts WHERE id = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param('i', $id);

                $stmt->execute();

                $stmt->bind_result($adminlevel);

                $stmt->store_result();

                $result = null;
                if($stmt->num_rows === 1)
                {
                    while($stmt->fetch())
                    {
                        $result = $adminlevel;
                    }
                }
                $stmt->close();
            }
            $this->close();
            return $result;
        }

        public function changepassword($email, $password)
        {
            $query = "UPDATE accounts
            SET   password = ? WHERE email = ?";

            if($stmt = $this->connect($query))
            {
                $stmt->bind_param("ss",  $password, $email);

                $stmt->execute();

                $stmt->close();
            }
            $this->close();
        }
    }
?>
