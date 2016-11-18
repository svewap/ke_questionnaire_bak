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
class ResultController extends \Kennziffer\KeQuestionnaire\Controller\AbstractController {
	
	/**
	 * The current view, as resolved by resolveView()
	 *
	 * @var ViewInterface
	 * @api
	 */
	var $view = NULL;
	
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
	 * initializes the controller
	 */
	public function initialize() {
		parent::initialize();
	}
	
	/**
	 * initializes the actions
	 */
	public function initializeAction() {
		parent::initializeAction();
		$this->questionnaire->settings = $this->settings;

		//check a logged in user
		$userRepository = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserRepository');
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		$userRepository->setDefaultQuerySettings($querySettings);

		if ($GLOBALS['TSFE']->fe_user->user['uid']) $this->user = $userRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);				
		
		//check jsKey for the created js-file
		$jsKey = substr($GLOBALS['TSFE']->fe_user->id,0,10).'_keqjs';
		$GLOBALS['TSFE']->fe_user->setKey('ses','keq_jskey',$jsKey);	
		//maybe better to erase the js file every time
		$pathname = 'typo3temp/ke_questionnaire';
		$filename = $pathname.'/'.$jsKey.'.js';
		if (file_exists(PATH_site.$filename)) unlink($filename); 
	}

	/**
	 * Displays a form for saving a new questionnaire
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $newResult A fresh new result object
	 * @param integer $requestedPage after checking the questions of currentPage redirect to this page
	 * @return void
	 * @ignorevalidaton $newResult
	 */
	public function newAction(\Kennziffer\KeQuestionnaire\Domain\Model\Result $newResult = NULL, $requestedPage = 0) {
		if ($newResult == NULL) { // workaround for fluid bug ##5636
			$newResult = $this->objectManager->get('Kennziffer\KeQuestionnaire\Domain\Model\Result');
		}
		if ($this->user) $newResult->setFeUser($this->user);
		//check for the Access-Type
		switch ($this->settings['accessType']){
                    case 'feUser':
                                    $this->checkFeUser();
                            break;
                    case 'authCode':
                                    $this->checkAuthCode();
                                    $newResult->setAuthCode($this->authCode);
                            break;
		}
		//check questionnaire-dependancy
		$this->checkDependancy();
		//check for restart
		$newResult = $this->checkRestart($newResult);
		//check if the max participations are reached
		$this->checkMaxParticipations();

		//get the correct requested start-page
		if ($requestedPage == 0 && !$this->settings['description']) $requestedPage = 1;

		//set the requested page
		$this->questionnaire->setPage($requestedPage);
		//get the questions and add them to the questionnaire
		$questions = $this->questionRepository->findAll();
		$this->questionnaire->setQuestions($questions);
		
		//SignalSlot for newAction
                $this->signalResult = $newResult;
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'newAction', array($this));
                $newResult = $this->signalResult;
		
		$this->view->assign('questions', $this->questionnaire->getQuestionsForPage($requestedPage));
		$this->view->assign('questionnaire', $this->questionnaire);
		$this->view->assign('newResult', $newResult);		
	}
	
	

	/**
	 * set StoragePid
	 *
	 * @param $storagePid
	 * @return void
	 */
	public function setStoragePid($storagePid) {
		$configurationManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		//fallback to current pid if no storagePid is defined
		if (version_compare(TYPO3_version, '6.0.0', '>=')) {
			$configuration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		} else {
			$configuration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		}
		$currentPid['persistence']['storagePid'] = $storagePid;
		$configurationManager->setConfiguration(array_merge($configuration, $currentPid));
	}


	/**
	 * initializes the createAction
	 */
	public function initializeCreateAction() {
		//to get correct updates it is needed that a clone of the actual result is created
		//the temp_result stores the current newResult-data
		$this->temp_result = $this->request->getArgument('newResult');
		//SignalSlot for this action
                $this->signalSlotDispatcher->dispatch(__CLASS__, 'initializeCreateAction', array($this, $this->arguments));
		//check for autoSave
		//Premium function needs to be checked here anyway. The autosave Action is part of the premium
                if ($this->settings['ajaxAutoSave'] == 1 AND $GLOBALS['TSFE']->fe_user->user['uid'] > 0){			
			if ($this->temp_result['__identity'] > 0){
				$result = $this->resultRepository->findByUid($this->temp_result['__identity']);	
				$this->request->setArgument('newResult',$result);
			}
		}
		//if the result is not new create the clone of the stored before it is updated
		//if the old-result is not stored, given answers of other pages will be deleted
		if ($this->temp_result['__identity'] > 0) {
			$result = $this->resultRepository->findByUid($this->temp_result['__identity']);	
			if ($result) $this->oldResult = clone $result;
		}
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->settings, 'settings');		
	}

	/**
	 * Creates a new questionnaire
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $newResult A fresh Questionnaire object
         * @ignorevalidation $newResult
	 * @param integer $currentPage check, validate and save the results of this page
	 * @param integer $requestedPage after checking the questions of currentPage redirect to this page
	 * @return void
         */
	public function createAction(\Kennziffer\KeQuestionnaire\Domain\Model\Result $newResult, $currentPage, $requestedPage) {
                //requestedPage => next Page to be shown
		$this->questionnaire->setRequestedPage($requestedPage);
		//currentPage => current Page, just send to this action
		$this->questionnaire->setPage($currentPage);
		//get all questions for this questionnaire
		$this->questionnaire->setQuestions($this->questionRepository->findAll());
		if ($this->user) $newResult->setFeUser($this->user);
		//check for the Access-Type
		switch ($this->settings['accessType']){
			case 'feUser':
					$this->checkFeUser();
				break;
			case 'authCode':
					$this->checkAuthCode();
					$newResult->setAuthCode($this->authCode);
				break;
		}		
		//validate the result
		$this->validateResult($newResult, $requestedPage);
		//rework the result so all given answers to all questions (not only current page) are stored
		$result = $this->getSavedAndMergedResult($newResult);
		//calculate the points of the questions and the result
		$result->calculatePoints();
		$this->resultRepository->update($result);
		
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
		
		$this->resultRepository->clearRATable();
		
		// check for last page
		if($currentPage == $requestedPage) {
			//if there is a redirect page, redirect
			//else forward to endAction
			if ($this->settings['redirectFinished']){
				$this->uriBuilder->setTargetPageUid($this->settings['redirectFinished']);   
				$link = $this->uriBuilder->build();
				$this->redirectToUri($link);
			} else {
				/* Problems with forward and RealUrl
				 $this->forward('end', NULL, NULL, array(
					'result' => $newResult
				));*/
				$this->view->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
					'typo3conf/ext/'.
					$this->request->getControllerExtensionKey().
					'/Resources/Private/Templates/Result/End.html'
				  ));
				$this->view->assign('result', $newResult);
				$this->view->assign('questionnaire', $this->questionnaire);
				$temp = false;
				$this->signalSlotDispatcher->dispatch(__CLASS__, 'endAction', array($newResult, $this, &$temp));
				if ($temp) $this->redirectToUri ($temp);
			}
		//if not last page, set all stuff for questionnaire-page
		} else {            
			//set the next page
			$this->questionnaire->setPage($requestedPage);
			//get questions
			$questions = $this->questionRepository->findAll();
			$this->questionnaire->setQuestions($questions);
			//signalSlot for this action
			$this->signalSlotDispatcher->dispatch(__CLASS__, 'createAction', array($this));

			$this->view->assign('questions', $this->questionnaire->getQuestionsForPage($requestedPage));
			$this->view->assign('questionnaire', $this->questionnaire);
			$this->view->assign('newResult', $newResult);
		}
	}

	/**
	 * Shows an chart => needs to be checked if really needed
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @return void
	 */
	public function showAction(\Kennziffer\KeQuestionnaire\Domain\Model\Result $result = NULL) {
		if (!$result) {
			$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('feView.noResultError', $this->extensionName), \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('feView.noResultErrorTitle', $this->extensionName), \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
		} else {
			$questionnaire = $this->questionnaireRepository->findByStoragePid($result->getPid());
			$this->view->assign('questionnaire', $questionnaire[0]);
			$this->view->assign('result', $result);
		}
	}

	/**
	 * validate the result object
	 * check for mandatory and correct answers
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @param integer $requestedPage after checking the questions redirect to this page
	 * @return void
	 */
	public function validateResult(\Kennziffer\KeQuestionnaire\Domain\Model\Result &$result, $requestedPage = 1) {
		/* @var $resultQuestion \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion */
		//check for every question in result
		foreach ($result->getQuestions() as $resultQuestion) {
				if ($resultQuestion->getQuestion()){
						// check if the question has to be answered correctly
						if ($resultQuestion->getQuestion()->getMustBeCorrect()) {
								if (!$resultQuestion->isAnsweredCorrectly()) {
										$this->moveToAction('new', $result, $requestedPage, 'mustBeCorrect');
								}
						}	

						// check if question is mandatory
						if ($resultQuestion->getQuestion()->getIsMandatory() AND !$resultQuestion->getQuestion()->IsDependant()) {
								if (!$resultQuestion->isAnswered()) {
										$this->moveToAction('new', $result, $requestedPage, 'mandatory');
								}
						}                           					
				}
		}
		$result->setFeCruserId($GLOBALS['TSFE']->fe_user->user['uid']);
		if ($this->user) $result->setFeUser($this->user);            
	}
    
	/**
	 * move to given action
	 *
	 * @param string $action Action to call
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result A fresh Questionnaire object
	 * @param integer $page needed for page navigation
	 * @param string $flashMessage Key to show a flashMessage if needed
	 * @return void
	 */
	public function moveToAction($action = 'new', \Kennziffer\KeQuestionnaire\Domain\Model\Result $result, $page = 1, $flashMessage = '') {
		if(!empty($flashMessage)) $this->addNewFlashMessage($flashMessage);
		$this->forward($action, NULL, NULL, array(
			'newResult' => $result,
			'requestedPage' => $page
		));
	}

	/**
	 * get the created or merged result object
	 * we have to merge them because $newResult contains the answered questions of the last page only.
	 * All other answered questions come from DB
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $newResult
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\Result The modified result object
	 */
	public function getSavedAndMergedResult(\Kennziffer\KeQuestionnaire\Domain\Model\Result $newResult) {
		$uid = $newResult->getUid();
		if ($uid === NULL) {
			$result = $this->addResult($newResult);
		} else {
			$result = $this->updateResult($newResult);			
		}
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'getSavedAndMergedResult', array($result, $this));
		return $result;
	}

	/**
	 * add the result to the DB
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $formResult This is the result from the form. NOT DB!
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\Result The added result object
	 */
	public function addResult(\Kennziffer\KeQuestionnaire\Domain\Model\Result $formResult) {
		$formResult = $this->modifyResultBeforeSave($formResult);
		$this->resultRepository->add($formResult);
		
		foreach ($formResult->getQuestions() as $rquestion){
			$formResult->checkMatrixType($rquestion);
		}
       
		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
		
		return $formResult;
	}

	/**
	 * if uid is set, we have to merge the given $newResult object with the existing record in DB
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $formResult This is the result from the form. NOT DB!
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\Result The updated result object
	 */
	public function updateResult(\Kennziffer\KeQuestionnaire\Domain\Model\Result $formResult) {
		//merge the old and the new result-data
		$dbResult = $this->oldResult;
		if ($dbResult) $updatedResult = $this->addQuestionToDbResult($formResult, $dbResult);
		else $updatedResult = $formResult;
		$updatedResult->setPoints($updatedResult->getPoints() + $formResult->getPoints());
		$updatedResult->setMaxPoints($updatedResult->getMaxPoints() + $formResult->getMaxPoints());
		//check the result
		$result = $this->modifyResultBeforeSave($updatedResult);
		$this->resultRepository->update($result);
		
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
				
		return $result;
	}

	/**
	 * here you/we make some little modifications to the result object before saving
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\Result
	 */
	public function modifyResultBeforeSave(\Kennziffer\KeQuestionnaire\Domain\Model\Result $result) {
		// set timestamp for finished if last page was reached
		if ($this->questionnaire AND $this->questionnaire->getIsFinished()) {
			$result->setFinished(time());
		}
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'modifyResultBeforeSave', array($result, $this));

		return $result;
	}

	/**
	 * add the questions of the form result to the db result object
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $formResult The result object which comes from the form
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $dbResult The result object which comes from DB
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\Result The updated db result
	 */
	public function addQuestionToDbResult(\Kennziffer\KeQuestionnaire\Domain\Model\Result $formResult, \Kennziffer\KeQuestionnaire\Domain\Model\Result $dbResult) {
		$formQuestions = $formResult->getQuestions();
		
		foreach ($formQuestions as $fQuestion){
			if ($fQuestion->getQuestion() AND $fQuestion->getQuestion()->getType() == 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question'){
				$dbResult->addOrUpdateQuestion($fQuestion);				
			}
		}
		$formResult->setQuestions($dbResult->getQuestions());		
		return $formResult;
	}
	
	/**
	 * checks the logged in user
	 *
	 * @return void
	 */
	public function checkFeUser() {
		if (!$this->user){
			$this->forward('feUserAccess');
		}
	}
    
    /**
	 * Action to show the FeUser-Access Error
	 *
	 * @return void
	 */
	public function feUserAccessAction() {
		
	}
    
    /**
     * checkes the dependancy of the Questionnaire
     */
    public function checkDependancy() {
        $this->signalSlotDispatcher->dispatch(__CLASS__, 'checkDependancy', array($this));
    }
	
	/**
	 * Action to show the FeUser-Access Error
	 *
     * @param \Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire $questionnaire
	 * @return void
	 */
	public function dependancyAccessAction(\Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire $questionnaire) {
		$this->view->assign('questionnaire',$questionnaire);
	}
	
	/**
	 * checks the logged in autchode
	 *
	 * @return boolean
	 */
	public function checkAuthCode() {
		//direct call with &authCode= in URI
		if ($_REQUEST['authCode'])$this->request->setArgument('code',$_REQUEST['authCode']);
		if ($this->request->hasArgument('authCode')){			
			$code = $this->authCodeRepository->findByUid($this->request->getArgument('authCode'));
			if ($code) {
				$this->authCode = $code;
                                if (!$this->authCode->getFirstactive()) {
                                    $this->authCode->setFirstactive(time());                                    
                                    $this->authCodeRepository->update($this->authCode);
                                    
                                    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
                                    $persistenceManager->persistAll();
                                }
				return true;
			}
		} elseif ($this->request->hasArgument('code')){
			$codes = $this->authCodeRepository->findByAuthCode($this->request->getArgument('code'));
                        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($codes[0], 'code');	exit;
			if ($codes[0]) {
				$this->authCode = $codes[0];
                                if (!$this->authCode->getFirstactive()) {
                                    $this->authCode->setFirstactive(time());                                    
                                    $this->authCodeRepository->update($this->authCode);
                                    
                                    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
                                    $persistenceManager->persistAll();
                                }
				return true;
			}
		}
		$this->forward('authCodeAccess');
	}
	
	/**
	 * Action to show the AuthCode-Access Error
	 *
	 * @return void
	 */
	public function authCodeAccessAction() {
		
	}
	
	/**
	 * checks the max participations allowed
	 *
	 * @return void
	 */
	public function checkMaxParticipations() {
		if ($this->settings['participations']['max'] > 0){
			if ($this->settings['accessType'] == 'free'){				
				$counted = $this->resultRepository->findAll()->count();
			} elseif ($this->settings['accessType'] == 'feUser') {
				$counted = $this->resultRepository->findByFeUser($this->user)->count();
			} elseif ($this->settings['accessType'] == 'authCode') {
				$counted = $this->resultRepository->findByAuthCode($this->authCode->getUid())->count();
			}
			if ($counted >= $this->settings['participations']['max']) $this->forward('maxParticipations');
		}
	}
	
	/**
	 * Action to show the maxParticipations Error
	 *
	 * @return void
	 */
	public function maxParticipationsAction() {
		
	}
	
	/**
	 * Check if the user can restart a started participation
	 * 
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 */
	private function checkRestart(\Kennziffer\KeQuestionnaire\Domain\Model\Result $result){
            if ($this->settings['accessType'] != 'free' AND $this->settings['restart']){
                //fetch the last participation of the user
                if ($result->getFeUser()){
                    $parts = $this->resultRepository->findByFeUser($result->getFeUser())->toArray();
                    if (count($parts) > 0){
                        $last = $parts[count($parts)-1];
                        if (!$last->getFinished()) $result = $last;
                    }
                    //or the authCode
		} elseif ($result->getAuthCode()){
                    $parts = $this->resultRepository->findByAuthCode($result->getAuthCode())->toArray();
                    if (count($parts) > 0){
			$last = $parts[count($parts)-1];
			if (!$last->getFinished()) $result = $last;
                    }
		}
            }
            return $result;
	}
	
	/**
	 * Action to show the End Page of the questionnaire
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @dontvalidate $result
	 * @return void
	 */
	public function endAction(\Kennziffer\KeQuestionnaire\Domain\Model\Result $result = NULL) {
		if (!$result) $result = $this->resultRepository->findByUid($this->request->getArgument('result'));
		$questionnaire = $this->questionnaireRepository->findByStoragePid($result->getPid());
		
		$this->view->assign('result', $result);
		$this->view->assign('questionnaire', $questionnaire);
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'endAction', array($result, $this));
	}

}
?>