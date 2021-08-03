<?php

namespace App\Model\Exception\Author;

use Exception;

class AuthorNotFound extends Exception
{
    /**
     * @throws AuthorNotFound
     */
    public static function throwException()
    {
        throw new self('Author not found');
    }
}
