<?php

class Import_Logger extends Zend_Log
{
    protected $import;

    public function __construct (Zend_Log_Writer_Abstract $writer = null)
    {
        if (!isset($writer)) {
            $dbAdapter = get_db()->getAdapter();
            $writer = new Zend_Log_Writer_Db($dbAdapter, 'import_logs', array(
                'severity' => 'priority',
                'message' => 'message',
                'import_id' => 'import_id',
                'params' => 'params',
            ));
        }

        parent::__construct($writer);
    }

    public function setImport(Import_Import $import)
    {
        $this->import = $import;
    }

    public function log($message, $severity, $extras = null)
    {
        $extras = array(
            'import_id' => $this->import->id,
            'params' => isset($extras) ? serialize($extras) : null,
        );
        parent::log($message, $severity, $extras);
    }
}
