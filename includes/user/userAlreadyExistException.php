<?php

namespace TheBalance\user;

/**
 * Excepción para cuando un usuario ya existe
 */
class userAlreadyExistException extends \Exception
{
    /**
     * Constructor
     */
    function __construct(string $message = "" , int $code = 0 , Throwable $previous = null )
    {
        parent::__construct($message, $code, $previous);
    }
}