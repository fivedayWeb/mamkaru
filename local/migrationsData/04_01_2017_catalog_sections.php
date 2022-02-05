<?php
require __DIR__ . '/../modules/saybert/include/migration.php';
\Bitrix\Main\Loader::includeModule('saybert');
$pathToFiles = './files/04_01_2017_catalog_sections/';


$iblock  = \Bitrix\Saybert\Helpers\IBlock::getIblock('clothes');


$arFirstLevelSection = [
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Коляски',
        'CODE' => 'kolyaski',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-1.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-1.png',
        'UF_BACKGROUND_IMAGE' => $pathToFiles.'dropdown-1.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Автокресла',
        'CODE' => 'avtokresla',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-2.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-2.png',
        'UF_BACKGROUND_IMAGE' => $pathToFiles.'dropdown-2.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Детская мебель',
        'CODE' => 'detskaya_mebel',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-3.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-3.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Малыш и мама',
        'CODE' => 'malysh_i_mama',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-4.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-4.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Одежда',
        'CODE' => 'odezhda',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-5.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-5.png',
        'UF_BACKGROUND_IMAGE' => $pathToFiles.'dropdown-3.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Обувь',
        'CODE' => 'obuv',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-6.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-6.png',
        'UF_BACKGROUND_IMAGE' => $pathToFiles.'dropdown-4.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Игрушки',
        'CODE' => 'igrushki',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-7.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-7.png',
        'UF_BACKGROUND_IMAGE' => $pathToFiles.'dropdown-5.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
    [
        'IBLOCK_ID' => $iblock['ID'],
        'NAME' => 'Товары для школы',
        'CODE' => 'tovary_dlya_shkoly',
        'UF_MENU_ICON' => $pathToFiles.'head-icon-8.png',
        'UF_MENU_ICON_ACTIVE' => $pathToFiles.'head-pink-icon-8.png',
        'UF_BACKGROUND_IMAGE' => $pathToFiles.'dropdown-6.png',
        'PICTURE' => $pathToFiles.'category-card.png',
    ],
];

$arFirstLevelSectionIds = [];

foreach($arFirstLevelSection as $arSection){
    if(!empty($arSection['UF_MENU_ICON'])){
        $fileID = \CFile::SaveFile(\CFile::MakeFileArray($arSection['UF_MENU_ICON']));
        $arSection['UF_MENU_ICON'] = $fileID;
    }
    if(!empty($arSection['UF_MENU_ICON_ACTIVE'])){
        $fileID = \CFile::SaveFile(\CFile::MakeFileArray($arSection['UF_MENU_ICON_ACTIVE']));
        $arSection['UF_MENU_ICON_ACTIVE'] = $fileID;
    }
    if(!empty($arSection['UF_BACKGROUND_IMAGE'])){
        $fileID = \CFile::SaveFile(\CFile::MakeFileArray($arSection['UF_BACKGROUND_IMAGE']));
        $arSection['UF_BACKGROUND_IMAGE'] = $fileID;
    }
    if(!empty($arSection['PICTURE'])){
        $fileID = \CFile::SaveFile(\CFile::MakeFileArray($arSection['PICTURE']));
        $arSection['PICTURE'] = $fileID;
    }
    $oMigrationSection = new \Bitrix\Saybert\Migrations\IblockSection('refs #18',"Секция {$arSection['NAME']}",$arSection );
    $arFirstLevelSectionIds[] = $oMigrationSection->add();
}

