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
class RenderPartialViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper {
	
	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;
	
	/**
	 * @var array
	 */
	var $oldPaths = array();

	/**
	 * Injects the object manager
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Renders the content.
	 *
         * @param string $partial The partial to render
	 * @param array $arguments Arguments to pass to the partial
	 * @return string
	 */
	public function render($partial = NULL, array $arguments = array()) {
        if (file_exists($partial)){
            // Overload arguments with own extension local settings (to pass own settings to external partial)
            $arguments = $this->loadSettingsIntoArguments($arguments);
			
            $path_parts = pathinfo($partial);
            $path = realpath($path_parts['dirname']);
            $partial = $path_parts['filename'];
            
            $this->setPartialRootPath($path);
            $content = $this->viewHelperVariableContainer->getView()->renderPartial($partial, NULL, $arguments);
            $this->resetPartialRootPath();            
        } else {
            $content = 'Fehlschlag';
        }
        return $content;
	}

	/**
	 * Set partial root path by controller context
	 *
	 * @param string $path
	 * @return void
	 */
	protected function setPartialRootPath($path) {
		$this->oldPaths = $this->viewHelperVariableContainer->getView()->getKeqPartialRootPaths();
		$this->viewHelperVariableContainer->getView()->setPartialRootPath(
			$path
		);
	}
    
        /**
	 * Set partial root path by controller context
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 */
	protected function setPartialRootPathFromCC(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext) {
		$this->viewHelperVariableContainer->getView()->setPartialRootPath(
			$this->getPartialRootPath($controllerContext)
		);
	}

	/**
	 * Resets the partial root path to original controller context
	 *
	 * @return void
	 */
	protected function resetPartialRootPath() {
		$this->setPartialRootPathFromCC($this->controllerContext);
	}
    
    /**
	 * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext
	 * @return mixed
	 */
	protected function getPartialRootPath(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext) {
		if (count($this->oldPaths) > 0){
			$partialRootPath = $this->oldPaths[0];
		} else {
			$partialRootPath = str_replace(
				'@packageResourcesPath',
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($controllerContext->getRequest()->getControllerExtensionKey()) . 'Resources/',
				'@packageResourcesPath/Private/Partials'
			);
		}
		return $partialRootPath;
	}
}
?>