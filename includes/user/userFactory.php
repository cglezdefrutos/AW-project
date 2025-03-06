<?php
    require("userDAO.php");
    require("userMock.php");

    class userFactory
    {
        public static function CreateUser() : IUser
        {
            $userDAO = false;
            $config = "DAO";

            if ($config === "DAO")
            {
                $userDAO = new userDAO();
            }
            else
            {
                $userDAO = new userMock();
            }
            
            return $userDAO;
        }
    }
?>