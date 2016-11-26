<?php

class Import_Form_ImporterForm extends Omeka_Form
{
    /**
     * @var Import_Importer
     */
    protected $importer;

    public function init()
    {
        parent::init();

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name');
        $this->addElement('text', 'name', array(
            'label' => __('Name'),
            'value' => isset($this->importer) ? $this->importer->name : null,
        ));

        $this->addElement('select', 'reader_name', array(
            'label' => __('Reader'),
            'multiOptions' => $this->getReaderOptions(),
        ));

        $this->addElement('select', 'processor_name', array(
            'label' => __('Processor'),
            'multiOptions' => $this->getProcessorOptions(),
        ));

        $this->addElement('submit', 'submit', array(
            'label' => __('Save'),
        ));

        $this->addDisplayGroup(array(
            'name',
            'reader_name',
            'processor_name',
        ), 'importer_info');

        $this->addDisplayGroup(array(
            'submit',
        ), 'importer_submit');
    }

    public function setImporter(Import_Importer $importer)
    {
        $this->importer = $importer;
    }

    protected function getReaderOptions()
    {
        $readerOptions = array();

        $readerManager = Zend_Registry::get('import_reader_manager');
        $readers = $readerManager->getAll();
        foreach ($readers as $key => $reader) {
            $readerOptions[$key] = $reader->getLabel();
        }

        return $readerOptions;
    }

    protected function getProcessorOptions()
    {
        $processorOptions = array();

        $processorManager = Zend_Registry::get('import_processor_manager');
        $processors = $processorManager->getAll();
        foreach ($processors as $key => $processor) {
            $processorOptions[$key] = $processor->getLabel();
        }

        return $processorOptions;
    }
}
