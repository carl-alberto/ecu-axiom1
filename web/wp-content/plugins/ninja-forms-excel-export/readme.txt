=== Ninja Forms - Excel Export ===
Contributors: haet
Tags: ninjaforms, excel, form, export, spreadsheet
Requires at least: 5.3
Tested up to: 6.1.1
Stable tag: 3.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Export selected fields from your Ninja Forms entries to Excel spreadsheet file format.

== Description ==

= Advanced data export to Excel Open XML (XLSX) spreadsheet file format =

Exporting form submissions with multiline fields normally ends up with merged columns and manual corrections. Another possible problems are visitors typing your csv seperator into one of your input fields, causing a column shift in your CSV file.

Ninja Forms Spreadsheet Export has two features to bypass these problems


  1. export your data to the reliable Excel Open XML (XLSX) spreadsheet file format 
  2. choose the fields you want to appear in your exported Excel file.

== Installation ==
1. Upload folder `ninja-forms-spreadsheet` to the `/wp-content/plugins/` directory

    or upload .zip file in the \'Plugins\' menu 

    or search in Plugin repository (\'Plugins\'->\'Add New\')
2. Activate the plugin through the \'Plugins\' menu in WordPress

== Changelog ==
= 3.3.5 (12 January 2023) =
Fixes:
- Export filter was not correctly #43 
- Remove deprecate codebase #48 
- Remove archived PHPExcel package

Other:
- Automate testing

= 3.3.4 (15 March 2022) =
*Bug Fixes*
  - Replace Array output with correct fieldset repeater columns

= 3.3.3 (10 January 2022) =
*Bug Fixes*
  - Fix deprecated curly brace issue in PHP 8

*Other*
  - Add automated build and Release  
= 3.3.2 (10 September 2020) =

Bugs:

* Fixed error that was limiting exports to 200 rows.

= 3.3.1 =
* improved compatibility with file uploads
* improved backend CSS

= 3.3 =
* added filters
* Changed PHP-Excels TMP dir
* load fields by id instead of key

= 3.1 =
* made fields sortable
* save field settings
* fixed error if Ninja Forms is disabled
* use admin labels if available

= 3.0.1 =

* Fixed capabilities filter

= 3.0 =

* Updated to work with NF THREE

= 1.6 =

* Fixed handling of single quotes
* Added support for Ninja Forms file-uploads extension

= 1.5 =

* Freeze header row
* Automatic column width

= 1.4 =

* Fixed a bug exporting more than 26 Columns to Excel (A-Z, AA, AB, AC, ...)
* Added choice for XLS and XLSX file type to increase compatibility

= 1.3 (21 September 2015) =

* Fixed compatibility with Multipart forms

= 1.2 (1 September 2015) =

* Preserve leading zero for numbers entered as text
* Added ID column

= 1.1 (10 August 2015) =

* Added batch processing to export thousands of submissions. Tested up to 25000 entries on a shared hosting with limited memory and limited execution time.

= 1.0.1 (8 July 2015) =

* Fixed a bug with empty fields

= 1.0 (7 July 2015) =

* Initial Release