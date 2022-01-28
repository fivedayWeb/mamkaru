<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;

if (class_exists('saybert')) {
    return;
}

class saybert extends CModule
{
    /** @var string */
    public $MODULE_ID;
    /** @var string */
    public $MODULE_VERSION;
    /** @var string */
    public $MODULE_VERSION_DATE;
    /** @var string */
    public $MODULE_NAME;
    /** @var string */
    public $MODULE_DESCRIPTION;
    /** @var string */
    public $MODULE_GROUP_RIGHTS;
    /** @var string */
    public $PARTNER_NAME;
    /** @var string */
    public $PARTNER_URI;

    public function __construct()
    {
        $this->MODULE_ID = 'saybert';
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2016-10-24 11:49:00';
        $this->MODULE_NAME = 'Модуль saybert';
        $this->MODULE_DESCRIPTION = 'Модуль saybert';
        $this->MODULE_DESCRIPTION = "После установки вы сможете пользоваться компонетом";
    }


    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installEvent();
        $this->installDB();
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        $this->uninstallEvent();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }


    public function installEvent(){
        Bitrix\Main\EventManager::getInstance()->registerEventHandler("main","OnUserTypeBuildList",$this->MODULE_ID,"Bitrix\\Saybert\\Handler\\OnUserTypeBuildList","handle");
        Bitrix\Main\EventManager::getInstance()->registerEventHandler("iblock","OnIBlockPropertyBuildList",$this->MODULE_ID,"Bitrix\\Saybert\\Handler\\OnIBlockPropertyBuildList","handle");
    }

    public function uninstallEvent(){
        Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler("iblock","OnIBlockPropertyBuildList ",$this->MODULE_ID,"Bitrix\\Saybert\\Handler\\OnIBlockPropertyBuildList","handle");
    }


    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
//            ExampleTable::getEntity()->createDbTable();
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
//            $connection = Application::getInstance()->getConnection();
//            $connection->dropTable(ExampleTable::getTableName());
        }
    }
}