<?php

class Import_Importer extends Omeka_Record_AbstractRecord
{
    public $name;
    public $reader_name;
    public $reader_config;
    public $processor_name;
    public $processor_config;

    protected $reader;
    protected $processor;

    public function getReader()
    {
        if (!isset($this->reader)) {
            $readerManager = Zend_Registry::get('import_reader_manager');
            $this->reader = $readerManager->get($this->reader_name);
            if ($this->reader instanceof Import_Configurable) {
                $this->reader->setConfig($this->getReaderConfig());
            }
        }

        return $this->reader;
    }

    public function getProcessor()
    {
        if (!isset($this->processor)) {
            $processorManager = Zend_Registry::get('import_processor_manager');
            $this->processor = $processorManager->get($this->processor_name);
            if ($this->processor instanceof Import_Configurable) {
                $this->processor->setConfig($this->getProcessorConfig());
            }
        }

        return $this->processor;
    }

    public function setReaderConfig($config)
    {
        if (isset($config)) {
            $this->reader_config = serialize($config);
        } else {
            $this->reader_config = null;
        }
    }

    public function getReaderConfig()
    {
        if (isset($this->reader_config)) {
            return unserialize($this->reader_config);
        }
    }

    public function setProcessorConfig($config)
    {
        if (isset($config)) {
            $this->processor_config = serialize($config);
        } else {
            $this->processor_config = null;
        }
    }

    public function getProcessorConfig()
    {
        if (isset($this->processor_config)) {
            return unserialize($this->processor_config);
        }
    }
}
