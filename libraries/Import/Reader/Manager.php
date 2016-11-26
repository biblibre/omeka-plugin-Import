<?php

class Import_Reader_Manager extends Import_AbstractPluginManager
{
    protected function getEventName()
    {
        return 'readers';
    }

    protected function getInterface()
    {
        return 'Import_Reader';
    }
}
