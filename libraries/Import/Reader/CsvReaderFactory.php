<?php

class Import_Reader_CsvReaderFactory implements Import_Reader_Factory
{
    public function create($config)
    {
        $reader = new Import_Reader_CsvReader();
        $reader->setConfig($config);

        return $reader;
    }
}
