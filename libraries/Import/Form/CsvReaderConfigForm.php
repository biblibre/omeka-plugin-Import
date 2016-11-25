<?php

class Import_Form_CsvReaderConfigForm extends Omeka_Form
{
    protected $config;

    public function init()
    {
        parent::init();

        $config = $this->config;

        $this->addElement('text', 'delimiter', array(
            'label' => __('Default delimiter'),
            'value' => isset($config['delimiter']) ? $config['delimiter'] : ',',
        ));
        $this->addElement('text', 'enclosure', array(
            'label' => __('Default enclosure'),
            'value' => isset($config['enclosure']) ? $config['enclosure'] : '"',
        ));
        $this->addElement('text', 'escape', array(
            'label' => __('Default escape'),
            'value' => isset($config['escape']) ? $config['escape'] : '\\',
        ));

        $this->addDisplayGroup(array(
            'delimiter',
            'enclosure',
            'escape',
        ), 'config_form');
    }

    public function setReaderConfig($config)
    {
        $this->config = $config;
    }
}
