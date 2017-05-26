<?php

/**
 * Description of Robinzhao_Log
 *
 * @author Robin Zhao <boborabit@qq.com>
 */
interface Robinzhao_Log
{
    public function success($string);

    public function error($string);

    public function log($string);
}
