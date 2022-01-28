<?php
namespace Bitrix\Saybert\Tools;

class Log{
    public static function toLog($header,$content,$path = false){
        $writingContent = "===============================================================================\n";
        $writingContent .= "DATE: " . date('d.m.Y H:i:s')."\n\r";
        if(!empty($header) && is_string($header))
            $writingContent .= "header: ".$header."\n\r";
        if(is_string($content) || is_numeric($content))
            $writingContent .= "content: ".$content."\n\r";
        elseif(is_array($content) || is_object($content))
            $writingContent .= "content: ".json_encode($content,JSON_PRETTY_PRINT||JSON_UNESCAPED_SLASHES||JSON_FORCE_OBJECT )."\n\r";
        elseif(is_bool($content))
            $writingContent .= "content: ".($content ? 'true' : 'false')."\n\r";
        else
            $writingContent .= "content: Undefinde type"."\n\r";
        $writingContent .= "===============================================================================\n\r\n\r";

        if(!$path)
            $path = $_SERVER['DOCUMENT_ROOT'].'/log/all.log';
        return file_put_contents($path,$writingContent,FILE_APPEND);
    }

    public static function debug($data){
        if(is_null($data))
            echo 'null';
        elseif(empty($data))
            echo 'empty';
        elseif($data ===true)
            echo 'true';
        elseif($data === false)
            echo "false";
        else{
            echo'<pre>';
            print_r($data);
            echo '</pre>';
        }
    }
}