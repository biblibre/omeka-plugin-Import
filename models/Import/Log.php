<?php

class Import_Log extends Omeka_Record_AbstractRecord
{
    public $import_id;
    public $severity;
    public $message;
    public $params;
    public $added;
}
