<?php

namespace Bitrix\Saybert\Migrations;

/**
 * Миграция добавления элемента инфоблока
 * Class IblockElement
 */
class IblockSection extends Migration
{
    /**
     * @param bool $exitOnFail
     * @return bool|int
     */
    public function add($exitOnFail = true)
    {
        $iblockSection = new \CIBlockSection();
        $this->id = $iblockSection->Add($this->fields);

        if (!$this->id) {
            $this->writeLine("Не удалось добавить раздел" . $this->fields["NAME"] . ": " . $iblockSection->LAST_ERROR, $exitOnFail);
            return false;
        }

        $this->writeLine("Раздел " . $this->fields["NAME"] . " успешно добавлен, ID=" . $this->id);
        return $this->id;
    }
}