<?

use Bitrix\Main;
use Bitrix\Sale;
use Bitrix\Main\Mail\Event;

class Order
{
    protected $order = null;

    public function __construct($order)
    {
        \CModule::IncludeModule('sale');

        $this->order = $order;
    }

    protected static $_instance = null;

    public static function Instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getId()
    {
        return $this->order->getId();
    }

    public function getPersonTypeId()
    {
        return $this->order->getPersonTypeId();
    }

    public function getPropertyCollection()
    {
        return $this->order->getPropertyCollection();
    }

    public function getPropPayInvoiceID()
    {
        return 27;
    }

    public function getPropCompanyNameID()
    {
        return 12;
    }

    public function getPropPhoneID()
    {
        return 10;
    }

    public function getPayInvoiceTemplateFilePath()
    {
        return '/local/php_interface/files/invoice.xlsx';
    }

    public function getData()
    {
        $propertyCollection = $this->order->getPropertyCollection();
        $dateCreate = new DateTimeHelper(strtotime($this->order->getField('DATE_INSERT')));
        $data = array(
            'ORDER_ID' => $this->order->getId(),
            'PRICE' => number_format($this->order->getPrice(), 2, ',', ' '),
            'DATE_HUMAN' => $dateCreate->format('d MONTH_RUS_R Y г.'),
            'PRICE_HUMAN' => Number2Word_Rus($this->order->getPrice()),
            'PERSON_NAME' => $propertyCollection->getItemByOrderPropertyId($this->getPropCompanyNameID())->getValue(),
            'PERSON_PHONE' => $propertyCollection->getItemByOrderPropertyId($this->getPropPhoneID())->getValue(),
        );
        return $data;
    }

    public function replace($str)
    {
        foreach ($this->getData() as $key => $value) {
            $str = str_replace('#' . $key . '#', $value, $str);
        }
        return $str;
    }

    public function generatePayInvoice()
    {
        // read file
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT'] . $this->getPayInvoiceTemplateFilePath());

        // edit file
        $worksheet = $spreadsheet->getActiveSheet();
        $arCells = array('B13', 'B17', 'F21', 'AG25', 'AK25', 'AK27', 'B29', 'B30');
        foreach ($arCells as $cellCode) {
            $cell = $worksheet->getCell($cellCode);
            $value = $cell->getValue();
            $newValue = $this->replace($value);
            $cell->setValue($newValue);
        }

        // save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $path = tempnam(sys_get_temp_dir(), 'PAY_INVOICE_') . '.xlsx';
        $writer->save($path);

        return $path;
    }
	/*public static function OnOrderAddHandler($ID, $arFields) {
      self::OnSaleStatusOrderHandler($ID, $arFields['STATUS_ID']);
	}*/
	public static function OnSaleStatusOrderChangeHandler(Main\Event $event)
	{
		$parameters = $event->getParameters();
		if ($parameters['VALUE'] === 'OP')
		{
			/** @var \Bitrix\Sale\Order $order */
			$order = $parameters['ENTITY'];
			$propertyCollection = $order->getPropertyCollection();
			$newProps = $propertyCollection->getArray();
			$id = $order->getId();

			$basket = Sale\Order::load($id)->getBasket();
			foreach ($basket as $basketItem) {
				$orderList[] = $basketItem->getField('NAME') . ' - ' . $basketItem->getQuantity();
			}
			$orderList[] = 'Итого: ' . $basket->getPrice();
			
			
			$arPayments = reset(self::getArPayments($id));
			$url = $arPayments['URL'];
			$title = $arPayments['TITLE'];

			$orderList = implode('<br>', $orderList);
			$userName = $propertyCollection->getProfileName()->getValue();
			$userEmail = $propertyCollection->getUserEmail()->getValue();
			
			$orderNumber = $id;
			$link = "<a href='$url' target='_blank'>$title</a>";

			if($url) {
				Event::send([    
					"EVENT_NAME" => "WF_SEND_PAYMENT_LINK",
					"LID" => "s1",
					"MESSAGE_ID" => 97,
					"C_FIELDS" => [
						"LINK_TO_PAYMENT" => $url,
						"ORDER_NUMBER" => $orderNumber,
						"ORDER_USER" => $userName,
						"ORDER_LIST" => $orderList
					]
				]);
			}
		}

		return new \Bitrix\Main\EventResult(
		  \Bitrix\Main\EventResult::SUCCESS
		);
	}
    public static function OnSaleOrderBeforeSavedHandler(Main\Event $event)
    {
		
        $order = new self($event->getParameter("ENTITY"));
        //self::sendLink($order);
    }

