<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Короткая карточка элемента",
	"DESCRIPTION" => "Короткая карточка элемента",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => "Короткая карточка элемента",
			"SORT" => 30,
		),
	),
);
?>