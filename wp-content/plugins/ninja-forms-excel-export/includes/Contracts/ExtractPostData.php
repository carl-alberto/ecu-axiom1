<?php 
namespace NinjaForms\ExcelExport\Contracts;

interface ExtractPostData{
    
    /**
     * Get form Id
     *
     * @return  string
     */ 
    public function getFormId():string;

    /**
     * Get field Ids
     *
     * @return  array
     */ 
    public function getFieldIds():array;

    /**
     * Get filters
     */ 
    public function getFilters();

    /**
     * Get is XLS (default is XLSX)
     *
     * @return  bool
     */ 
    public function isXls():bool;

    /**
     * Get temporary filename
     *
     * @return  string
     */ 
    public function getTempFileName():string;

    /**
     * Get current iteration
     *
     * @return  int
     */ 
    public function getIteration():int;
}