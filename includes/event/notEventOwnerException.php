<?php

namespace TheBalance\event;

/**
 * Excepción para cuando un proveedor intenta eliminar un evento que no le pertenece
 */
class notEventOwnerException extends \Exception
{
    /**
     * Constructor
     */
    function __construct(string $message = "" , int $code = 0 , Throwable $previous = null )
    {
        parent::__construct($message, $code, $previous);
    }
}