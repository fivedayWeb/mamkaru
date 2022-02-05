<?php

namespace Bitrix\Saybert\Migrations;

/**
 * Миграция добавления группы пользователя
 * Class UserGroup
 */
class UserGroup extends Migration
{
    /** @var mixed ID, символьный идентификатор или наименование группы */
    public $identifier;

    /**
     * @return bool
     * @throws \Exception
     */
    public function isExist()
    {
        if (isset($this->fields["ID"])) {
            $this->identifier = intval($this->fields["ID"]);
            $rsGroup = \CGroup::GetById($this->fields["ID"]);
        } else {
            $arFilter = [];
            if (isset($this->fields["STRING_ID"])) {
                $this->identifier = strval($this->fields["STRING_ID"]);
                $arFilter["STRING_ID"] = $this->fields["STRING_ID"];
            } elseif (isset($this->fields["NAME"])) {
                $this->identifier = strval($this->fields["NAME"]);
                $arFilter["NAME"] = $this->fields["NAME"];
            } else {
                throw new \Exception("Невозможно определить, существует ли группа, так как не задано ни одно из полей: ID, NAME, STRING_ID");
            }
            $rsGroup = \CGroup::GetList($by, $order, $arFilter);
        }

        $arGroup = $rsGroup->Fetch();
        if (!$arGroup) {
            return false;
        }

        $this->id = $arGroup["ID"];
        return true;
    }

    /**
     * @param bool $exit_on_success
     * @param bool $exit_if_exists
     * @param bool $exit_on_fail
     * @return bool|int
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true)
    {
        try {
            if ($this->isExist()) {
                $this->writeLine("Группа $this->identifier уже существует", $exit_if_exists);
                return $this->id;
            }
        } catch(\Exception $e) {
            $this->writeLine($e->getMessage());
        }

        $group = new \CGroup();
        $group_id = $group->Add($this->fields);

        if (!$group_id) {
            $this->writeLine("Ошибка добавления группы $this->identifier: " . $group->LAST_ERROR, $exit_on_fail);
            return false;
        }

        $this->id = intval($group_id);
        $this->writeLine("Группа $this->identifier была добавлена, ID=" . $this->id, $exit_on_success);
        return $this->id;
    }
}