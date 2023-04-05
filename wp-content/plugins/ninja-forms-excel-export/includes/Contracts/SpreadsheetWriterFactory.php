<?php

namespace NinjaForms\ExcelExport\Contracts;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;

/**
 * Provides a spreadsheet writer object
 */
interface SpreadsheetWriterFactory
{

   /**
     * Construct desired Excel object
     *
     * @param Spreadsheet $excelObject
     * @return BaseWriter
     */
    public function makeObjectWriter(Spreadsheet $spreadsheetObject, ?bool $isXls=false): BaseWriter;
}
