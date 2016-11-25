<?php

class Import_Form_ImporterStartForm extends Omeka_Form
{
    public function init()
    {
        $this->addElement('submit', 'submit', array(
            'label' => __('Start import'),
        ));
    }
}
