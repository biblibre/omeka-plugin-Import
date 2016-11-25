<?php

class Import_Processor_ItemsProcessorFactory implements Import_Processor_Factory
{
    public function create($config)
    {
        $processor = new Import_Processor_ItemsProcessor;
        $processor->setConfig($config);

        return $processor;
    }
}
