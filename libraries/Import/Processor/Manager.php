<?php

class Import_Processor_Manager extends Import_AbstractPluginManager
{
    protected function getEventName()
    {
        return 'processors';
    }

    protected function getInterface()
    {
        return 'Import_Processor';
    }
}
