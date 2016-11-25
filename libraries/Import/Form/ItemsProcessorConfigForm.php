<?php

class Import_Form_ItemsProcessorConfigForm extends Omeka_Form
{
    protected $processor;

    public function init()
    {
        parent::init();

        $config = $this->processor->getConfig();

        $this->addElement('select', 'collection_id', array(
            'label' => __('Default collection'),
            'multiOptions' => $this->getCollectionsOptions(),
            'value' => isset($config['collection_id']) ? $config['collection_id'] : null,
        ));

        $this->addDisplayGroup(array(
            'collection_id',
        ), 'config_form');
    }

    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }

    protected function getCollectionsOptions()
    {
        return get_db()->getTable('Collection')->findPairsForSelectForm(array(
            'include_no_collection' => true,
        ));
    }
}
