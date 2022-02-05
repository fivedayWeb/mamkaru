#### Ссылка для восстановления резервной копии ####
http://mamkaru.ru/bitrix/backup/mamkaru.ru_20220119_144241_full_b5b9e650.enc.gz

## Ядро 1С-Битрикс ##
В папке bitrix нам понядобятся изменить только два файла
1. `bitrix/modules/sale/lib/basketbase.php` 
2. `bitrix/components/bitrix/sale.order.ajax/class.php`
---
### 1. basketbase.php ###
В первом файле мы заменяем функцию `loadItemsForFUser()` на 107 строчке
<pre>
public static function loadItemsForFUser($fUserId, $siteId) 
{
    /** @var BasketBase $basket */
    $basket = static ::create($siteId);

    $basket -> setFUserId($fUserId);

    $basket -> isLoadForFUserId = true;

    /** @var BasketBase $collection */
    return $basket -> loadFromDb([
        "FUSER_ID" => $fUserId,
        "=LID" => $siteId,
        "ORDER_ID" => null
    ]);
}
</pre>
на 
<pre>
public static function loadItemsForFUser($fUserId, $siteId) 
{
    /** @var BasketBase $basket */
    $basket = static ::create($siteId);

    $basket -> setFUserId($fUserId);

    $basket -> isLoadForFUserId = true;

    /** @var BasketBase $collection */
    $filter = [
        "FUSER_ID" => $fUserId,
        "ORDER_ID" => [null,1],
    ];
    if(!empty($_REQUEST['SITE_ID']) && in_array($_REQUEST['SITE_ID'], ['4','1','2','3'])){
        $filter["=SORT"] = $_REQUEST['SITE_ID'];
    }
    else {
        $filter["=SORT"] = ['4','1','2','3'];
    }
    if(!empty($_REQUEST['KASSA']) && in_array($_REQUEST['KASSA'], ['4','1','2','3'])){
        $filter["=SORT"] = $_REQUEST['KASSA'];
    }
    if(!empty($_REQUEST['order']['KASSA']) && in_array($_REQUEST['order']['KASSA'], ['4','1','2','3'])){
        $filter["=SORT"] = $_REQUEST['order']['KASSA'];
    }
    $filter["=LID"] = $siteId;
    return $basket->loadFromDb($filter);
}
</pre>
###2. class.php ###
В втором файле есть функция `obtainPaySystem()`<br><br>
После строки:
<pre>$paySystemList = $this->arParams['DELIVERY_TO_PAYSYSTEM'] === 'p2d' ? $this->arActivePaySystems : $this->arPaySystemServiceAll;</pre>
Добавить:

- удаление лишнего ИП(организацию) из списка доступных:
<pre>
if($_REQUEST['SITE_ID'] == 2 || $_REQUEST['order']['KASSA'] == 2){
    unset($paySystemList[13]);
} else {
    unset($paySystemList[14]);
}
</pre>

- автовыбор платежной системы:
<pre>
if($_REQUEST['SITE_ID'] == 2 || $_REQUEST['order']['KASSA'] == 2){
    $paymentId = 14;
} else {
    $paymentId = 13;
}
</pre>
где `13` - это ID платежной системы, которое мы ставим по-умолчанию,<br>
соответственно `14` - это ID необходимой нам платежной системы

---

##Структура сайта ##
Добавляется новая папка в корневом разделе `cart` или `basket` в зависимости,<br>
что у Вас уже имеется на «борту», которая состоит из 4 файлов:
`index.php`, `ajax.php`, `check.php` и `.section.php`

В данном репозитории используется Шаблон от АСПРО - `aspro_mshop` в папке `local`

- В локальном компоненте `bitrix:sale.order.ajax` создаем **componentTemplate** `order`
- В локальном компоненте `bitrix:basket.basket.line` создаем **componentTemplate** `inheader`
