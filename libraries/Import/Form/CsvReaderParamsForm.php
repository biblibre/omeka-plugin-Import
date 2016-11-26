<?php

class Import_Form_CsvReaderParamsForm extends Omeka_Form
{
    protected $reader;

    public function init()
    {
        parent::init();

        $config = $this->reader->getConfig();

        $this->addElement('file', 'file', array(
            'label' => __('CSV file'),
            'required' => true,
        ));
        $this->addElement('text', 'delimiter', array(
            'label' => __('Delimiter'),
            'value' => isset($config['delimiter']) ? $config['delimiter'] : ',',
        ));
        $this->addElement('text', 'enclosure', array(
            'label' => __('Enclosure character'),
            'value' => isset($config['enclosure']) ? $config['enclosure'] : '"',
        ));
        $this->addElement('text', 'escape', array(
            'label' => __('Escape character'),
            'value' => isset($config['escape']) ? $config['escape'] : '\\',
        ));
        $this->addDisplayGroup(array(
            'file',
            'delimiter',
            'enclosure',
            'escape',
        ), 'csv_reader_params');
    }

    public function setReader(Import_Reader $reader)
    {
        $this->reader = $reader;
    }
}
