<?php
namespace Kennziffer\KeQuestionnaire\Utility;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Kennziffer.com <info@kennziffer.com>, www.kennziffer.com
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
//require_once(PATH_site.'typo3conf/ext/ke_questionnaire/Resources/Private/Tcpdf/tcpdf.php');
//require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_questionnaire').'Classes/Lib/Mpdf/mpdf.php');

/**
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PdfExport {
	/**
     * Create PDF
     * 
     * @param string $html
     * @param string $filename
     */
    public function createPdfFromHTML($html,$filename = "ke_questionnaire.pdf"){

        require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_questionnaire').'Classes/Lib/Mpdf/mpdf.php');

        $this->createAndCheckTmpFile();
        
        $pdf = new \mPDF();
	$pdf->WriteHtml($html);
	$pdf->Output($filename, 'D');
	//$pdf->Output();
	exit;
    }
    
    /**
     * Try to write check file to typo3temp folder
     */
    protected function createAndCheckTmpFile() {
        $this->tmpFileAndPath = PATH_site . 'typo3temp/ke_questionnaire/pdf/TEST';
        if(!is_dir(PATH_site . 'typo3temp/ke_questionnaire/pdf')) {
            @mkdir(PATH_site . 'typo3temp/ke_questionnaire/pdf');
        }
        //create htaccess file
        $htaccess = '
Order Deny,Allow
Deny from all
Allow from 127.0.0.1

<FilesMatch ".*\.(css|js)$">
	Order Allow,Deny
	Allow from all
</FilesMatch>';
        $htaccessFileAndPath = PATH_site . 'typo3temp/ke_questionnaire/.htaccess';
        \TYPO3\CMS\Core\Utility\GeneralUtility::writeFileToTypo3tempDir($htaccessFileAndPath, $htaccess);        
        $htaccessFileAndPath = PATH_site . 'typo3temp/ke_questionnaire/pdf/.htaccess';
        \TYPO3\CMS\Core\Utility\GeneralUtility::writeFileToTypo3tempDir($htaccessFileAndPath, $htaccess);        
    }
}

?>