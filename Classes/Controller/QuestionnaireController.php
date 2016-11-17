<?php
namespace Kennziffer\KeQuestionnaire\Controller;
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
class QuestionnaireController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    /**
	 * The current view, as resolved by resolveView()
	 *
	 * @var ViewInterface
	 * @api
	 */
	var $view = NULL;
        
    /**
	 * questionnaireRepository
	 *
	 * @var \Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository
	 */
	protected $questionnaireRepository;
	
	/**
	 * authCodeRepository
	 *
	 * @var \Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository
	 */
	protected $authCodeRepository;
	
	/**
	 * resultRepository
	 *
	 * @var \Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository
	 */
	protected $resultRepository;
	
	/**
	 * @var \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	protected $mailSender;
    
    /**
	 * injectQuestionnaireRepository
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository $questionnaireRepository
	 * @return void
	 */
	public function injectQuestionnaireRepository(\Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository $questionnaireRepository) {
		$this->questionnaireRepository = $questionnaireRepository;
	}
	
	/**
	 * injectAuthCodeRepository
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository $authCodeRepository
	 * @return void
	 */
	public function injectAuthCodeRepository(\Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository $authCodeRepository) {
		$this->authCodeRepository = $authCodeRepository;
	}
	
	/**
	 * injectResultRepository
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository $resultRepository
	 * @return void
	 */
	public function injectResultRepository(\Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository $resultRepository) {
		$this->resultRepository = $resultRepository;
	}
	
	/**
	 * inject mailSender
	 *
	 * @param \Kennziffer\KeQuestionnaire\Utility\Mail $mail
	 */
	public function injectMail(\Kennziffer\KeQuestionnaire\Utility\Mail $mail) {
		$this->mailSender = $mail;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {		
            $this->view->assign('questionnaires',$this->getQuestionnaires());
            //SignalSlot for listAction
            $this->signalSlotDispatcher->dispatch(__CLASS__, 'listAction', array($this));            
	}
	
	/**
	 * action reclaimAuthcode
	 *
	 * @return void
	 */
	public function reclaimAuthCodeAction() {		

	}
	
	/**
	 * Checks if the value is valid email
	 *
	 * @param string $value value
	 * @return boolean
	 */
	public function isValidEmail($value){
		$class = 'Kennziffer\\KeQuestionnaire\\Validation\\Email';
		if (class_exists($class)) {
			$objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager;
			$validator = $objectManager->get($class);
			if ($validator instanceof \Kennziffer\KeQuestionnaire\ValidationAbstractValidation) {
				/* @var $validator \Kennziffer\KeQuestionnaire\ValidationAbstractValidation */
				return $validator->isValid($value, $this);
			} else return false;
		} else return false;
	}
    
    
    /**
     * get the selected Questionnaires
     * 
     * @return array
     */
    private function getQuestionnaires(){
        if ($this->settings['questionnaires']) $questionnaires = $this->questionnaireRepository->findForUids($this->settings['questionnaires']);
        else $questionnaires = $this->questionnaireRepository->findAll();
		
		$list = array();
		if ($GLOBALS['TSFE']->fe_user) $user_id = $GLOBALS['TSFE']->fe_user->user['uid'];
		switch ($this->settings['listType']){
			case 'all':
					$list = $questionnaires;
				break;
			case 'used':
					if ($user_id){
						foreach ($questionnaires as $questionnaire){
							if (count($questionnaire->getUserResults($user_id)) > 0){
								$list[] = $questionnaire;
							}
						}
					}
				break;
			case 'unused':
					if ($user_id){
						foreach ($questionnaires as $questionnaire){
							if (count($questionnaire->getUserResults($user_id)) == 0){
								$list[] = $questionnaire;
							}
						}
					}
				break;
		}
		
        return $list;
    }
    
    /**
	 * Create getSettings
	 *
	 * @return array
	 */
	public function getSettings() {
		return $this->settings;
	}

}
?>