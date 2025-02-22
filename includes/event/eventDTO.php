<?php
    class eventDTO
    {
        private $id;
        private $name;
        private $desc;
        private $date;
        private $price;
        private $location;
        private $category;

        public function __construct($id, $name, $desc, $date, $price, $location, $category)
        {
            $this->id = $id;
            $this->name = $name;
            $this->desc = $desc;
            $this->date = $date;
            $this->price = $price;
            $this->location = $location;
            $this->category = $category;
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
    }
?>