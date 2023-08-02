<?php
trait Logger
{

    private static function log($msg)
    {
        $myfile = fopen("log.txt", "a");
        fwrite($myfile, $msg);
        fwrite($myfile, " @ " . date('Y-m-d H:i:s') . "\n");
        fclose($myfile);
    }
}