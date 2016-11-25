<?php

interface Import_Reader extends Iterator
{
    public function setConfig($config);
    public function getConfig();
    public function setOptions($options);
    public function getOptions();

    public function hasConfigForm();
    public function getConfigForm();
    public function handleConfigForm(Zend_Form $form);

    public function hasOptionsForm();
    public function getOptionsForm();
    public function handleOptionsForm(Zend_Form $form);

    public function getAvailableFields();
}