$arSecondLevelSections = [
    $arFirstLevelSectionIds[0] => [
        ['NAME' => 'Классика',],
        ['NAME' => '2 в 1',],
        ['NAME' => '3 в 1',],
        ['NAME' => 'Трансформеры',],
        ['NAME' => 'Прогулочные',],
        ['NAME' => 'Трости',],
    ],
    $arFirstLevelSectionIds[1] => [
        ['NAME' => 'Автокресла 0-13 кг',],
        ['NAME' => 'Автокресла 0-18 кг',],
        ['NAME' => 'Автокресла 9-18 кг',],
        ['NAME' => 'Автокресла 9-25 кг',],
        ['NAME' => 'Автокресла от 15 кг',],
        ['NAME' => 'Автокресла от 22 кг',],
    ],
    $arFirstLevelSectionIds[2] => [
        ['NAME' => 'Кроватки',],
        ['NAME' => 'Комоды',],
        ['NAME' => 'Шкафы',],
        ['NAME' => 'Детские комнаты',],
        ['NAME' => 'Матрацы ортопелические',],
        ['NAME' => 'Аксессуары для детской комнаты',],
        ['NAME' => 'Комплекты постельного белья',],
        ['NAME' => 'Стулья для кормления',],
        ['NAME' => 'Манежи',],
        ['NAME' => 'Ходунки',],
        ['NAME' => 'Шезлонги',],
        ['NAME' => 'Детские столы и стулья',],
        ['NAME' => 'Качели',],
        ['NAME' => 'Ворота и барьеры',],
    ],
    $arFirstLevelSectionIds[3] => [
        ['NAME' => 'Выписка из роддома',],
        ['NAME' => 'Техника',],
        ['NAME' => 'Косметика для малышей',],
        ['NAME' => 'Уход и косметика для мам',],
        ['NAME' => 'Белье для мам',],
        ['NAME' => 'Товары для кормления',],
        ['NAME' => 'Подгузники',],
        ['NAME' => 'Гигиена и уход',],
        ['NAME' => 'Прорезыватели',],
        ['NAME' => 'Ванны и купание',],
        ['NAME' => 'Горшки детские и другое',],
        ['NAME' => 'Бытовая химия',],
        ['NAME' => 'Защита в доме',],
    ],
    $arFirstLevelSectionIds[4] => [
        ['NAME' => 'Школьная форма',],
        ['NAME' => 'Одежда для новорожденных',],
        ['NAME' => 'Одежда для мальчиков',],
        ['NAME' => 'Одежда для девочек',],
        ['NAME' => 'Аксессуары',],
        ['NAME' => 'Нижнее белье',],
        ['NAME' => 'Носки, колготки для малышей',],
    ],
    $arFirstLevelSectionIds[5] => [
        ['NAME' => 'Босоножки, сандалии',],
        ['NAME' => 'Туфли',],
        ['NAME' => 'Кроссовки',],
        ['NAME' => 'Ботинки',],
        ['NAME' => 'Сапоги',],
    ],
    $arFirstLevelSectionIds[6] => [
        ['NAME' => 'Для малышей',],
        ['NAME' => 'Для мальчиков',],
        ['NAME' => 'Для девочек',],
        ['NAME' => 'Конструкторы',],
        ['NAME' => 'Творчество и развитие',],
        ['NAME' => 'Книги и раскраски',],
        ['NAME' => 'Для  улицы',],
        ['NAME' => 'Электромобили',],
        ['NAME' => 'Велосипеды. самокаты',],
        ['NAME' => 'Спортивные комплексы и тренажеры',],
    ],
    $arFirstLevelSectionIds[7] => [
        ['NAME' => 'Школьная форма',],
        ['NAME' => 'Ранцы',],
        ['NAME' => 'Пеналы, сменки',],
        ['NAME' => 'Сопутствующие товары',],
    ],
];

foreach($arSecondLevelSections as $parentSectionID => $arSections){
    foreach($arSections as $arSection ) {
        $arTmpSection = [
            'IBLOCK_ID' => $iblock['ID'],
            'NAME' => $arSection['NAME'],
            'CODE' => \Bitrix\Saybert\Tools\Text::str2url($arSection['NAME']),
            'IBLOCK_SECTION_ID' => $parentSectionID
        ];
        $oMigrationSection = new \Bitrix\Saybert\Migrations\IblockSection('refs #18', "Секция {$arTmpSection['NAME']}", $arTmpSection);
        $oMigrationSection->add();
    }
}