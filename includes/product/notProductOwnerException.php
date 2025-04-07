<?php

namespace TheBalance\product;

/**
 * Excepción para cuando un proveedor intenta eliminar un producto que no le pertenece
 */
class notProductOwnerException extends \Exception
{
    /**
     * Constructor
     */
    function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
