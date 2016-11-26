<?php

class Import_Processor_ItemsProcessor extends Import_Processor_AbstractProcessor
    implements Import_Configurable, Import_Parametrizable
{
    use Import_ConfigurableTrait, Import_ParametrizableTrait;

    protected $elements;

    public function getLabel()
    {
        return 'Items';
    }

    public function getConfigForm()
    {
        $options = array(
            'processor' => $this,
        );

        return new Import_Form_ItemsProcessorConfigForm($options);
    }

    public function handleConfigForm(Zend_Form $form)
    {
        $values = $form->getValues();
        $config = array(
            'collection_id' => $values['collection_id'],
        );
        $this->setConfig($config);
    }

    public function getParamsForm()
    {
        $options = array(
            'processor' => $this,
        );

        return new Import_Form_ItemsProcessorParamsForm($options);
    }

    public function handleParamsForm(Zend_Form $form)
    {
        $values = $form->getValues();
        $params = array(
            'mapping' => $values['mapping'],
            'collection_id' => $values['collection_id'],
        );

        $this->setParams($params);
    }

    public function process()
    {
        $db = get_db();

        $mapping = $this->getParam('mapping', array());
        $collection_id = $this->getParam('collection_id');

        foreach ($this->reader as $i => $entry) {
            $this->logger->log('Processing row ' . $i + 1, Zend_Log::NOTICE);

            $metadata = array(
                'public' => true,
                'featured' => false,
            );
            $elementTexts = array();
            $files = array();

            if ($collection_id) {
                $metadata['collection_id'] = $collection_id;
            }

            foreach ($mapping as $sourceField => $target) {
                if (isset($entry[$sourceField])) {
                    $value = $entry[$sourceField];

                    if (is_numeric($target)) {
                        $elementName = $this->getElementName($target);
                        $elementSetName = $this->getElementSetName($target);

                        $elementTexts[$elementSetName][$elementName][] = array(
                            'text' => $value,
                            'html' => false,
                        );
                    } elseif (0 === strpos($target, 'file:')) {
                        $strategy = substr($target, strpos($target, ':') + 1);
                        $strategy = ucfirst($strategy);
                        $files[] = array(
                            'strategy' => $strategy,
                            'file' => $value,
                        );
                    } else {
                        $metadata[$target] = $value;
                    }
                }
            }

            try {
                $item = insert_item($metadata, $elementTexts);
                foreach ($files as $file) {
                    insert_files_for_item($item, $file['strategy'], $file['file']);
                }
                $this->logger->log("Created item {$item->id}", Zend_Log::NOTICE);
            } catch (Exception $e) {
                $this->logger->log("$e", Zend_Log::ERR);
            }

        }
    }

    protected function getElementName($elementId)
    {
        $elements = $this->getElements();
        if (array_key_exists($elementId, $elements)) {
            return $elements[$elementId]['name'];
        }
    }

    protected function getElementSetName($elementId)
    {
        $elements = $this->getElements();
        if (array_key_exists($elementId, $elements)) {
            return $elements[$elementId]['set_name'];
        }
    }

    protected function getElements()
    {
        if (!isset($this->elements)) {
            $db = get_db();
            $select = $db->getTable('Element')->getSelectForFindBy();
            $select->reset(Zend_Db_Select::COLUMNS);
            $select->from(array(), array(
                'id' => 'elements.id',
                'name' => 'elements.name',
                'set_name' => 'element_sets.name',
            ));

            $this->elements = $db->fetchAssoc($select);
        }

        return $this->elements;
    }
}
