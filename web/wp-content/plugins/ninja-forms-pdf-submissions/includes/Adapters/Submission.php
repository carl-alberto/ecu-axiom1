<?php
// namespace NinjaForms\Pdf\Adapters;

class NF_Pdf_Submissions_Adapters_Submission extends NF_Pdf_Submissions_Adapters_Fields
{
    protected $fields;
    /**
     * @var NF_Database_Models_Submission
     */
    protected $submission;

    public function __construct($fields, $form_id, $submission)
    {
        parent::__construct($fields, $form_id);
        $this->submission = $submission;
    }

    /*
    |--------------------------------------------------------------------------
    | ArrayAccess
    |--------------------------------------------------------------------------
    */

    public function offsetExists($offset): bool
    {
        if (isset($this->fields[$offset])) {
            return true;
        }

        if (isset($this->fields_by_key[$offset])) {
            return true;
        }

        return $this->offsetMaybeCreate($offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (isset($this->fields[$offset])) {
            return $this->fields[$offset];
        }
        if (isset($this->fields_by_key[$offset])) {
            return $this->fields_by_key[$offset];
        }
        return $this->offsetMaybeCreate($offset);
    }

    protected function offsetMaybeCreate($offset)
    {
        foreach ($this->fields as $field) {
            if (is_array($field)) {
                return false;
            }

            if ($offset != $field->get_setting('key')) {
                continue;
            }

            return $this->fields[$offset] = [
                'id' => $field->get_id(),
                'type' => $field->get_setting('type'),
                'label' => $field->get_setting('label'),
                'admin_label' => $field->get_setting('admin_label'),
                'value' => $this->submission->get_field_value($field->get_id()),
                'key' => $field->get_setting('key'),
                'fields' => $field->get_setting('fields', [])
            ];
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Iterator
    |--------------------------------------------------------------------------
    */

    #[\ReturnTypeWillChange]
    public function current()
    {
        $field = current($this->fields);

        return [
            'id' => $field->get_id(),
            'type' => $field->get_setting('type'),
            'label' => $field->get_setting('label'),
            'admin_label' => $field->get_setting('admin_label'),
            'value' => $this->submission->get_field_value($field->get_id()),
            'key' => $field->get_setting('key'),
            'fields' => $field->get_setting('fields', [])
        ];
    }
}
