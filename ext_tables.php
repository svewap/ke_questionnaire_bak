<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Kennziffer.'.$_EXTKEY,
	'Questionnaire',
	'KeQ Inserts a questionnaire'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Kennziffer.'.$_EXTKEY,
	'QList',
	'KeQ List of questionnaires'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Kennziffer.'.$_EXTKEY,
	'View',
	'KeQ FeView of Participations'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Questionnaire');
include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Classes/Utility/AddActivatorsToDependancy.php');

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_questionnaire';
$pluginSignature2 = strtolower($extensionName) . '_qlist';
$pluginSignature5 = strtolower($extensionName) . '_view';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature2] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature2] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature5] = 'layout,select_key,recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/questionnaire.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature2, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/qlist.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content.pi_flexform.kequestionnaire_questionnaire.list', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_flexforms.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_question', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_question.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_question');
$TCA['tx_kequestionnaire_domain_model_question'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'type' => 'type',
		'thumbnail' => 'image',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,show_title,text,help_text,image,image_position,is_mandatory,must_be_correct,answers,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Question.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/question.svg'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_answer', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_answer.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_answer');
$TCA['tx_kequestionnaire_domain_model_answer'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'type' => 'type',
		'thumbnail' => 'image',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,value,text,is_correct_answer,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Answer.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/answer.svg'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_resultquestion', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_resultquestion.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_resultquestion');
$TCA['tx_kequestionnaire_domain_model_resultquestion'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_resultquestion',
		'label' => 'answers',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'fe_cruser_id' => 'fe_cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'answers,question,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/ResultQuestion.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/resultquestion.svg'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_resultanswer', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_resultanswer.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_resultanswer');
$TCA['tx_kequestionnaire_domain_model_resultanswer'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_resultanswer',
		'label' => 'answer',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'fe_cruser_id' => 'fe_cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'answer,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/ResultAnswer.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/resultanswer.svg'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_result', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_result.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_result');
$TCA['tx_kequestionnaire_domain_model_result'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result',
		'label' => 'finished',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'fe_cruser_id' => 'fe_cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'finished,questions,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Result.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/result.svg'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_range', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_range.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_range');
$TCA['tx_kequestionnaire_domain_model_range'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_range',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'fe_cruser_id' => 'fe_cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'text,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Range.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/range.svg'
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_authcode', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_authcode.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_authcode');
$TCA['tx_kequestionnaire_domain_model_authcode'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_authcode',
		'label' => 'auth_code',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'fe_cruser_id' => 'fe_cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'authcode,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/AuthCode.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/authcode.svg'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_dependancy', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_dependancy.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_dependancy');
$TCA['tx_kequestionnaire_domain_model_dependancy'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_dependancy',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'fe_cruser_id' => 'fe_cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'answer,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Dependancy.php',
		'iconfile' => t3lib_extMgm::extRelPath('ke_questionnaire').'Resources/Public/Icons/dependancy.svg'
	),
);

/*
 * Backend-Modules
 */
if (TYPO3_MODE === 'BE'){
   $mainModuleName = 'keQuestionnaireBe';
   
	//Register Image Area Select Wizard
   // Deprecated since 7.6, needed for 6.2, will be removed with 8
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModulePath(
        'wizard_imageAreaSelect',
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Controller/Wizard/ImageAreaSelectWizard/'
	);	
 
    // Hack damit das Modul direkt nach dem Web Modul erscheint
    // die Angabe der $position in addModule() funktioniert hier leider nicht
    if (!isset($TBE_MODULES[$mainModuleName])) {
        $temp_TBE_MODULES = array();
        foreach($TBE_MODULES as $key => $val) {
            if($key == 'web') {
                $temp_TBE_MODULES[$key] = $val;
                $temp_TBE_MODULES[$mainModuleName] = '';
            } else {
                $temp_TBE_MODULES[$key] = $val;
            }
        }
        $TBE_MODULES = $temp_TBE_MODULES;
    }
	
    // Hauptmodul erstellen
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Kennziffer.'.$_EXTKEY,            # Extension-Key
		$mainModuleName,				   # Kategorie
		'',								   # Modulname
		'',                                # Position
		Array ( ),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.$_EXTKEY.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang_mod.xml',				
		)
	);
	
    // Authcode Backend Modul der Extension
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Kennziffer.'.$_EXTKEY,                  # Extension-Key
		$mainModuleName,		   # Kategorie
		'Authcode',				   # Modulname
		'',                                # Position
		Array ( 'Backend' => 'index,authCodes,createAuthCodes,authCodesSimple,authCodesMail,createAndMailAuthCodes,authCodesRemind,remindAndMailAuthCodes',
				'Export'  => 'downloadPdf, pdf, downloadAuthCodesCsv'),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.$_EXTKEY.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang_mod_authcode.xml'
		)
	);
	
	// Export Backend Modul der Extension
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Kennziffer.'.$_EXTKEY,                  # Extension-Key
		$mainModuleName,		   # Kategorie
		'Export',				   # Modulname
		'',                                # Position
		Array ( 'Export' => 'index,csv,downloadCsv,pdf,downloadPdf,csvInterval,csvCheckInterval,downloadCsvInterval'),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.$_EXTKEY.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang_mod_export.xml',
		)
	);
    
    // Analyse Backend Modul der Extension
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Kennziffer.'.$_EXTKEY,                  # Extension-Key
		$mainModuleName,		   # Kategorie
		'Analyse',				   # Modulname
		'',                                # Position
		Array ( 'Analyse' => 'index,questions,general'),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.$_EXTKEY.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang_mod_analyse.xml',
		)
	);  
	
	// Report zur Prüfung des FileAcces auf den Temp Ordner
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Kennziffer/Questionnaire'][]
		= 'Kennziffer\\KeQuestionnaire\\Reports\\FileAccessReport';
    
}
?>