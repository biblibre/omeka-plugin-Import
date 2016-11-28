<?php

class Import_Job_Import extends Omeka_Job_AbstractJob
{
    protected $import;
    protected $logger;

    public function perform()
    {
        $import = $this->getImport();

        $importer = $import->getImporter();
        $reader = $importer->getReader();
        if ($reader instanceof Import_Parametrizable) {
            $reader->setParams($import->getReaderParams());
        }
        $processor = $importer->getProcessor();
        $processor->setReader($reader);
        if ($processor instanceof Import_Parametrizable) {
            $processor->setParams($import->getProcessorParams());
        }
        $processor->setLogger($this->getLogger());

        try {
            $this->getLogger()->log('Import started', Zend_Log::NOTICE);
            $import->started = date('Y-m-d H:i:s');
            $import->status = 'in progress';
            $import->save();

            $processor->process();

            $this->getLogger()->log('Import completed', Zend_Log::NOTICE);
            $import->status = 'completed';
            $import->ended = date('Y-m-d H:i:s');
            $import->save();
        } catch (Exception $e) {
            $this->getLogger()->log("$e", Zend_Log::ERR);

            $import->status = 'error';
            $import->save();
        }

    }

    protected function getLogger()
    {
        if (!isset($this->logger)) {
            $this->logger = new Import_Logger();
            $this->logger->setImport($this->getImport());
        }

        return $this->logger;
    }

    protected function getImport()
    {
        if (!isset($this->import)) {
            $importId = $this->_options['importId'];
            $this->import = $this->_db->getTable('Import_Import')->find($importId);
        }

        return $this->import;
    }
}
