<?php

namespace Bitrix\Saybert\Migrations;

/**
 * Класс для короткой записи миграций почтовых шаблонов
 * Class MailTemplate
 */
class MailTemplate extends Migration
{
    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string    $task_id                Идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $CEventMessageFields    Набор полей почтового шаблона
     *
     * @link http://dev.1c-bitrix.ru/api_help/main/reference/ceventmessage/
     */
    public $ignore_count_template = false;

    public function __construct($task_id, $migration_description, $CEventMessageFields = [])
    {
        parent::__construct($task_id, $migration_description, $CEventMessageFields);
    }


    /**
     * Проверяет существование шаблонов с текущим набором полей
     *
     * @return mixed Массив(ы) полей найденного элемента или false
     *
     * @throws \Exception
     */
    public function isExist()
    {
        $arFilter = [];
        // Попробуем найти шаблон по ID, почтовому событию или теме
        if (!empty($this->fields['ID'])) {
            $arFilter['ID'] = intval($this->fields['ID']);
        } elseif (!empty($this->fields['EVENT_NAME'])) {
            $arFilter['TYPE_ID'] = strval($this->fields['EVENT_NAME']);
        } elseif (!empty($this->fields['SUBJECT'])) {
            $arFilter['SUBJECT'] = strval($this->fields['SUBJECT']);
        }

        // Получилось сформировать фильтр?
        if (empty($arFilter)) {
            throw new \Exception('Невозможно опеределить существование шаблона по имеющемуся набору полей');
        }

        // Сколько записей нашлось по фильтру?
        $rsEventMessage = \CEventMessage::GetList($by = 'id', $order = 'desc', $arFilter);
        $count_event_messages = $rsEventMessage->SelectedRowsCount();
        if (($count_event_messages > 1) && ($this->ignore_count_template == true)) {
            $event_messages = [];
            while ($event_message = $rsEventMessage->Fetch()) {
                $event_messages[] = $event_message;
            }
            return $event_messages;
        }
        switch($count_event_messages) {
            case 0:
                return false;
            case 1:
                $event_message = $rsEventMessage->Fetch();
                $this->id = $event_message['ID'];
                return [$event_message];
            default:
                throw new \Exception('По заданым полям нашлось более 1 почтового шаблона');
        }
    }

    /**
     * Добавляет новый почтовый шаблон
     *
     * @param bool $exit_on_success Работа скрипта завершается на успешном добавлении шаблона?
     * @param bool $exit_if_exists  Прерывать выполнение скрипта, если шаблон уже существует?
     * @param bool $exit_on_fail    Прерывать выполнение скрипта, если произошла ошибка при добавлении?
     *
     * @return mixed ID добавленного шабона или false в случае ошибки
     */
    public function add($exit_on_success = true, $exit_if_exists = true, $exit_on_fail = true)
    {
        // Проверим, было ли уже выполнено добавление
        try {
            if ($this->isExist()) {
                // Если шаблон уже существует, сообщим об отмене действия
                $this->cancel("Шаблон " . $this->id . " уже существует", $exit_if_exists);
                return $this->id;
            }
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли свойство, то дальше не идём
            $this->fail($e->getMessage());
        }

        // Если ещё не добавляли, то добавим
        $tmpl = new \CEventMessage();
        $this->id = $tmpl->Add($this->fields);

        // Посмотрим, какой результат
        if (!$this->id) {
            $this->fail('Не удалось добавить почтовый шаблон: ' . $tmpl->LAST_ERROR, $exit_on_fail);
            return false;
        }
        $this->success('Шаблон для события ' . $this->fields['EVENT_NAME'] . ' успешно добавлен, ID=' . $this->id, $exit_on_success);
        return $this->id;
    }


    /**
     * Обновляет существующй почтовый шаблон
     *
     * @param boolean $exit_on_success  Работа скрипта завершается после успешного выполнения?
     * @param boolean $exit_on_fail     Прерывать работу скрипта при сбое в обновлении данных?
     *
     * @return boolean Обновление выполнено успешно?
     */
    public function update($exit_on_success = true, $exit_on_fail = true)
    {
        // Проверим, существует ли шаблон, который будем обновлять
        try {
            $template = $this->isExist();
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли шаблон, то дальше не идём
            $this->fail($e->getMessage());
        }

        // Обновляем найденный элемент
        unset($this->fields['ID']);
        if (!empty($template['ADDITIONAL_FIELD']) && !empty($this->fields['ADDITIONAL_FIELD'])) {
            foreach ($template['ADDITIONAL_FIELD'] as $key => $field) {
                $add_fields_names[$key] = $field['NAME'];
            }
            foreach ($this->fields['ADDITIONAL_FIELD'] as $field) {
                $new_add_fields_names[] = $field['NAME'];
            }
            foreach ($add_fields_names as $key => $name) {
                if (!in_array($name, $new_add_fields_names)) {
                    $this->fields['ADDITIONAL_FIELD'][] = $template['ADDITIONAL_FIELD'][$key];
                }
            }
        }
        $tmpl = new \CEventMessage();
        if (!$tmpl->Update($this->id, $this->fields)) {
            $this->fail('Ошибка обновления шаблона ' . $this->id . ': ' . $tmpl->LAST_ERROR, $exit_on_fail);
            return false;
        }

        // Если всё ок, то сообщим об этом
        $this->success('Почтовый шаблон ' . $this->id . ' успешно обновлён', $exit_on_success);
        return true;
    }


