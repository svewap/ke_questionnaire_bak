<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_kequestionnaire_domain_model_answer'] = array(
	'ctrl' => $TCA['tx_kequestionnaire_domain_model_answer']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, text, points, is_correct_answer',
	),
	'types' => array(
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, text, points, is_correct_answer, show_textfield,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, text, points, is_correct_answer, show_textfield,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, width, pre_text, in_text, post_text, max_chars, validation_type, validation_text, validation_keys_amount, comparison_text,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, width, height, pre_text, in_text, post_text, validation_type, validation_text, validation_keys_amount, comparison_text,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, text, select_values, comparison_text,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, text,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, text, cloze_add_terms,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, cloze_position,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, image, width, height, coords, area_highlight,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, area_highlight,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		//'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, image, width, height, area_highlight,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, image, width, height, area_index,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
                'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
                'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
                'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
                'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, image, width, height, area_index,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, cols, max_answers, min_answers,template,add_clones,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, title_line, show_textfield, template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Slider' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, left_label, right_label, min_value, max_value, slider_increment, width,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SemanticDifferential' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, left_label, right_label, min_value, max_value, slider_increment, show_steps, step_labels, points_start, points_increase, width,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
		'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DataPrivacy' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, type, title, text,template,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
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
				'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
				'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_answer.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_answer.sys_language_uid IN (-1,0)',
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
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'items' => array(
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.Radiobutton', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.Checkbox', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.SingleInput', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.MultiInput', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.SingleSelect', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.ClozeText', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.ClozeTextDD', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.ClozeTerm', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.DDAreaImage', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.DDAreaSequence', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence'),
					//array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.DDAreaSimpleScale', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.DDImage', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.RankingInput', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.RankingOrder', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.RankingSelect', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.RankingTerm', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm'),
                                        array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.MatrixHeader', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.MatrixRow', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.Slider', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Slider'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.SemanticDifferential', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SemanticDifferential'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.type.DataPrivacy', 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DataPrivacy'),
				),
				'itemsProcFunc' => 'Kennziffer\\KeQuestionnaire\\Utility\\TCAAnswerType->checkTypes',
				'size' => 1,
				'maxitems' => 1,
				'eval' => '',
				'default' => 'Kennziffer\\KeQuestionnaire\\Domain\\Model\\AnswerType\\Radiobutton',
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'points' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.points',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'checkbox' => '0',
				'range'	=> Array (
					'upper' => '1000',
					'lower' => '-1000'
				),
				'default' => 0
			)
		),
		'points_start' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.points_start',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'checkbox' => '0',
				'range'	=> Array (
					'upper' => '1000',
					'lower' => '-1000'
				),
				'default' => 0
			)
		),
		'points_increase' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.points_increase',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'checkbox' => '0',
				'range'	=> Array (
					'upper' => '1000',
					'lower' => '-1000'
				),
				'default' => 0
			)
		),
		'text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.text',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'				
			),
			'defaultExtras' => 'richtext[]',
		),
		'is_correct_answer' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.is_correct_answer',
			'config' => array(
				'type' => 'check',
			),
		),
		'question' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'width' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.width',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int'
			)
		),
		'height' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.height',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int'
			)
		),
		'pre_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.pre_text',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'in_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.in_text',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'post_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.post_text',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'max_chars' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.max_chars',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 0
			)
		),
		'validation_type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'items' => array(
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.none', ''),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.numeric', 'numeric'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.integer', 'integer'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.date', 'date'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.email', 'email'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.compareText', 'compareText'),
					array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_type.keywords', 'keywords'),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => '',
				'default' => '',
			),
		),
		'validation_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_text',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
			),
		),
		'validation_keys_amount' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.validation_keys_amount',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 0
			)
		),
		'comparison_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.comparison_text',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
			),
		),
		'cloze_position' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.cloze_position',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 1,
			),
		),
		'cloze_add_terms' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.cloze_add_terms',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
			),
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_kequestionnaire',
				'show_thumbs' => 1,
				'size' => 1,
				'maxitems' => 1,
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'disallowed' => '',
			),
		),
		'coords' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.coords',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'type' => 'popup',
						'title' => 'Create Image Area Coordinates',
						'module' => array(
							'name' => 'wizard_imageAreaSelect',						
						),
						'icon' => 'EXT:ke_questionnaire/Resources/Public/Icons/imageAreaSelectWizard.png',
						'JSopenParams' => 'height=800,width=900,status=0,menubar=0,scrollbars=1',
					),
				),                                
			),
		),
		'area_index' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.area_index',
			'config' => array(
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int'
			),
		),
		'area_highlight' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.area_highlight',
			'config' => array(
				'type' => 'check',
				'default' => true
			),
		),
		'answer' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'cols' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.cols',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
				'foreign_field' => 'answer',
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
		'show_textfield' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.show_textfield',
			'config' => array(
				'type' => 'check',
			),
		),
		'max_answers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.max_answers',
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
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.min_answers',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 0
			)
		),
		'select_values' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.select_values',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
			),
		),
		'left_label' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.left_label',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'right_label' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.right_label',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'min_value' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.min_value',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 0
			)
		),
		'max_value' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.max_value',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '4',
				'max'	  => '4',
				'eval'	 => 'int',
				'default' => 10
			)
		),
		'slider_increment' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.slider_increment',
			'config' => Array (
				'type'	 => 'input',
				'size'	 => '10',
				'max'	  => '10',
				'eval'	 => 'float',
				'default' => '1.0000'
			)
		),
		'show_steps' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.show_steps',
			'config' => array(
				'type' => 'check',
			),
		),
		'step_labels' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.step_labels',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
			),
		),
        'source_dir' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.source_dir',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',                
			),
		),
        'destination_dir' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.destination_dir',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',                
			),
		),
        'avatar_parts' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.avatar_parts',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim',
			),
		),
        'feuser_field' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.feuser_field',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
        'template' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.template',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'size' => 1,
				'maxitems' => 1
			),
		),
        'add_clones' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.add_clones',
			'config' => array(
				'type' => 'check',
			),
		),
		'title_line' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_answer.title_line',
			'config' => array(
				'type' => 'check',
			),
		),
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
?>