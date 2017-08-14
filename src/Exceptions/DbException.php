<?php
namespace easyPM\Exceptions;

use Exception;

class DbException extends Exception
{
    public function __construct($error = null)
    {
        $errmsg = $error ?: 'Database Exception!';
        parent::__construct($errmsg);
    }
}
