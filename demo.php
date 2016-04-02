<?php
/**
 * @file        demo.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://blog.xu42.cn/
 */

require_once 'OneTitleMsg.php';

error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);

// 开始抓取的序号
$imdb_start = 1;

// 停止抓取的序号
$imdb_end = 1000;

for($i = $imdb_start; $i < $imdb_end; $i++)
{
    $start_time = microtime(TRUE);

    $title = 'tt'.sprintf("%07d", $i);
    $one_title_msg = new OneTitleMsg($title);
    $text = json_encode($one_title_msg->getMsgOfOneTitle());
    $file_name = $title . '.json';

    $file = fopen($file_name, "w");
    if(!$file) die("创建文件失败，终止程序");
    if($file) echo '创建&';
    fwrite($file, $text);
    fclose($file);

    $file_end_time = microtime(TRUE);
    $time_all = $file_end_time - $start_time;

    echo '写入:' . $file_name . ', 耗时:'. $time_all .'，文件长度:' . strlen($text) . "\n";
    usleep(100);
}
