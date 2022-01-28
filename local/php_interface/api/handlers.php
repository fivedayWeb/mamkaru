<?
use Bitrix\Main; 

Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved', ['Order', 'OnSaleOrderBeforeSavedHandler']);
Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderSaved', ['Order', 'OnSaleOrderSavedHandler']);
Main\EventManager::getInstance()->addEventHandler('sale', 'OnOrderNewSendEmail', ['Order', 'OnOrderNewSendEmailHandler']);
Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleStatusOrderChange', ['Order', 'OnSaleStatusOrderChangeHandler']);

// Main\EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIblockElementUpdate', ['\Custom\Catalog', 'OnBeforeIblockElementUpdate']);
Main\EventManager::getInstance()->addEventHandler('iblock', 'OnAfterIblockElementUpdate', ['\Custom\Catalog', 'OnAfterIblockElementUpdate']);
Main\EventManager::getInstance()->addEventHandler('iblock', 'OnProductAdd', ['\Custom\Catalog', 'OnProductAdd']);

Main\EventManager::getInstance()->addEventHandler('main', 'OnBeforeProlog', ['Order', 'OnBeforeProlog']);