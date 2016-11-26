<?php

class Import_Reader_CsvReader
    implements Import_Reader, Import_Configurable, Import_Parametrizable
{
    use Import_ConfigurableTrait, Import_ParametrizableTrait;

    protected $fh;

    protected $currentRow;
    protected $currentRowData;
    protected $headers;

    public function getLabel()
    {
        return 'CSV';
    }

    public function current()
    {
        $entry = new Import_Entry_CsvRow($this->headers, $this->currentRowData);

        return $entry;
    }

    public function key()
    {
        return $this->currentRow;
    }

    public function next()
    {
        $this->currentRowData = $this->getRow($this->fh);
        $this->currentRow++;
    }

    public function rewind()
    {
        if (!isset($this->fh)) {
            $this->fh = fopen($this->getParam('filename'), 'r');
        } else {
            fseek($this->fh, 0);
        }

        $this->headers = $this->getRow($this->fh);
        $this->currentRowData = $this->getRow($this->fh);
        $this->currentRow = 0;
    }

    public function valid()
    {
        return is_array($this->currentRowData);
    }

    public function getAvailableFields()
    {
        $fields = array();

        $filename = $this->getParam('filename');
        if ($filename) {
            $fh = fopen($filename, 'r');
            if (false !== $fh) {
                $fields = $this->getRow($fh);
                fclose($fh);
            }
        }

        return $fields;
    }

    public function getConfigForm()
    {
        return new Import_Form_CsvReaderConfigForm(array(
            'readerConfig' => $this->getConfig(),
        ));
    }

    public function handleConfigForm(Zend_Form $form)
    {
        $values = $form->getValues();
        $config = array(
            'delimiter' => $values['delimiter'],
            'enclosure' => $values['enclosure'],
            'escape' => $values['escape'],
        );

        $this->setConfig($config);
    }

    public function getParamsForm()
    {
        return new Import_Form_CsvReaderParamsForm(array(
            'reader' => $this,
        ));
    }

    public function handleParamsForm(Zend_Form $form)
    {
        $values = $form->getValues();
        $filename = $form->file->getFileName();
        $this->setParams(array(
            'filename' => $filename,
            'delimiter' => $values['delimiter'],
            'enclosure' => $values['enclosure'],
            'escape' => $values['escape'],
        ));
    }

    protected function getRow($fh)
    {
        $delimiter = $this->getParam('delimiter', ',');
        $enclosure = $this->getParam('enclosure', '"');
        $escape = $this->getParam('escape', '\\');

        $fields = fgetcsv($fh, 0, $delimiter, $enclosure, $escape);
        if (is_array($fields)) {
            return array_map('trim', $fields);
        }
    }
}
