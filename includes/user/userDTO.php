<?php

class userDTO implements JsonSerializable
{
    private $id;

    private $email;

    private $password;

    private $usertype;


    public function __construct($id, $email, $password, $usertype)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->usertype = $usertype;
    }

    public function id()
    {
        return $this->id;
    }

    public function email()
    {
        return $this->email;
    }

    public function password()
    {
        return $this->password;
    }

    public function usertype()
    {
        return $this->usertype;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>