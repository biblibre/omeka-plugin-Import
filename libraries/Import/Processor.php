<?php

interface Import_Processor
{
    public function setReader(Import_Reader $reader);
    public function setConfig($config);
    public function setOptions($options);

    public function hasConfigForm();
    public function getConfigForm();
    public function handleConfigForm(Zend_Form $form);

    public function hasOptionsForm();
    public function getOptionsForm();
    public function handleOptionsForm(Zend_Form $form);

    public function setLogger(Zend_Log $log);

    public function process();
}
