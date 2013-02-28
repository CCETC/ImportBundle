<?php

namespace CCETC\ImportBundle\Import\Handler;

/**
 * A class to manage the importing of an xls spreadsheet
 */
class XLSHandler
{
    /**
     * The spreadsheet to import
     * @var PHPExcel Spreadsheet
     */
    private $spreadsheet;
    
    /**
     * The SonataAdmin class to import to
     * @var SonataAdmin/Admin
     */
    public $admin;
    
    /**
     * Array of string representations of insertions made
     * @var string
     */
    public $insertions;
    
    /**
     * Array of string representations of updatse made
     * @var string
     */
    public $updates;
    
    /**
     * Array of string representations of deletions made
     * @var string
     */
    public $deletions;
    
    /**
     * Array of string representations of duplicates found and ignored
     * @var string
     */
    public $duplicates;

    /**
     * Array of string representations of leas not found and ignored
     * @var string
     */
    public $notFound;    
    
    public function init($filename)
    {
        $this->spreadsheet = \PHPExcel_IOFactory::load($filename);
        $this->insertions = array();
        $this->updates = array();
        $this->deletions = array();
        $this->duplicates = array();
    }

    public function processRows()
    {
        $row = 2; // assumes first row is headings
        $atAnEmptyRow = false;
        while(!$atAnEmptyRow) {
            if($this->rowIsEmpty($row)) {
                $atAnEmptyRow = true;
            } else {
                $this->processRow($row);
            }
            $row++;
        }
    }
    
    public function processRow($row) {
        // should be implemented by a child class
    }

    public function rowIsEmpty($row)
    {
        return trim($this->getVal('A' . $row)) == ''
                && trim($this->getVal('B' . $row)) == ''
                && trim($this->getVal('C' . $row)) == ''
                && trim($this->getVal('D' . $row)) == ''
                && trim($this->getVal('E' . $row)) == ''
                && trim($this->getVal('F' . $row)) == '';
    }

    public function createObject($object, $row = null)
    {
        $this->admin->create($object);
        $this->postPersist($object, $row);
    }

    public function updateObject($object, $row = null)
    {
        $this->admin->update($object);
        $this->postUpdate($object, $row);
    }
    
    public function postPersist($object, $row)
    {
        
    }
    
    public function postUpdate($object, $row)
    {
        
    }
    
    public function getVal($cell)
    {
        if(trim($this->spreadsheet->getActiveSheet()->getCell($cell)->getValue()) == "") {
            return null;
        } else {
            return trim($this->spreadsheet->getActiveSheet()->getCell($cell)->getValue());
        }
    }

    protected function getBoolVal($cell)
    {
        if($this->isYes($this->getVal($cell))) {
            return true;
        } else if($this->isNo($this->getVal($cell))) {
            return false;
        } else {
            return null;
        }
    }

    protected function isYes($value)
    {
        $value = (string) $value;
        $value = trim(strtoupper($value));
        return in_array($value, array('YES', 'Y', '1'));
    }

    protected function isNo($value)
    {
        $value = (string) $value;
        $value = trim(strtoupper($value));
        return in_array($value, array('NO', 'N', '0'));
    }

    protected function getDateVal($cell)
    {
        return $this->getAbstractDateTimeVal($cell, "M/D/YYYY");
    }

    protected function getTimeVal($cell)
    {
        return $this->getAbstractDateTimeVal($cell, "H:i:s");
    }

    protected function getDateTimeVal($cell)
    {
        return $this->getAbstractDateTimeVal($cell, "M/D/YYYY H:i:s");
    }

    protected function getAbstractDateTimeVal($cell, $pattern)
    {
        $val = $this->getVal($cell);

        if(isset($val)) {
            return \PHPExcel_Style_NumberFormat::toFormattedString($val, $pattern);
        } else {
            return null;
        }
    }

}