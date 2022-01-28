<?php

namespace Bitrix\Saybert\Migrations;

/**
 * Миграция добавления типа инфоблока
 * Class Iblock
 */
class IblockType extends Migration
{

    /**
     * @return bool|int
     * @throws \Exception
     */
    public function isExist()
    {
        if ($this->id) {
            return $this->id;
        }

        if (empty($this->fields["ID"])) {
            throw new \Exception("Не задан ID типа инфоблока");
        }
        $filter = ['ID' => $this->fields['ID']];

        $iblockType = \CIBlockType::GetList(['id' => 'asc'], $filter)->Fetch();

        if (!$iblockType["ID"]) {
            return false;
        }

        $this->id = $iblockType["ID"];
        $this->fields["ID"] = $iblockType["ID"];
        return $this->id;
    }

    /**
     * @param bool $exitOnSuccess
     * @param bool $exitIfExists
     * @param bool $exitOnFail
     * @return bool|int
     */
    public function add($exitOnSuccess = false, $exitIfExists = false, $exitOnFail = true)
    {
        try {
            $iblock_id = $this->isExist();
            if ($iblock_id) {
                $this->writeLine("Тип инфоблока " . $this->fields["ID"] . " уже существует", $exitIfExists);
                return $this->id;
            }
        } catch(\Exception $e) {
            $this->writeLine($e->getMessage(), $exitOnFail);
        }

        $iblockType = new \CIBlockType();
        $this->id = $iblockType->Add($this->fields);

        if (!$this->id) {
            $this->writeLine("Не удалось добавить тип инфоблока " . $this->fields["ID"] . ": " . $iblockType->LAST_ERROR, $exitOnFail);
            return false;
        }

        $this->writeLine("Тип инфоблока " . $this->fields["ID"] . " успешно добавлен, ID=" . $this->id, $exitOnSuccess);
        return $this->id;
    }
}