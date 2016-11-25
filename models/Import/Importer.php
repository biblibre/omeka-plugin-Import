<?php

class Import_Importer extends Omeka_Record_AbstractRecord
{
    public $name;
    public $reader;
    public $reader_config;
    public $processor;
    public $processor_config;

    protected $readerObject;
    protected $processorObject;

    public function getReader()
    {
        if (!isset($this->readerObject)) {
            $readers = Import::getReaders();
            if (array_key_exists($this->reader, $readers)) {
                $readerFactoryClass = $readers[$this->reader]['factory'];
                if (class_exists($readerFactoryClass)) {
                    $readerFactory = new $readerFactoryClass();
                    $this->readerObject = $readerFactory->create($this->getReaderConfig());
                }
            }
        }

        return $this->readerObject;
    }

    public function getProcessor()
    {
        if (!isset($this->processorObject)) {
            $processors = Import::getProcessors();
            if (array_key_exists($this->processor, $processors)) {
                $processorFactoryClass = $processors[$this->processor]['factory'];
                if (class_exists($processorFactoryClass)) {
                    $processorFactory = new $processorFactoryClass();
                    $this->processorObject = $processorFactory->create($this->getProcessorConfig());
                }
            }
        }

        return $this->processorObject;
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
