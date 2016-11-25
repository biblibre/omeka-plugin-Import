<?php

class Import_Import extends Omeka_Record_AbstractRecord
{
    public $importer_id;
    public $reader_options;
    public $processor_options;
    public $status;
    public $started;
    public $ended;

    public function getImporter()
    {
        return $this->getTable('Import_Importer')->find($this->importer_id);
    }

    public function getReaderOptions()
    {
        return unserialize($this->reader_options);
    }

    public function getProcessorOptions()
    {
        return unserialize($this->processor_options);
    }
}
