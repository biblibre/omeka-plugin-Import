<?php

interface Import_Reader extends Iterator
{
    public function getLabel();
    public function getAvailableFields();
}