    public static function OnSaleOrderSavedHandler(Main\Event $event)
    {

        $order = new self($event->getParameter("ENTITY"));
        if ($order->getPersonTypeId() != 2) {
            return;
        }

        $fileID = CFile::SaveFile(CFile::MakeFileArray($order->generatePayInvoice()), 'Order/PayInvoice');
        self::SetPropertyValue($order->getId(), $order->getPropPayInvoiceID(), $fileID);

        $arOrderProperties = array();
        $resProperties = CSaleOrderPropsValue::GetList(
            array(),
            array(
                'ORDER_ID' => $order->getId(),
            )
        );
        while ($arItemProp = $resProperties->Fetch()) {
            $arOrderProperties[$arItemProp['CODE']] = $arItemProp;
        }
        // if ($event->getParameter("IS_NEW")) {
        $arEventFields = array(
            'ORDER_ID' => $order->getId(),
            'EMAIL' => $arOrderProperties['EMAIL']['VALUE'],
        );
        if (!CEvent::Send("PAY_INVOICE", 's1', $arEventFields, 'Y', '', array($fileID))) {
            Log::add([__FILE__, __LINE__, $arEventFields], 'Order/OnSaleOrderSavedHandler');
        }
        // }
    }

    public static function SetPropertyValue($orderID, $propID, $value)
    {
        if (!$orderID || !$propID || !CModule::IncludeModule('sale')) {
            return false;
        }

        $res = CSaleOrderPropsValue::GetList(
            array(),
            array(
                'ORDER_ID' => $orderID,
                'ORDER_PROPS_ID' => $propID
            )
        );
        $arProp = $res->Fetch();
        if ($arProp) {
            $arFields = array(
                'VALUE' => $value,
            );
            return CSaleOrderPropsValue::Update($arProp['ID'], $arFields);
        }

        $res = CSaleOrderProps::GetList(array(), array('ID' => $propID));
        $arProp = $res->Fetch();
        if (!$arProp) {
            return false;
        }

        $arFields = array(
            'NAME' => $arProp['NAME'],
            'CODE' => $arProp['CODE'],
            'ORDER_PROPS_ID' => $arProp['ID'],
            'ORDER_ID' => $orderID,
            'VALUE' => $value,
        );
        return CSaleOrderPropsValue::Add($arFields);
    }

    public static function OnOrderNewSendEmailHandler($orderId, &$eventName, &$arFields)
    {
        if ($eventName != 'SALE_NEW_ORDER') {
            return;
        }
        
        global $USER;
        $ID = $arFields["ID"];
        $arUser = CUser::GetByID($ID)->Fetch();
        $arResult = $USER->SimpleRegister($arUser['EMAIL']);
        
        $arOrder = CSaleOrder::GetByID($orderId);
        if ($arOrder['DELIVERY_ID'] == 3 && $arOrder['PAY_SYSTEM_ID'] == 1) {
            $arFields['RESERVE_INFO'] = 'Внимание: Ваш заказ резервируется на 2 суток с момента его оформления!';
            $arFields['PASSWORD'] = $arUser['PASSWORD'];
        } else {
            $arFields['RESERVE_INFO'] = '';
        }
    }

