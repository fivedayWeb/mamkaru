<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
$ext = 'wmv,wma,flv,vp6,mp3,mp4,aac,jpg,jpeg,gif,png,pdf,rtf';
$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "LINK_TO_PICTURE" => Array(
            "PARENT" => "BASE",
            "NAME" => 'Ссылка на файл',
            'TYPE' => 'FILE',
            "FD_EXT" => true,
            "FD_TARGET" => "F",
            "FD_UPLOAD" => true,
            "FD_MEDIALIB_TYPES" => Array(),
            "FD_USE_MEDIALIB" => true,
        ),
        "HEADER" => Array(
            "PARENT" => "BASE",
            "NAME" => 'Заголовок',
            'TYPE' => 'STRING',
            'DEFAULT' => 'Название',
        ),
        "TEXT" => Array(
            "PARENT" => "BASE",
            "NAME" => 'Текст',
            'TYPE' => 'STRING',
            "DEFAULT" => "",
            'COLS' => '50',
            'ROWS' => 10,
        ),
        'TEXT_URL' => array(
            "PARENT" => "BASE",
            "NAME" => 'Текст ссылки',
            'TYPE' => 'STRING',
        ),
        'URL' => array(
            "PARENT" => "BASE",
            "NAME" => 'Ссылка',
            'TYPE' => 'STRING',
        ),
    ),
);

?>
