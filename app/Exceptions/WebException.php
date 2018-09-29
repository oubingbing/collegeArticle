<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 11:57
 */

namespace App\Exceptions;


class WebException extends \Exception
{
    function __construct($msg='',$code)
    {
        parent::__construct($msg,$code);
    }
}