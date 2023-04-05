<?php 
namespace NinjaForms\ExcelExport\Factories;

use NinjaForms\ExcelExport\Contracts\QuerySubmissions as ContractsQuerySubmissions;
use NinjaForms\ExcelExport\Contracts\FormDefinition as ContractsFormDefinition;

use NinjaForms\ExcelExport\Handlers\QuerySubmissions;
use NinjaForms\ExcelExport\Handlers\FormDefinition;

/**
 * Provide query objects 
 */
class NfDatabaseQueryFactory{


    /**
     * Make QuerySubmissions object working with CPT data
     *
     * @return ContractsQuerySubmissions
     */
    public function makeQuerySubmissions(): ContractsQuerySubmissions
    {
        $return = new QuerySubmissions();

        return $return;
    }

    /**
     * Make FormDefinition object working with NF_Database_Models data
     *
     * @return ContractsFormDefinition
     */
    public function makeFormDefinition(): ContractsFormDefinition
    {
        $return = new FormDefinition();

        return $return;
    }
}