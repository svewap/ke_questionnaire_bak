<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
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

/**
 * 
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CoaViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Disable the escaping interceptor because otherwise the child nodes would be escaped before this view helper
	 * can decode the text's entities.
	 *
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * @var array
	 */
	protected $typoScriptSetup;

	/**
	 * @var \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser
	 */
	protected $typoScriptParser;

	/**
	 * @var	t3lib_fe contains a backup of the current $GLOBALS['TSFE'] if used in BE mode
	 */
	protected $tsfeBackup;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;





	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->typoScriptSetup = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
	}

	/**
	 * @param \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser $typoScriptParser
	 * @return void
	 */
	public function injectTypoScriptParser(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser $typoScriptParser) {
		$this->typoScriptParser = $typoScriptParser;
	}





	/**
	 * Renders the TypoScript
	 *
	 * @param string $typoScript the TypoScript to render
	 * @param mixed $data the data to be used for rendering the cObject. Can be an object, array or string. If this argument is not set, child nodes will be used
	 * @param string $currentValueKey
	 * @return string the content of the rendered TypoScript object
	 */
	public function render($typoScript, $data = NULL, $currentValueKey = NULL) {
		if (TYPO3_MODE === 'BE') {
			$this->simulateFrontendEnvironment();
		}

		if ($data === NULL) {
			$data = $this->renderChildren();
		}
		$currentValue = NULL;
		if (is_object($data)) {
			$data = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettableProperties($data);
		} elseif (is_string($data) || is_numeric($data)) {
			$currentValue = (string) $data;
			$data = array($data);
		}

		$contentObject = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
		/* @var $contentObject tslib_cObj */
		$contentObject->start($data);

		if ($currentValue !== NULL) {
			$contentObject->setCurrentVal($currentValue);
		} elseif ($currentValueKey !== NULL && isset($data[$currentValueKey])) {
			$contentObject->setCurrentVal($data[$currentValueKey]);
		}

		$this->typoScriptParser->parse($typoScript);
		$typoScriptConf = $this->typoScriptParser->setup;

		$content = $contentObject->COBJ_ARRAY($typoScriptConf);

		if (TYPO3_MODE === 'BE') {
			$this->resetFrontendEnvironment();
		}

		return $content;
	}

	/**
	 * Sets the $TSFE->cObjectDepthCounter in Backend mode
	 * This somewhat hacky work around is currently needed because the cObjGetSingle() function of tslib_cObj relies on this setting
	 *
	 * @return void
	 */
	protected function simulateFrontendEnvironment() {
		$this->tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : NULL;
		$GLOBALS['TSFE'] = new stdClass();
		$GLOBALS['TSFE']->cObjectDepthCounter = 100;
	}

	/**
	 * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment()
	 *
	 * @return void
	 * @see simulateFrontendEnvironment()
	 */
	protected function resetFrontendEnvironment() {
		$GLOBALS['TSFE'] = $this->tsfeBackup;
	}
}
?>