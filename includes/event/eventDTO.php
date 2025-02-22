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

        public function id()
        {
            return $this->id;
        }

        public function name()
        {
            return $this->name;
        }

        public function desc()
        {
            return $this->desc;
        }

        public function date()
        {
            return $this->date;
        }

        public function price()
        {
            return $this->price;
        }

        public function location()
        {
            return $this->location;
        }

        public function category()
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