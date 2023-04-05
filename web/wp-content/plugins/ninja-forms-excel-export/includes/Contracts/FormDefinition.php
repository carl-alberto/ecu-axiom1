<?php
namespace NinjaForms\ExcelExport\Contracts;
/**
 * Contract defining required methods for defining a form
 * 
 */
interface FormDefinition {

    /**
     * Set parameters for the form definition
     *
     * @param string $formId
     * @param array $selectedFieldIds Field Ids to populate in definition
     * @return FormDefinition
     */
    public function setFormParameters(string $formId, array $selectedFieldIds): FormDefinition;
  
    /**
     * Return the form Id
     * @return int
     */
    public function getFormId();

    /**
     * Return the form title
     * @return string
     */
    public function getFormTitle();

    /**
     * Return array of field labels 
     * 
     * If hiddenFieldTypes array is set, labels filtered to hide those types
     * 
     * @param bool $useAdminLabels Optionally use admin_labels
     * @return array
     */
    public function getLabels(?bool $useAdminLabels = false) : array ;

    /**
     * Return array of field types
     * 
     * @return array
     */
    public function getFieldTypes() : array ;

    /**
     * Return array of field Ids
     */
    public function getFieldIds() :array ;

    /**
     * Return array, keyed on field id, of the count of fields within a repeater
     *
     * @return array
     */
    public function getRepeaterFieldsCount( ): array;

     /**
     * Get repeater field definitions
     *
     * @return  array
     */ 
    public function getRepeaterFields():array;
}
