<?php

namespace NinjaForms\ExcelExport\Factories;

use NinjaForms\ExcelExport\Contracts\SpreadsheetWriterFactory as ContractsSpreadsheetWriterFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Provides a spreadsheet writer object
 */
class SpreadsheetWriterFactory implements ContractsSpreadsheetWriterFactory
{

   /**
     * Construct desired Excel object
     *
     * @param Spreadsheet $excelObject
     * @return BaseWriter
     */
    public function makeObjectWriter(Spreadsheet $spreadsheetObject, ?bool $isXls=false): BaseWriter
    {
        if ($isXls) {
            $return = new Xls($spreadsheetObject);
        } else {
            $return = new Xlsx($spreadsheetObject);
        }
        return $return;
    }
}
