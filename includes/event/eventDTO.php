<?php
    class eventDTO implements JsonSerializable
    {
        private $id;
        private $name;
        private $desc;
        private $date;
        private $price;
        private $location;
        private $category;
        private $capacity;
        private $email_provider;

        public function __construct($id, $name, $desc, $date, $price, $location, $capacity, $category, $email_provider)
        {
            $this->id = $id;
            $this->name = $name;
            $this->desc = $desc;
            $this->date = $date;
            $this->price = $price;
            $this->location = $location;
            $this->capacity = $capacity;
            $this->category = $category;
            $this->email_provider = $email_provider;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getDesc()
        {
            return $this->desc;
        }

        public function getDate()
        {
            return $this->date;
        }

        public function getPrice()
        {
            return $this->price;
        }

        public function getLocation()
        {
            return $this->location;
        }

        public function getCategory()
        {
            return $this->category;
        }

        public function getCapacity()
        {
            return $this->capacity;
        }

        public function getEmailProvider()
        {
            return $this->email_provider;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function setDesc($desc)
        {
            $this->desc = $desc;
        }

        public function setDate($date)
        {
            $this->date = $date;
        }

        public function setPrice($price)
        {
            $this->price = $price;
        }

        public function setLocation($location)
        {
            $this->location = $location;
        }

        public function setCategory($category)
        {
            $this->category = $category;
        }        

        public function setCapacity($capacity)
        {
            $this->capacity = $capacity;
        }

        public function setEmailProvider($email_provider)
        {
            $this->email_provider = $email_provider;
        }
        
        public function jsonSerialize()
        {
            return get_object_vars($this);
        }
    }
?>