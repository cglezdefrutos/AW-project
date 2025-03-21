<?php

namespace TheBalance\event;

/**
 * Excepción para cuando se intenta eliminar un evento que tiene participantes
 */
class eventHasParticipantsException extends \Exception
{
    /**
     * Constructor
     */
    function __construct(string $message = "" , int $code = 0 , Throwable $previous = null )
    {
        parent::__construct($message, $code, $previous);
    }
}