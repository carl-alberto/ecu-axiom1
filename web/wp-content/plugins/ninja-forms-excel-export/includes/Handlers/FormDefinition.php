<?php

namespace NinjaForms\ExcelExport\Handlers;

use NinjaForms\ExcelExport\Contracts\FormDefinition as ContractsFormDefinition;

use NinjaForms\Includes\Handlers\Field;

class FormDefinition implements ContractsFormDefinition
{


    /**
     * Form Id
     *
     * @var string
     */
    private $formId;

    /**
     * Array of field ids selected for output
     *
     * @var array
     */
    private $selectedFieldIds;

    /**
     * Form title
     *
     * @var string
     */
    private $formTitle;

    /** @var \NF_Database_Models_Field[] */
    private $nfFieldsCollection;

    /**
     * Field labels, keyed on field key
     *
     * @var array
     */
    private $labels = [];

    /**
     * Admin field labels, keyed on field key
     *
     * @var array
     */
    private $adminLabels = [];

    /**
     * Array of field types keyed on field key
     * @var array
     */
    private $fieldTypes = [];

    /**
     * Array of field Ids keyed on field key
     * @var array
     */
    private $fieldIds = [];

    /**
     * Repeater field definitions
     *
     * @var array
     */
    private $repeaterFields=[];

    /**
     * Return array, keyed on field id, of the count of fields within a repeater
     *
     * @var array
     */
    private $repeaterFieldsCount = [];

    /** @inheritDoc */
    public function setFormParameters(string $formId, array $selectedFieldIds): ContractsFormDefinition
    {
        $this->formId = $formId;
        $this->selectedFieldIds = $selectedFieldIds;

        $this->constructFieldLookups();

        return $this;
    }

    /**
     * Construct labels/adminLabels from submission aggregate
     *
     * @return void
     */
    protected function constructFieldLookups(): void
    {
        $this->nfFieldsCollection = \Ninja_Forms()->form($this->formId)->get_fields();

        foreach ($this->nfFieldsCollection as $field) {
            if (!in_array($field->get_id(), $this->selectedFieldIds)) {
                continue;
            }

            $this->setFieldProperties($field);
        }
    }


    /**
     * Set form field properties
     *
     * @param \NF_Database_Models_Field $field
     * @return void
     */
    protected function setFieldProperties(\NF_Database_Models_Field $field): void
    {
        try {

            $slug = $field->get_id();

            if ('repeater' === $field->get_setting('type')) {
                $this->extractRepeaterFieldColumns($field);
                return;
            }
            $fieldArray = [
                'label' => $field->get_setting('label'),
                'type' => $field->get_setting('type'),
                'id' => $field->get_id(),
                'admin_label' => $field->get_setting('admin_label'),
            ];

            $this->pushDefinitionsBySlug($slug, $fieldArray);
        } catch (\Throwable $e) {
            $message = $e->getMessage();

            \error_log('Handlers\FormDefinition.setFieldProperties');
            \error_log($message);

            throw $e;
        }
    }

    /**
     * Extract field definitions within a repeater field
     *
     * @param \NF_Database_Models_Field $field
     * @return void
     */
    protected function extractRepeaterFieldColumns(\NF_Database_Models_Field $field): void
    {
        try {
            // Add the repeater field parent to the lookups
            $this->fieldTypes[$field->get_id()] = $field->get_setting('type');
            $this->fieldIds[$field->get_id()] = $field->get_id();

            $repeaterFieldsCollection = $field->get_setting('fields');

            $this->repeaterFieldsCount[$field->get_id()]=count($repeaterFieldsCollection);

            if (empty($repeaterFieldsCollection)) {
                return;
            }

            // iterate each SubmissionField within the repeater fields collection
            foreach ($repeaterFieldsCollection as $repeaterField) {
                $slug = $repeaterField['id'];
                $this->pushDefinitionsBySlug($slug, $repeaterField);
                $nfField = Field::fromArray($repeaterField);
                $this->repeaterFields[$slug]=$nfField;
            }
        } catch (\Throwable $e) {
            error_log('Handlers\FormDefinition.extractRepeaterFieldColumns');
            error_log($e->getMessage());
        }
    }

    /**
     * Push label/type/id definitions keyed on slug
     *
     * @param string $slug
     * @param array $fieldArray
     * @return void
     */
    protected function pushDefinitionsBySlug(string $slug, array $fieldArray): void
    {
        try {
            $this->labels[$slug] = $fieldArray['label'];

            $this->fieldTypes[$slug] = $fieldArray['type'];

            $this->fieldIds[$slug] = $fieldArray['id'];

            $this->adminLabels[$slug] = $this->determineAdminLabel($fieldArray);
        } catch (\Throwable $e) {
            error_log('Handlers\FormDefinition.pushDefinitionsBySlug');
            error_log(serialize($fieldArray));
            error_log($e->getMessage());
        }
    }



    /**
     * Determine admin label property - fallback to label property if not set
     *
     * @param array $field
     * @return string
     */
    protected function determineAdminLabel(array $fieldArray): string
    {
        $adminLabel = $fieldArray['admin_label'];


        if ('' === $adminLabel || \is_null($adminLabel)) {
            $return = $fieldArray['label'];
        } else {
            $return = $adminLabel;
        }

        return $return;
    }

    protected function setFormTitle(): void
    {
        $form = Ninja_Forms()->form($this->formId)->get();
        $this->formTitle = $form->get_setting('title');
    }

    /**
     * Return array of field types keyed on field keys
     * 
     * @return array
     */
    public function getFieldTypes(): array
    {
        return $this->fieldTypes;
    }

    /**
     * Return array of field Ids keyed on field keys
     */
    public function getFieldIds(): array
    {
        return $this->fieldIds;
    }

    /**
     * Return array of field labels keyed on field keys
     * 
     * If hiddenFieldTypes array is set, labels filtered to hide those types
     * 
     * @param bool $useAdminLabels Optionally use admin_labels
     * @return array
     */
    public function getLabels(?bool $useAdminLabels = false): array
    {
        if ($useAdminLabels) {
            $return  = $this->adminLabels;
        } else {
            $return  = $this->labels;
        }

        return $return;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getFormId(): string
    {
        return $this->formId;
    }

    /**
     * Get form title
     *
     * @return  string
     */
    public function getFormTitle(): string
    {
        if (!isset($this->formTitle)) {
            $this->setFormTitle();
        }

        return $this->formTitle;
    }

    /**
     * Get return array, keyed on field id, of the count of fields within a repeater
     *
     * @return  array
     */ 
    public function getRepeaterFieldsCount():array
    {
        return $this->repeaterFieldsCount;
    }

    /**
     * Get repeater field definitions
     *
     * @return  array
     */ 
    public function getRepeaterFields():array
    {
        return $this->repeaterFields;
    }
}
