<?php

class Import_Import extends Omeka_Record_AbstractRecord
{
    public $importer_id;
    public $reader_params;
    public $processor_params;
    public $status;
    public $started;
    public $ended;

    public function getImporter()
    {
        return $this->getTable('Import_Importer')->find($this->importer_id);
    }

    public function setReaderParams($params)
    {
        $this->reader_params = isset($params) ? serialize($params) : null;
    }

    public function getReaderParams()
    {
        if (isset($this->reader_params)) {
            return unserialize($this->reader_params);
        }
    }

    public function setProcessorParams($params)
    {
        $this->processor_params = isset($params) ? serialize($params) : null;
    }

    public function getProcessorParams()
    {
        if (isset($this->processor_params)) {
            return unserialize($this->processor_params);
        }
    }
}
