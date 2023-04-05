<?php

// namespace NinjaForms\Pdf\Adapters;

class NF_Pdf_Submissions_Adapters_Fields implements ArrayAccess, Iterator
{
    protected $fields;
    protected $fields_by_key = array();

    public function __construct($fields = array(), $form_id='')
    {
        foreach ($fields as $field) {
            if (is_array($field)) {
                if (!isset($field['key'])) {
                    continue;
                }
                $key = $field['key'];
            } else {
                if (!method_exists($field, 'get_setting')) {
                    continue;
                }
                $key = $field->get_setting('key');
            }
            $this->fields_by_key[$key] = $field;
        }
        $fields_sorted = apply_filters('ninja_forms_get_fields_sorted', array(), $this->fields, $this->fields_by_key, $form_id);

        if (!empty($fields_sorted)) {
            $this->fields = $fields_sorted;
        } else {
            $this->fields = $fields;
        }
    }

    public function get_value($id)
    {
        return $this->fields[$id]['value'];
    }

    /*
    |--------------------------------------------------------------------------
    | ArrayAccess
    |--------------------------------------------------------------------------
    */

    public function offsetSet($offset, $value):void
    {
        if (is_null($offset)) {
            $this->fields[] = $value;
        } else {
            $this->fields[$offset] = $value;
        }
    }

    public function offsetExists($offset):bool
    {
        if (isset($this->fields[$offset])) {
            return true;
        }
        if (isset($this->fields_by_key[$offset])) {
            return true;
        }
        return false;
    }

    public function offsetUnset($offset):void
    {
        unset($this->fields[ $offset ]);
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
        return array(
            'type' => '',
            'label' => '',
            'admin_label' => '',
            'value' => ''
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Iterator
    |--------------------------------------------------------------------------
    */

    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->fields);
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->fields);
    }

    public function next():void
    {
        next($this->fields);
    }

    public function rewind():void
    {
        reset($this->fields);
    }

    public function valid():bool
    {
        $return = false;
        if(current($this->fields)){
            $return = true;
        }

        return $return;
    }
}
