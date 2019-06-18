<?php
class Logger
{
    public function addLog($logItem, $type)
    {
        file_put_contents('./log_'.date("j.n.Y").'.txt', date('Y-m-d H:i').'     '.$type.'    '. $logItem.PHP_EOL, FILE_APPEND);
    }
}
