<?php

namespace easyPM\Exceptions;

use Exception;

/**
 *
 */
class NotFoundException extends Exception
{
    public function __construct($error = null)
    {
        $errmsg = $error ?: 'Not Found Exception!';
        parent::__construct($errmsg);
    }
/*
    public function getMessage() : string
    {
        return $this->message;
    }*/
}

?>
