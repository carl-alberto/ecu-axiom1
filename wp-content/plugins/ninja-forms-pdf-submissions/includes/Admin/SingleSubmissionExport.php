<?php

use NinjaForms\Includes\Contracts\SubmissionHandler as ContractSubmissionHandler;
use NinjaForms\Includes\Entities\SingleSubmission;
use NinjaForms\Includes\Abstracts\SubmissionHandler as AbstractSubmissionHandler;

/**
 * Export a single submission from React.js submissions page
 */
class NF_Pdf_Submissions_Admin_SingleSubmissionExport extends AbstractSubmissionHandler implements ContractSubmissionHandler
{
    /** @inheritDoc */
    protected $slug = 'exportPdf';

    /** @inheritDoc */
    protected $responseType = 'download';

    /** @inheritDoc */
    protected $blobType = 'application/pdf';

    /**
     * Filename of the download, including file extension
     *
     * @var string
     */
    protected $filename = '';

    /** @inheritDoc */
    protected function handleSubmission(SingleSubmission $singleSubmission): void
    {
        $subId = $singleSubmission->getSubmissionRecordId();

        $sub_pdf = new NF_Pdf_Submissions_Admin_Submission($subId);

        // Set boolean to populate 'fields' merge tags
        $sub_pdf->setPopulateMergeFields(true);

        $this->download  = $sub_pdf->returnPdf();

        $this->filename = $sub_pdf->getFilename();
    }

    /** @inheritDoc */
    protected function doesAddHandler(SingleSubmission $singleSubmission): bool
    {
        // Always adds Export CSV handler
        return true;
    }

    /** @inheritDoc */
    protected function constructLabel(): void
    {
        $this->label = __('Export PDF', 'nf-pdf');
    }

    /**
     * Return class name of SubmissionHandler
     * @return string 
     */
    public function getHandlerClassName(): string
    {
        return self::class;
    }
}
