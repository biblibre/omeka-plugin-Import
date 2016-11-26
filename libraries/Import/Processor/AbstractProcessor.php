<?php

abstract class Import_Processor_AbstractProcessor implements Import_Processor
{
    /**
     * @var Import_Reader
     */
    protected $reader;

    /**
     * @var Zend_Log
     */
    protected $logger;

    public function setReader(Import_Reader $reader)
    {
        $this->reader = $reader;
    }

    public function getReader()
    {
        return $this->reader;
    }

    public function setLogger(Zend_Log $logger)
    {
        $this->logger = $logger;
    }
}
