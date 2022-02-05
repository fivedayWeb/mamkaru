<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('saybert');

$oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($oRequest->isAjaxRequest()) {
    $result = [
        'error' => false,
    ];

    if (isset($_REQUEST['id'])) {
        $res = CIBlockElement::getList([], ['ID' => $_REQUEST['id']], false, false,
            ['*', 'PROPERTY_CML2_LINK'])->getNext();
        $res_parent = CIBlockElement::getById($res['PROPERTY_CML2_LINK_VALUE'])->getNext();
        if ($res) {
            $_REQUEST['product'] = $res['NAME'];
            if ($res_parent) {
                $_REQUEST['link'] = $res_parent['DETAIL_PAGE_URL'];
            } else {
                $_REQUEST['link'] = $res['DETAIL_PAGE_URL'];
            }
        }
    }

    if (!empty($_REQUEST['name']) && !empty($_REQUEST['phone'])) {
        try {
            Bitrix\Main\Mail\Event::send(array(
				"EVENT_NAME" => "ORDER_TO_USER",
				"LID" => "s1",
				"C_FIELDS" => array(
					'NAME' => htmlspecialchars($_REQUEST['name']),
					'PHONE' => htmlspecialchars($_REQUEST['phone']),
					'PRODUCT_NAME' => htmlspecialchars($_REQUEST['product']),
                    'PRODUCT_LINK' => "<a href='" . $_REQUEST['link'] . "'>" . $_REQUEST['product'] . "</a>"
				),
			));
			/*'ORDER_TO_USER', SITE_ID,
                [
                    'NAME' => htmlspecialchars($_REQUEST['name']),
                    'PHONE' => htmlspecialchars($_REQUEST['phone']),
                    'PRODUCT_NAME' => htmlspecialchars($_REQUEST['product']),
                    'PRODUCT_LINK' => "<a href='" . $_REQUEST['link'] . "'>" . $_REQUEST['product'] . "</a>",
                ]
			);*/
        } catch (Exception $e) {
            $result = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    } else {
        $result = [
            'error' => true,
            'message' => 'Заполнены не все данные',
        ];
    }
    echo json_encode($result);
    die();

} else {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php');
    \Bitrix\Iblock\Component\Tools::process404("", true, true, true, '');
}