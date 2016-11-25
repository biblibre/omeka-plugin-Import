<?php

class Table_Import_Import extends Omeka_Db_Table
{
    public function getLastImports()
    {
        return $this->findBy(array(
            'sort_field' => 'id',
            'sort_dir' => 'd',
        ), 5);
    }
}
