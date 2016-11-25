<?php

abstract class Import_Reader_AbstractReader implements Import_Reader
{
    /**
     * @var Zend_Log
     */
    protected $logger;

    protected $config;

    protected $options;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption($name, $default = null)
    {
        if (isset($this->options) && array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return $default;
    }

    public function hasConfigForm()
    {
        return false;
    }

    public function getConfigForm()
    {
    }

    public function handleConfigForm(Zend_Form $form)
    {
    }

    public function hasOptionsForm()
    {
        return false;
    }

    public function getOptionsForm()
    {
    }

    public function handleOptionsForm(Zend_Form $form)
    {
    }
}