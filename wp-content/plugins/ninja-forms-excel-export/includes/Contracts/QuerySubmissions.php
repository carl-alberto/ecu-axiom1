<?php

namespace NinjaForms\ExcelExport\Contracts;

interface QuerySubmissions
{

    /**
     * Return a collection of submissions
     *
     * @param string $formId
     * @param integer $subsPerPage
     * @param integer $iteration
     * @param array $filters
     * @return array
     */
    public function querySubmissions(string $formId, int $subsPerPage, int $iteration, array $filters): array;
}