    /**
     * Заменяет фрагмент в теле письма
     *
     * @param string    $search             Что поменять
     * @param string    $replace            Чем заменить
     * @param boolean   $exit_on_success    Работа скрипта завершается после успешного выполнения?
     * @param boolean   $exit_on_cancel     Прерывать работу скрипта, если замена уже была проведена?
     * @param boolean   $exit_on_fail       Прерывать работу скрипта при сбое в обновлении данных?
     *
     * @return boolean Замена текста и обновление шаблона прошли успешно?
     */
    public function replaceBodyText($search, $replace, $exit_on_success = true, $exit_on_cancel = true, $exit_on_fail = true)
    {
        // Проверим, существует ли шаблон, который будем обновлять
        try {
            $tmpls_fields = $this->isExist();
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли шаблон, то дальше не идём
            $this->fail($e->getMessage());
        }

        foreach ($tmpls_fields as $tmpl_fields) {
            // Проверим, что есть что заменять
            if (strpos($tmpl_fields['MESSAGE'], $search) === false) {
                $this->cancel('Фрагмент для замены не обнаружен в теле письма', $exit_on_cancel);
                continue;
            }

            // Заменим текст и обновим шаблон
            $new_message = str_replace($search, $replace, $tmpl_fields['MESSAGE']);
            $this->setFields([
                'ID'        => $tmpl_fields['ID'],
                'MESSAGE' => $new_message,
            ]);
            if (!$this->update($exit_on_success, $exit_on_fail))
                return false;
        }
        return true;
    }


    /**
     * Добавляет почтовое событие и шаблон
     *
     * @param array $event_type_fields  Поля почтового события
     * @param bool  $add_template       Нужно ли после добавления почтового события также добавить новый шаблон
     */
    public function addEvent($event_type_fields, $add_template = true, $exit_on_success = true, $exit_if_exists = true)
    {
        // Проверим, не было ли уже выполнено добавление ранее
        $event_name = strval($event_type_fields['EVENT_NAME']);
        $event_lid  = $event_type_fields['LID'];
        try {
            if (self::isEventExist($event_type_fields)) {
                $this->cancel("Почтовое событие $event_name для языка $event_lid уже было добавлено ранее");
            }
        } catch (\Exception $e) {
            $this->fail('Не удалось определить, существует ли событие: ' . $e->getMessage());
        }

        // Если есть описание, сформируем из массива строку
        if (is_array($event_type_fields['DESCRIPTION'])) {
            $event_description = '';
            foreach ($event_type_fields['DESCRIPTION'] as $name => $description) {
                $event_description .= '#' . strval($name) . '# - ' . strval($description) . "\n";
            }
            if (!empty($event_description)) {
                $event_type_fields['DESCRIPTION'] = $event_description;
            }
        }

        // Если проверка прошла успешно, то добавляем событие
        $event = new \CEventType();
        if (!$event->add($event_type_fields)) {
            $this->fail("При добавлении почтового события $event_name произошла ошибка: " . $event->LAST_ERROR);
        }
        $this->success("Почтовое событие $event_name успешно добавлено", !$add_template);

        // Если надо добавить и почтовый шаблон тоже, то добавим его
        if ($this->fields['EVENT_NAME'] != $event_name) {
            $this->cancel('Наименование события и соответствующего поля в шаблоне не совпадают');
        }
        $this->add($exit_on_success, $exit_if_exists);
    }


    /**
     * Проверяет существование почтового события
     *
     * @param array $fields Массив полей почтового события. Должны быть обязательно определены EVENT_NAME, LID.
     *
     * @return bool
     *
     * @throws \Exception Если не удалось построить фильтр
     */
    public static function isEventExist($fields)
    {
        // Сформируем из значимых полей фильтр для проверки существования события
        $significant_fields = ['EVENT_NAME', 'LID'];
        $filter = [];
        foreach ($significant_fields as $field_name) {
            if (empty($fields[$field_name])) {
                throw new \Exception("Не задано поле '$field_name' почтового события");
            }
            $filter[$field_name] = $fields[$field_name];
        }
        return (bool) \CEventType::GetList($filter)->Fetch();
    }

    /**
     * Устанавливает флаг игнорирования количества шаблонов при проверке на их существование в методе isExist()
     *
     * @param bool $value
     */
    public function setIgnoreTemplate($value = true)
    {
        $this->ignore_count_template = $value;
    }
}