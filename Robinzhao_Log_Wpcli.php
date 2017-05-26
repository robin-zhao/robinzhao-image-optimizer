<?php

/**
 * Description of Robinzhao_Log
 *
 * @author Robin Zhao <boborabit@qq.com>
 */
class Robinzhao_Log_Wpcli implements Robinzhao_Log
{
    public function success($string)
    {
        WP_CLI::success($string);
    }

    public function error($string)
    {
        WP_CLI::error($string);
    }

    public function log($string)
    {
        WP_CLI::log($string);
    }
}
