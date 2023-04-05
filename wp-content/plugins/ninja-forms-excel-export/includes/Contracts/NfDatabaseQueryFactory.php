<?php 
namespace NinjaForms\ExcelExport\Contracts;

use NinjaForms\ExcelExport\Contracts\QuerySubmissions as ContractsQuerySubmissions;
use NinjaForms\ExcelExport\Contracts\FormDefinition as ContractsFormDefinition;


/**
 * Provide query objects 
 */
interface NfDatabaseQueryFactory{


    /**
     * Make QuerySubmissions object
     *
     * @return ContractsQuerySubmissions
     */
    public function makeQuerySubmissions(): ContractsQuerySubmissions;


    /**
     * Make FormDefinition object
     *
     * @return ContractsFormDefinition
     */
    public function makeFormDefinition(): ContractsFormDefinition;
}