    public static function OnBeforeProlog()
    {
        if (preg_match('/^\/bitrix\/admin\/sale_order_(view|edit)\.php/', Page::getURI())) {
            CModule::IncludeModule('sale');

            $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Order::getRegistryType());

            $orderId = $_REQUEST['ID'];

            $paymentClassName = $registry->getPaymentClassName();
            $orderClassName = $registry->getOrderClassName();

            $order = $orderClassName::load($orderId);

            $listPayments = $paymentClassName::getList([
                'select' => [
                    'ID',
                    'PAY_SYSTEM_NAME',
                    'PAY_SYSTEM_ID',
                    'ACCOUNT_NUMBER',
                    'ORDER_ID',
                    'PAID',
                    'SUM',
                    'CURRENCY'
                ],
                'filter' => [
                    'ORDER_ID' => $orderId
                ]
            ]);

            $arPayments = [];
            while ($payment = $listPayments->fetch()) {
                if ($payment['PAY_SYSTEM_ID'] == 9 && $payment['PAID'] == 'N') {
                    $hash = $order->getHash();
                    $arParams = [
                        'ORDER_ID' => $orderId,
                        'PAYMENT_ID' => $payment['ACCOUNT_NUMBER'],
                        'HASH' => $hash,
                    ];
                    $url = '/personal/order/payment/?' . http_build_query($arParams);
                    $title = 'Оплатить ' . Price::format($payment['SUM']) . ' ' . $payment['CURRENCY'];

                    $arPayments[] = [
                        'URL' => $url,
                        'TITLE' => $title,
                    ];
                }
            }

            if (!empty($arPayments)) {
                ob_start();
                ?>
                <script type="text/javascript">
                    document.addEventListener("DOMContentLoaded", function () {
                        <?foreach ($arPayments as $payment):?>
                        var elem = document.createElement('a');
                        elem.setAttribute('href', '<?=$payment['URL']?>');
                        elem.setAttribute('class', 'adm-btn');
                        var text = document.createTextNode('<?=$payment['TITLE']?>');
                        elem.appendChild(text);
                        document.getElementsByClassName('adm-detail-toolbar-right')[0].appendChild(elem);
                        <?endforeach;?>
                    });
                </script>
                <?
                $script = ob_get_clean();

                global $APPLICATION;
                $APPLICATION->AddHeadString($script);
            }
        }
    }

    protected static function sendLink($entity)
    {
        $order = $entity;
        $propertyCollection = $entity->getPropertyCollection();
        $newProps = $propertyCollection->getArray();
        $id = $entity->getId();

        $db_sales = CSaleOrder::GetList(
            array(
                "DATE_INSERT" => "ASC"
            ),
            array(
                "ID" => $id
            ),
            false,
            false,
            array(
                "ID",
                "PROPERTY_VAL_BY_CODE_SEND_PAYMENT_LINK"
            )
        );
        $oldProp = $db_sales->Fetch();

        $newProp = $newProps['properties'][array_search('SEND_PAYMENT_LINK',
            array_column($newProps['properties'], 'CODE'))];

        if ((reset($newProp['VALUE']) !== $oldProp['PROPERTY_VAL_BY_CODE_SEND_PAYMENT_LINK']) && reset($newProp['VALUE']) == 'Y') {

            $basket = Sale\Order::load($id)->getBasket();
            foreach ($basket as $basketItem) {
                $orderList[] = $basketItem->getField('NAME') . ' - ' . $basketItem->getQuantity();
            }
            $orderList[] = 'Итого: ' . $basket->getPrice();
            $arPayments = reset(self::getArPayments($id));
            $url = $arPayments['URL'];
            $title = $arPayments['TITLE'];

            $orderList = implode('<br>', $orderList);
            $userName = $propertyCollection->getProfileName()->getValue();
            $userEmail = $propertyCollection->getUserEmail()->getValue();
			
            $orderNumber = $id;
            $link = "<a href='$url' target='_blank'>$title</a>";

            if($url) {
				/*Bitrix\Main\Mail\Event::send([    
					"EVENT_NAME" => "WF_SEND_PAYMENT_LINK",
					"LID" => "s1",
					"MESSAGE_ID" => 97,
					"C_FIELDS" => [
						"LINK_TO_PAYMENT" => $url,
						"EMAIL" => "webmaster@fiveday.ru",
						"ORDER_NUMBER" => $id
					]
				]);*/
				
                CEvent::Send("WF_SEND_PAYMENT_LINK", 's1',					
					array(
                        "ORDER_LIST" => $orderList,
                        "LINK_TO_PAYMENT" => $url,
                        "ORDER_USER" => $userName,
                        "EMAIL" => "webmaster@fiveday.ru",
                        "ORDER_NUMBER" => $orderNumber,
					),"Y","97"
                );
            }
        }
    }

    protected static function getArPayments($id)
    {
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Order::getRegistryType());

        $orderId = $id;

        $paymentClassName = $registry->getPaymentClassName();
        $orderClassName = $registry->getOrderClassName();

        $order = $orderClassName::load($orderId);

        $listPayments = $paymentClassName::getList([
            'select' => [
                'ID',
                'PAY_SYSTEM_NAME',
                'PAY_SYSTEM_ID',
                'ACCOUNT_NUMBER',
                'ORDER_ID',
                'PAID',
                'SUM',
                'CURRENCY'
            ],
            'filter' => [
                'ORDER_ID' => $orderId
            ]
        ]);

        $arPayments = [];
        while ($payment = $listPayments->fetch()) {
            if ($payment['PAY_SYSTEM_ID'] == 9 && $payment['PAID'] == 'N') {
                $hash = $order->getHash();
                $arParams = [
                    'ORDER_ID' => $orderId,
                    'PAYMENT_ID' => $payment['ACCOUNT_NUMBER'],
                    'HASH' => $hash,
                ];
                $url = 'http://' . $_SERVER['HTTP_HOST'] . '/personal/order/payment/?' . http_build_query($arParams);
                $title = 'Оплатить ' . Price::format($payment['SUM']) . ' ' . $payment['CURRENCY'];

                $arPayments[] = [
                    'URL' => $url,
                    'TITLE' => $title,
                ];
            }
        }
        return $arPayments;
    }
}