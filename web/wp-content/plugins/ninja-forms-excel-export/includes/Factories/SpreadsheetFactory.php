<?php

namespace NinjaForms\ExcelExport\Factories;

use NinjaForms\ExcelExport\Contracts\SpreadsheetFactory as ContractsSpreadsheetFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Provides a spreadsheet object
 */
class SpreadsheetFactory implements ContractsSpreadsheetFactory
{
    /** @var Spreadsheet */
    private $spreadsheet;

    public function makeSpreadsheetObject(?string $tempFileName = 'notPassed'):Spreadsheet
    {
        if ('notPassed' === $tempFileName) {

            $this->makeNewSpreadsheetObject();
        } else {

            $this->loadExistingSpreadsheetObject($tempFileName);
        }

        return $this->spreadsheet;
    }

    /**
     * Make/load Excel object with current position
     */
    protected function loadExistingSpreadsheetObject($tempFileName)
    {
        $this->spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFileName);
    }

    /**
     * Make/load Excel object with current position
     */
    protected function makeNewSpreadsheetObject()
    {
        $this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    }
}
