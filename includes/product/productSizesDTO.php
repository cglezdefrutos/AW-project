<?php

namespace TheBalance\product;

/**
 * Data Transfer Object de las tallas de un producto
 */
class productSizesDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del producto
     */
    private $product_id;

    /**
     * @var array Diccionario de la talla y su stock
     */
    private $sizes = array();

    /**
     * Constructor
     */
    public function __construct($product_id, $sizes)
    {
        $this->product_id = $product_id;
        $this->sizes = $sizes;
    }

    /**
     * Getters
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    public function getSizes()
    {
        return $this->sizes;
    }

    public function getTotalStock()
    {
        $totalStock = 0;
        foreach ($this->sizes as $size => $stock) {
            $totalStock += $stock;
        }
        return $totalStock;
    }

    public function getStockBySize($size)
    {
        $stock = 0;
        if (array_key_exists($size, $this->sizes)) {
            $stock = $this->sizes[$size];
        }
        return $stock;
    }

    /**
     * Setters
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
    }
   
    /**
     * Método para serializar el objeto a JSON
     * 
     * @return array Datos del objeto en formato JSON
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }    
}