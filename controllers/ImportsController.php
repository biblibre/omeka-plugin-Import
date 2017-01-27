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
        $db = get_db();
        $importLogTable = $db->getTable('Import_Log');

        $severity = (int) $this->getParam('severity', Zend_Log::NOTICE);

        $select = $importLogTable->getSelect();
        $select->where('import_id = ?', $this->getParam('id'));
        $select->where('severity <= ?', $severity);
        $importLogTable->applySorting($select, 'added', 'DESC');

        $currentPage = $this->getParam('page', 1);
        $recordsPerPage = 20;
        $selectForCount = clone $select;
        $selectForCount->reset(Zend_Db_Select::COLUMNS);
        $alias = $importLogTable->getTableAlias();
        $selectForCount->from(array(), "COUNT(DISTINCT($alias.id))");
        $totalRecords = $db->fetchOne($selectForCount);

        $select->limitPage($currentPage, $recordsPerPage);
        $logs = $importLogTable->fetchObjects($select);

        Zend_Registry::set('pagination', array(
            'page' => $currentPage,
            'per_page' => $recordsPerPage,
            'total_results' => $totalRecords,
        ));

        $this->view->logs = $logs;
        $this->view->severity = $severity;
    }
}
