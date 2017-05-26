<?php

/**
 * Description of Robinzhao_Log
 *
 * @author Robin Zhao <boborabit@qq.com>
 */
class Robinzhao_Log_File implements Robinzhao_Log
{

    protected function write($file, $string)
    {
        $handle = fopen(__DIR__ . '/logs/' . $file, 'a');
        fwrite($handle, date('Y-m-d H:i:s ') . $string . "\n");
        fclose($handle);
    }

    public function success($string)
    {
        $this->write(__FUNCTION__ . '.log', $string);
    }

    public function error($string)
    {
        $this->write(__FUNCTION__ . '.log', $string);
    }

    public function log($string)
    {
        $this->write(__FUNCTION__ . '.log', $string);
    }
}
