<?php

namespace Hshn\NpmBundle\Exception;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    public function __construct($message = '', \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
