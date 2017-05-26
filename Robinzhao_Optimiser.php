<?php

/**
 * Description of Robinzhao_Optimiser
 *
 * @author Robin Zhao <boborabit@qq.com>
 */
class Robinzhao_Optimiser
{
    private $log;

    public function __construct(Robinzhao_Log $log)
    {
        $this->log = $log;
    }

    public function run($limit)
    {
        exec('which mogrify', $output, $return_var);

        if ($return_var === 0) {
            $this->log->success('Imagemagick is installed.');
        } else {
            $this->log->error('Imagemagick is required!');
            return;
        }

        Robinzhao_DB::getInstance()->fillId();

        $cmd = "mogrify -strip -interlace Plane -quality 85%% -resize '768x432>' -sampling-factor 4:2:0 %s";

        $results = Robinzhao_DB::getInstance()->fetch($limit);
        foreach ($results as $result) {
            $files = Robinzhao_DB::getInstance()->getImages($result->ID);
            foreach ($files as $file) {
                $execute = sprintf($cmd, $file);
                $this->log->log('Executing: ' . sprintf($cmd, $file));
                exec($execute, $out, $rtn);
                if ($rtn === 1) {
                    $this->log->error($out);
                }
            }

            Robinzhao_DB::getInstance()->update($result->ID);
        }

        $this->log->success( "Done" );
    }
}
