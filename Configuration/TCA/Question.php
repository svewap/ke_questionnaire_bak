<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_kequestionnaire_domain_model_question'] = array(
	'ctrl' => $TCA['tx_kequestionnaire_domain_model_question']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, show_title, text, help_text, image, image_position, is_mandatory, must_be_correct, answers, dependancies, to_page, direct_jump, javascript, only_js',
	),
	'types' => array(
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title, show_title, text;;4;richtext[], image;;3, is_mandatory;;2,template,--div--;LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.answers,answers,random_answers,column_count,max_answers,min_answers,--div--;LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.dependancies,dependancies,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Html' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1, text;;;wizards[t3editorHtml]'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Text' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1, text;;;richtext[],--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Typo3Content' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1, content_id'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScript' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1, text;;;wizards[t3editorTypoScript]'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScriptPath' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1, text'),
		'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title;;1, text;;;richtext[],--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title, dependancies, to_page, direct_jump, javascript, only_js,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PlausiCheck' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, type, title, text;;4;richtext[], dependancies,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_kequestionnaire_domain_model_question',
				'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_question.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_question.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'items' => array(
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.Question', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.PageBreak', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.ConditionalJump', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.PlausiCheck', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PlausiCheck'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.Group', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.Html', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Html'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.Text', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Text'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.Typo3Content', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Typo3Content'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.TypoScript', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScript'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.TypoScriptPath', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScriptPath'),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => '',
				'default' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question',
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
				'wizards' => array(
					'title_picker' => array(
						'type' => 'select',
						'mode' => '',
						'items' => array(
							array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.type.I.2', 'Pagebreak'),
						),
					),
				),
			),
		),
		'show_title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.show_title',
			'config' => array(
				'type' => 'check',
			),
		),
		'text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.text',
			'config' => array(
				'type' => 'text',
				'cols' => 80,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					't3editorHtml' => array(
						'enableByTypeConfig' => 1,
						'type' => 'userFunc',
						'userFunc' => 'TYPO3\\CMS\\T3Editor\\FormWizard->main',
						'params' => array(
							'format' => 'html',
						),
					),
					't3editorTypoScript' => array(
						'enableByTypeConfig' => 1,
						'type' => 'userFunc',
						'userFunc' => 'TYPO3\\CMS\\T3Editor\\FormWizard->main',
						'params' => array(
							'format' => 'ts',
						),
					),
				),
			),
		),
		'help_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.help_text',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_kequestionnaire',
				'show_thumbs' => 1,
				'size' => 5,
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'disallowed' => '',
			),
		),
		'image_position' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.image_position',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.image_position.I.1', 'top'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.image_position.I.2', 'right'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.image_position.I.3', 'left'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.image_position.I.4', 'bottom')
				),
				'size' => 1,
				'maxitems' => 1,
				'default' => 'top',
                                'renderType' => 'selectSingle',
			),
		),
		'is_mandatory' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.is_mandatory',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'must_be_correct' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.must_be_correct',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'answers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.answers',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
				'foreign_field' => 'question',
				'foreign_sortby' => 'sorting',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapseAll' => TRUE,
					'expandSingle' => TRUE,
					'levelLinksPosition' => 'both',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'useSortable' => 1
				),
			),
		),
		'random_answers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.random_answers',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'column_count' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.column_count',
			'config' => array(
				'type'	 => 'input',
				'size'	 => '4',
				'max'	 => '4',
				'eval'	 => 'int',
				'default'=> 1
			),
		),
		'max_answers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.max_answers',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 0
			)
		),
		'min_answers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.min_answers',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 0
			)
		),
		'content_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.content_id',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tt_content',
				'size' => 1,
				'maxitems' => 1
			),
		),
        'dependancies' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.dependancies',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_kequestionnaire_domain_model_dependancy',
				'foreign_field' => 'dquestion',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapseAll' => TRUE,
					'expandSingle' => TRUE,
					'levelLinksPosition' => 'both',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'useSortable' => 1
				),
			),
		),
        'to_page' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.to_page',
			'config' => array(
				'type'	 => 'input',
				'size'	 => '4',
				'max'	 => '4',
				'eval'	 => 'int'
			),
		),
        'direct_jump' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.direct_jump',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
        'javascript' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.javascript',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'size' => 1,
				'maxitems' => 1
			),
		),
        'only_js' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.only_js',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
        'css' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.css',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
        'template' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_question.template',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'size' => 1,
				'maxitems' => 1
			),
		),
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
?>