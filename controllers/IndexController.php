<?php

class Import_IndexController extends Zend_Controller_Action
{
    /**
     * @var Zend_EventManager_SharedEventManager
     */
    protected $events;

    public function init()
    {
        $this->events = Zend_EventManager_StaticEventManager::getInstance();
    }

    public function indexAction()
    {
        $db = $this->getHelper('db');

        $importers = $db->getTable('Import_Importer')->findAll();
        $imports = $db->getTable('Import_Import')->getLastImports();

        $this->view->importers = $importers;
        $this->view->imports = $imports;
    }
}
