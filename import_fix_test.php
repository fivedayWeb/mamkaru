<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_GET['import']) {
    if (!$_GET['pos']) {
        $pos = 1;
    } else {
        $pos = $_GET['pos'];
    }

    set_time_limit(10 * 60);

    CModule::IncludeModule('iblock');
    CModule::IncludeModule('grishchenk.onecimportmod');

    $dbElement = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => 469,
        ],
        false,
        [
            'nPageSize' => 500,
            'iNumPage' => (int)$pos,
        ],
        [
            'ID',
        ]
    );

    while ($element = $dbElement->Fetch()) {
        $elementId = $element['ID'];

        \Grishchenk\OneCImportMod\Helpers\IblockElementHelper::processElement($elementId);
    }

    if (($pos * 500) > (int)$dbElement->selectedRowsCount()) {
        $dbSection = CIBlockSection::GetList(
            [],
            [
                'IBLOCK_ID' => 469,
                'ACTIVE' => 'Y',
                '!XML_ID' => false,
            ]
        );
        while ($section = $dbSection->Fetch()) {
            $sectionID = $section['ID'];

            \Grishchenk\OneCImportMod\Helpers\IblockSectionHelper::processSection($sectionID);
        }
        echo 'success';
        exit();
    } else {?>
        <script>
            window.location = 'import_fix_test.php' + '?import=true&pos=' + '<?=$pos + 1?>';
        </script>
   <?}
}

?>
<form action="" method="POST">
    <input type="submit" value="Запустить">
</form>