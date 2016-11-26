<?php

interface Import_Processor
{
    public function setReader(Import_Reader $reader);
    public function setLogger(Zend_Log $log);

    public function process();
}
