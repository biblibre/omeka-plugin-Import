<?php

class Import_ImportsController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $db = $this->getHelper('Db');

        $importTable = $db->getTable('Import_Import');

        $currentPage = $this->getParam('page', 1);
        $recordsPerPage = 20;
        $totalRecords = $importTable->count();

        $imports = $importTable->findBy(array(
            'sort_field' => 'id',
            'sort_dir' => 'd',
        ), $recordsPerPage, $currentPage);

        Zend_Registry::set('pagination', array(
            'page' => $currentPage,
            'per_page' => $recordsPerPage,
            'total_results' => $totalRecords,
        ));

        $this->view->imports = $imports;
    }

    public function logsAction()
    {
        $db = $this->getHelper('Db');

        $params = array(
            'import_id' => $this->getParam('id'),
        );

        $importLogTable = $db->getTable('Import_Log');

        $currentPage = $this->getParam('page', 1);
        $recordsPerPage = 20;
        $totalRecords = $importLogTable->count($params);

        $logs = $importLogTable->findBy($params, $recordsPerPage, $currentPage);

        Zend_Registry::set('pagination', array(
            'page' => $currentPage,
            'per_page' => $recordsPerPage,
            'total_results' => $totalRecords,
        ));

        $this->view->logs = $logs;
    }
}
