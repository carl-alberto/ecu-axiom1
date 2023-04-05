<?php

namespace NinjaForms\ExcelExport\Wrappers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class SpreadsheetObject
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheetObject;

    /** @var int */
    private $rowNumber;

    /**
     * Construct a row of column headers
     */
    public function writeColumnHeaders(int $columnIndex, int $rowNumber, array $formDefinitionHeaders): void
    {

        foreach ($formDefinitionHeaders as $columHeader) {
            $col = Coordinate::stringFromColumnIndex($columnIndex);
            $this->spreadsheetObject->getActiveSheet()->getCell($col . $rowNumber)->setValue($columHeader);
            $this->spreadsheetObject->getActiveSheet()->getStyle($col . $rowNumber)->getFont()->setBold(true);
            $columnIndex++;
        }
 
        $this->spreadsheetObject->getActiveSheet()->freezePane("A2");
    }

        /**
     * Write standard columns
     * 
     * Sequence number and date submitted
     *
     * @param int $columnIndex
     * @param array $sub
     * @return int
     */
    public function writeStandardColumns(int $columnIndex, int $rowNumber, $sequenceNumber,$dateSubmitted): int
    {
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $rowNumber)->setValueExplicit((float) $sequenceNumber, DataType::TYPE_NUMERIC);
        $columnIndex++;

        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $rowNumber)->setValueExplicit($dateSubmitted, DataType::TYPE_STRING);
        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write column values based on value type
     *
     * @param string $type
     * @param integer $columnIndex
     * @param mixed $fieldValue
     * @return integer
     */
    public function writeColumnValue(string $type, int $columnIndex, $fieldValue, $rowNumber): int
    {
        $this->rowNumber = $rowNumber;

        switch ($type) {
            case 'shipping':
            case 'total':
            case 'number':
                $outgoingColumnIndex = $this->writeFloatValueCell($columnIndex, $fieldValue);
                break;
            case 'starrating':
            case 'quantity':
                $outgoingColumnIndex = $this->writeIntegerValueCell($columnIndex, $fieldValue);
                break;
            case 'checkbox':
            case 'optin':
                $outgoingColumnIndex = $this->writeCheckboxValueCell($columnIndex, $fieldValue);
                break;
            case 'listcheckbox':
                $outgoingColumnIndex = $this->writeListCheckboxValueCell($columnIndex, $fieldValue);
                break;
            case 'file_upload':
                $outgoingColumnIndex =  $this->writeFileUploadValueCell($columnIndex, $fieldValue);
                break;
            case 'textarea':
                $outgoingColumnIndex = $this->writeTextareaValueCell($columnIndex, $fieldValue);
                break;
            default:
                $outgoingColumnIndex = $this->writeFallbackValueCell($columnIndex, $fieldValue);
                break;
        }

        return $outgoingColumnIndex;
    }

    /**
     * Write a float value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeFloatValueCell(int $columnIndex, $field_value): int
    {
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit((float) $field_value, DataType::TYPE_NUMERIC);
        $this->spreadsheetObject->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)
            ->getNumberFormat()
            ->setFormatCode('#,##');

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write an integer value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeIntegerValueCell(int $columnIndex, $field_value): int
    {
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit((float)$field_value, DataType::TYPE_NUMERIC);
        $this->spreadsheetObject->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)
            ->getNumberFormat()
            ->setFormatCode('#');

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write checkbox value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeCheckboxValueCell(int $columnIndex, $field_value): int
    {
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit($field_value, DataType::TYPE_STRING);

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write an list checkbox value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeListCheckboxValueCell(int $columnIndex, $field_value): int
    {
        $field_output = $field_value;
        if (is_array($field_value)) {
            $field_output = '';
            foreach ($field_value as $key => $value) {
                if ($field_output == '')
                    $field_output = $value;
                else
                    $field_output .= ', ' . $value;
            }
        }
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit(htmlspecialchars_decode($field_output, ENT_QUOTES), DataType::TYPE_STRING);

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write an file upload value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeFileUploadValueCell(int $columnIndex, $field_value): int
    {
        if (is_array($field_value)) {
            $field_value = implode("\n", $field_value);
        }

        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit(htmlspecialchars_decode($field_value, ENT_QUOTES), DataType::TYPE_STRING);
        $this->spreadsheetObject->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)
            ->getAlignment()
            ->setWrapText(true);

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write an textarea value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeTextareaValueCell(int $columnIndex, $field_value): int
    {
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit(htmlspecialchars_decode($field_value, ENT_QUOTES), DataType::TYPE_STRING);
        $this->spreadsheetObject->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)
            ->getAlignment()
            ->setWrapText(true);

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Write an fallback value to a cell
     *
     * @param int $columnIndex
     * @param mixed $field_value
     * @return int
     */
    private function writeFallbackValueCell(int $columnIndex, $field_value): int
    {
        if (is_array($field_value)) {
            $field_value = implode('; ', $field_value);
        }
        $this->spreadsheetObject->getActiveSheet()->getCell(Coordinate::stringFromColumnIndex($columnIndex) . $this->rowNumber)->setValueExplicit(htmlspecialchars_decode($field_value, ENT_QUOTES), DataType::TYPE_STRING);

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Autosize columns
     *
     * @param integer $columnCount
     * @return void
     */
    public function setAutosize(int $columnCount ): void
    {
        $i=1;
        while($i<=$columnCount){
            $i++;
            $columnString = Coordinate::stringFromColumnIndex($i);
            $this->spreadsheetObject->getActiveSheet()->getColumnDimension($columnString)->setAutoSize(true);
        }
    }

    /**
     * Set SpreadsheetObject
     *
     * @param  Spreadsheet  $spreadsheetObject
     *
     * @return  SpreadsheetObject
     */
    public function setSpreadsheetObject(Spreadsheet $spreadsheetObject): SpreadsheetObject
    {
        $this->spreadsheetObject = $spreadsheetObject;

        return $this;
    }

    /**
     * Get the value of spreadsheetObject
     *
     * @return  Spreadsheet
     */ 
    public function getSpreadsheetObject()
    {
        return $this->spreadsheetObject;
    }
}
