<? require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("");

Cmodule::IncludeModule('iblock');

$result = [];
?>
    <style>
        ul, li {
            margin: auto !important;
            padding-left: 25px !important;
        }
    </style>
<?


function getActiveProps($id)
{
    $list = [];
    $rsElement = CIBlockElement::getList(
        [],
        ['IBLOCK_SECTION_ID' => $id, 'ACTIVE' => 'Y'],
        false,
        false,
        []
    );

    while ($arElement = $rsElement->getNextElement()) {
        $fields = $arElement->getFields();
        $offerElement = CIBlockElement::getList(
            [],
            ['ACTIVE' => 'Y', 'IBLOCK_ID' => 470, 'PROPERTY_CML2_LINK' => $fields['ID']],
            false,
            false,
            []
        );

        while ($arElement2 = $offerElement->getNextElement()) {
            foreach ($arElement2->getProperties() as $property) {
                if (!empty($property['VALUE'])) {
                    $props[$property['ID']] = array('NAME' => $property['NAME'], 'CODE' => $property['CODE']);
                }
            }
        }


        foreach ($arElement->getProperties() as $property) {
            if (!empty($property['VALUE'])) {
                $props[$property['ID']] = array('NAME' => $property['NAME'], 'CODE' => $property['CODE']);
            }
        }
    }

    return $props;
}


function getSections($id)
{
    $list = [];
    $rsSections2 = CIBlockSection::GetList(
        [],
        ['IBLOCK_ID' => 469, 'SECTION_ID' => $id, 'ACTIVE' => 'Y'],
        false,
        ['ID', 'NAME', 'CODE']
    );

    while ($arSection2 = $rsSections2->Fetch()) {
        $el = $arSection2;
        $el['PROPS'] = getActiveProps($arSection2['ID']);
        $el['CHILDS'] = getSections($arSection2['ID']);
        $list[] = $el;
    }

    return $list;
}

function htmlItem($item)
{
    echo "<ul>";
    echo '<li><span>' . $item['NAME'] . '</span></li>';
    if (count($item['PROPS'])) {
        echo 'Свойства:';
        echo "<ul>";
        foreach ($item['PROPS'] as $prop) {
            echo '<li>' . $prop['NAME'] . '(' . $prop['CODE'] . ')' . '</li>';
        }
        echo "</ul>";
    }
    if (count($item['CHILDS'])) {
        echo '<li>';
        foreach ($item['CHILDS'] as $child) {
            htmlItem($child);
        }
        echo '</li>';
    }
    echo "</ul>";
}

$rsSections = CIBlockSection::GetList(
    [],
    ['IBLOCK_ID' => 469, 'DEPTH_LEVEL' => 1, 'ACTIVE' => 'Y'],
    false,
    ['ID', 'NAME', 'CODE']
);

while ($arSection = $rsSections->Fetch()) {

    $arSection['PROPS'] = getActiveProps($arSection['ID']);
    $arSection['CHILDS'] = getSections($arSection['ID']);
    $result[] = $arSection;
}

foreach ($result as $item) {
    htmlItem($item);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>