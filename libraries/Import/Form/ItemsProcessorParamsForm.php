<?php

class Import_Form_ItemsProcessorParamsForm extends Omeka_Form
{
    protected $processor;

    public function init()
    {
        parent::init();

        $config = $this->processor->getConfig();

        $this->addElement('select', 'collection_id', array(
            'label' => __('Collection'),
            'multiOptions' => $this->getCollectionsOptions(),
            'value' => isset($config['collection_id']) ? $config['collection_id'] : null,
        ));

        $mappingSubForm = new Omeka_Form();
        $elements = array();
        foreach ($this->processor->getReader()->getAvailableFields() as $name) {
            $mappingSubForm->addElement('select', $name, array(
                'label' => $name,
                'multiOptions' => $this->getMappingOptions(),
            ));

            $elements[] = $name;
        }

        //$mappingSubForm->addDisplayGroup($elements, 'mapping');
        $mappingSubForm->setIsArray(true);
        $mappingSubForm->removeDecorator('Form');
        $mappingSubForm->addDecorator('Fieldset', array('legend' => __('Mapping')));
        $this->addSubForm($mappingSubForm, 'mapping');
    }

    public function setProcessor(Import_Processor $processor)
    {
        $this->processor = $processor;
    }

    protected function getMappingOptions()
    {
        $db = get_db();

        $options = array(
            '' => '',
            'Metadata' => array(
                'public' => __('Is Public'),
                'featured' => __('Is Featured'),
                'item_type_name' => __('Item Type Name'),
                'tags' => __('Tags'),
            ),
            'File' => array(
                'file:filesystem' => __('Local Path'),
                'file:url' => __('URL'),
            ),
        );

        $options = array_merge(
            $options,
            $db->getTable('Element')->findPairsForSelectForm()
        );

        return $options;
    }

    protected function getCollectionsOptions()
    {
        return get_db()->getTable('Collection')->findPairsForSelectForm(array(
            'include_no_collection' => true,
        ));
    }
}
