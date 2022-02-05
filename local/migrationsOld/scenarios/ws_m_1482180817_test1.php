<?php

/**
 * Class definition update migrations scenario actions
 **/
class ws_m_1482180817_test1 extends \WS\Migrations\ScriptScenario {

    /**
     * Name of scenario
     **/
    static public function name() {
        return "test1";
    }

    /**
     * Description of scenario
     **/
    static public function description() {
        return "test";
    }

    /**
     * @return array First element is hash, second is owner name
     */
    public function version() {
        return array("dc517db22c2f527a263d07f68517db45", "");
    }

    /**
     * Write action by apply scenario. Use method `setData` for save need rollback data
     **/
    public function commit() {
        // my code
    }

    /**
     * Write action by rollback scenario. Use method `getData` for getting commit saved data
     **/
    public function rollback() {
        // my code
    }
}