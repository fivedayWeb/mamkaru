<?php

namespace Bitrix\Saybert\Migrations;

/**
 * Миграция добавления элемента инфоблока
 * Class IblockElement
 */
class IblockElement extends Migration
{
    /**
     * @param bool $exitOnFail
     * @return bool|int
     */
    public function add($exitOnFail = true)
    {
        $iblockElement = new \CIBlockElement();
        $this->id = $iblockElement->Add($this->fields);

        if (!$this->id) {
            $this->writeLine("Не удалось добавить элемент" . $this->fields["NAME"] . ": " . $iblockElement->LAST_ERROR, $exitOnFail);
            return false;
        }

        $this->writeLine("Элемент " . $this->fields["NAME"] . " успешно добавлен, ID=" . $this->id);
        return $this->id;
    }
}