<?php

namespace NinjaForms\ExcelExport\Contracts;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
/**
 * Provides a spreadsheet object
 */
interface SpreadsheetFactory
{

    public function makeSpreadsheetObject(?string $tempFileName = 'notPassed'):Spreadsheet;

}
