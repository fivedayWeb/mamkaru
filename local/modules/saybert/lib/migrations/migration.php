<?php

namespace Bitrix\Saybert\Migrations;

class Migration
{
    const DATE_FORMAT = '[Y-m-d H:i:s] ';

    /** @var string Идентификатор задачи */
    public $taskId;

    /** @var string Описание миграции */
    public $description;

    /** @var int Время начала миграции  */
    public $startTime;

    /** @var array Массив данных основного элемента, с которым будем работать */
    public $fields;

    /** @var int Идентификатор основного элемента */
    protected $id;

    /**
     * @param $taskId
     * @param $migrationDescription
     * @param null $data
     */
    public function __construct($taskId, $migrationDescription, $data = null)
    {
        $this->taskId = trim($taskId);
        $this->setDescription($migrationDescription);
        if (!empty($data)) {
            $this->setFields($data);
        }
        $this->startTime = time();
        $this->start_time = time();
        $this->writeLine(
            '==== ' . $this->taskId . ' ====' . PHP_EOL .
            date(self::DATE_FORMAT, $this->startTime) . $migrationDescription
        );
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $fields
     * @throws \Exception
     */
    public function setFields($fields)
    {
        if (empty($fields)) {
            throw new \Exception("Задан пустой набор полей");
        }
        $this->fields = $fields;
        $id = intval($fields["ID"]);
        if ($id) {
            $this->id = $id;
        }
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = trim($description);
    }

    /**
     * @param string $text
     * @param bool $exit
     */
    public function writeLine($text = "", $exit = false)
    {
        echo $text . PHP_EOL;
        if ($exit) {
            die();
        }
    }


    /**
     * Выводит текст и переводит строку
     *
     * @param string $message - текст сообщения
     * @param bool $exit - прерывать выполнение скрипта после вывода сообщения?
     */
    protected function showMessage($message, $exit = false)
    {
        $this->writeLine(date(self::DATE_FORMAT) . $message . PHP_EOL, $exit);
    }


    /**
     * Выводит информацию о ходе миграции
     *
     * @param string $message
     */
    public function status($message)
    {
        $this->showMessage($message, false);
    }


    /**
     * Выводит текст об успешном завершении операции
     * Например SUCCESS: все данные обновлены
     *
     * @param string $message - текст сообщения
     * @param bool $exit - прерывать выполнение скрипта после вывода сообщения?
     */
    public function success($message, $exit = false)
    {
        $this->showMessage("SUCCESS (операция выполнена): $message", $exit);
    }


    /**
     * Выводит сообщение о неудачном выполнении операции
     * Например: FAIL: не удалось обновить объект ID=XXXX
     *
     * @param string $message - текст сообщение об ошибке
     * @param bool $exit - прерывать выполнение скрипта после вывода ошибки?
     */
    public function fail($message, $exit = true)
    {
        $this->showMessage("FAIL (ошибка операции): $message", $exit);
    }


    /**
     * Выводит сообщение об отмене операции
     * Например: CANCEL: свойство с кодом PROP_CODE уже существует
     *
     * @param string $message - текст сообщение об отмене
     * @param bool $exit - прерывать выполнение скрипта после вывода ошибки?
     */
    public function cancel($message, $exit = true)
    {
        $this->showMessage("CANCEL (отмена операции): $message", $exit);
    }

    /**
     * Копирует файл из установочной директории
     * модуля saybert в админку Битрикса
     *
     * @param string $file_name
     * @param bool|true $exit_on_success
     * @param bool|true $exit_on_fail
     *
     * @return bool Прошло ли копирование успешно?
     */
    public function copy2bitrix($file_name, $exit_on_success = true, $exit_on_fail = true)
    {
        if (!CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'] . '/local/modules/saybert/install/admin/' . $file_name,
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $file_name,
            false
        )) {
            $this->fail("Ошибка переноса файла $file_name в админку", $exit_on_fail);
            return false;
        }
        $this->success("Файл $file_name успешно перенесён в админку", $exit_on_success);
        return true;
    }
}