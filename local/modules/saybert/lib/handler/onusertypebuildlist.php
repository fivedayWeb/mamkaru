<?php
namespace Bitrix\Saybert\Handler;

class OnUserTypeBuildList{
    
        public static function handle(){
            $tmp = 1;
            return array(
                // уникальный идентификатор
                'USER_TYPE_ID' => 'iblock_element_test',
                // имя класса, методы которого формируют поведение типа
                'CLASS_NAME' => '\Bitrix\Saybert\Handler\OnUserTypeBuildList',
                // название для показа в списке типов пользовательских свойств
                'DESCRIPTION' => 'Связь с элементами инфоблока',
                // базовый тип на котором будут основаны операции фильтра
                'BASE_TYPE' => 'int',
            );
        }
}