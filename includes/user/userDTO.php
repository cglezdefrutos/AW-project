<?php

class userDTO
{
    private $id;

    private $username;

    private $password;

    private $type;


    public function __construct($id, $username, $password, $type)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->type = $type;
    }

    public function id()
    {
        return $this->id;
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }

    public function type()
    {
        return $this->type;
    }
}
?>