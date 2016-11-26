<?php

interface Import_Configurable
{
    public function setConfig($config);
    public function getConfig();

    public function getConfigForm();
    public function handleConfigForm(Zend_Form $form);
}
