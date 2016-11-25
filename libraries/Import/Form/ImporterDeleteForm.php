<?php

class Import_Form_ImporterDeleteForm extends Omeka_Form
{
    /**
     * @var Import_Importer
     */
    protected $importer;

    public function init()
    {
        parent::init();

        $this->addElement('submit', 'submit', array(
            'label' => __('Delete importer'),
        ));
    }

    public function setImporter(Import_Importer $importer)
    {
        $this->importer = $importer;
    }
}
