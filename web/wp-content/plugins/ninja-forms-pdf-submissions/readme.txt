=== Ninja Forms - PDF Submissions ===
Contributors: Patrick Rauland
Tags: form, forms, ninja forms, pdf, submission, download
Requires at least: 5.4
Tested up to: 6.1.1
Stable tag: 3.2.1

License: GPLv2 or later

== Description ==

An extension for Ninja Forms that can automagically attach a PDF file of the form submission along with an email notification. You can also download PDF copies of any form submission in the WordPress admin.

= Features =

* Automatically attach a PDF copy of the form submission with the admin email notification
* Download a PDF copy of a form submission via the WordPress admin

== Screenshots ==

To see up to date screenshots, visit the [Ninja Forms](http://wpninjas.com/ninja-forms/) page.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `ninja-forms-pdf-submissions` directory to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Use ==

For help and video tutorials, please see the [documentation on the Ninja Forms website](http://wpninjas.com/ninja-forms/docs/).

== Upgrade Notice ==
= 3.2.1 (8 February 2023) =
*Bug Fixes:*
- Remove warning by adding required return types
- Ensure false value in repeater doesn't throw error

*Other:*
- Add testing reproduction and code coverage


*Bugs:*

* Correct output of fieldset fields, was showing 'Array Array Array'

== Changelog ==
= 3.2.1 (8 February 2023) =
*Bug Fixes:*
- Remove warning by adding required return types
- Ensure false value in repeater doesn't throw error

*Other:*
- Add testing reproduction and code coverage

= 3.2.0 - 2022.03.07 =
*Feature:*
- Add filter to customize basic mPDF configuration

= 3.1.7 - 2022.01.03 =

*Bugs:*

* Correct output of fieldset fields, was showing 'Array Array Array'

= 3.1.6 - 2021.11.15 =

*Bugs:*

* Enable missing merge tags for output on document
* Enable filename customizations (requires Ninja Forms v3.6.6 )
* Ensure custom filename is a valid filename to prevent submission error
* Remove obsolete legacy banner

= 3.1.5 - 2021.10.18 =

*Bugs:*

* Fix custom output does not trigger on Windows servers
* Fix custom body not present if submission is not stored
* Add link to create PDF on new submissions page

= 3.1.4 - 2021-01-25 =

*Bugs:*

* Resolved an issue that was causing the contents of paragraph fields to get cut off if they contained line breaks.
* Corrected an oversight that sometimes resulted in PDF files being corrupted upon export.

= 3.1.3 - 2020-09-09 =

*Changes:*

* Added support for custom PDF body.
* Added support for custom PDF filename.

= 3.1.2 - 2020-02-12 =

*Bugs:*

* Corrected an issue that sometimes caused PDF files to be truncated under certain conditions.

= 3.1.1 - 2020-01-06 =

*Bugs:*

* Corrected an issue that sometimes caused blank pages to be inserted into PDF exports.

= 3.1.0 - 2019-11-25 =

*Changes:*

* We've updated our PDF library to use MPDF.
* Added PDF customization settings, which can be found under the Advanced tab in the form builder.

= 3.0.5 - 2018-03-09 =

*Changes:*

* Fixed 'No Block-level Parent Found' issue in DOM PDF. Note: if you customized your PDF template file you'll have to use the new template file and redo your changes.
* Added checks for some null fields

= 3.0.4 - 2017-09-13 =

*Changes:*

* Add a check for Ninja Forms to be active before running the PHP version check.
* Added support for Field Keys when customizing the PDF.

= 3.0.3 - 2017-05-03 =

*Bugs:*

* Fixed a bug that prevented admin labels from being displayed in PDF document.

*Tweaks:*

* Allow modification of fields that are used in document via filter: nf_sub_document_fields
* Added a notice for websites that run PHP 5.5 and lower. We'll drop support for < PHP 5.6 in coming version.

= 3.0.2 - 2017-03-17 =

*Bugs:*

* Fixed a bug that prevented correct pdf.css stylesheet overriding.
* Fixed a bug that caused fields to be in incorrect order.
* Fixed a bug that incorrectly attached PDF to emails.

= 3.0.1 - 2017-10-24 =

*Bugs:*

* Fixed a bug with attaching the PDF to the Email.

= 3.0.0 - 2017-01-15 =

* Updated with Ninja Forms v3.x compatibility
* Deprecated Ninja Forms v2.9.x compatible code

= 1.3.5 - 2015-10-07 =
* Fix - Fixed compatibility issues with Ninja Forms: Table Editor

= 1.3.4 - 2015-09-16 =
* Fix - Fixed a fatal that was caused by accessing an array of an object pre PHP 5.4.
* Tweak - add support for multiple uploaded files in PDF.

= 1.3.3 - 2015-06-04 =
* Fix   - Adding support for Fields Uploads extension. Will display the url to the file.
* Tweak - Adding a form submission ID which can be added to the PDF. See the ninja_forms_submission_pdf_fetch_sequential_number filter.
* Tweak - Adding paragraph tags to the field values with wpautop(). Necessary for multi-paragraph values.
* Tweak - The ninja_forms_submission_pdf_name filter now works for email attachments and for PDFs downloaded via the admin

= 1.3.2 - 2014-12-01 =
* Tweak - Removing fields from the PDF which are conditionally not shown to the user

= 1.3.1 - 2014-10-29 =
* Tweak - Adding support for table editor

= 1.3 - 2014-09-15 =
* Tweak - Using new notifications settings in Ninja Forms 2.8

= 1.2 - 2014-07-28 =
* Feature - Adding ninja_forms_submission_pdf_fetch_date filter to add the submission date to the form
* Tweak   - Using new admin_label in pdf if available
* Tweak   - Passing form fields & form ID into template

= 1.1 - 2014-06-24 =
* Feature - Attach PDFs to user email

= 1.0 =
* Initial release! PDF all the things!
