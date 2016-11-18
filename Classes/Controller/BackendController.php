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
 * Backend Controller
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BackendController extends  \Kennziffer\KeQuestionnaire\Controller\AbstractController {
	/**
	 * The current view, as resolved by resolveView()
	 *
	 * @var ViewInterface
	 * @api
	 */
	var $view = NULL;
        
	/**
	 * @var integer
	 */
	protected $storagePid;
	
	/**
	 * @var array
	 */
	var $plugin;
	
	/**
	 * @var array
	 */
	protected $pluginFF;
	
	/**
	 * @var \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	var $mailSender;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	*/
    protected $flexFormService;

	/**
     * @param \TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService
     * @return void
     */
    public function injectFlexFormService(\TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService) {
        $this->flexFormService = $flexFormService;
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
	 * initialize Action
	 */
	public function initializeAction() {
		parent::initializeAction();
		//the plugin selected in the be
		if ($this->request->hasArgument('pluginUid')) $this->plugin = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', $this->request->getArgument('pluginUid'));
		//create the flexform-data for this questionnaire
		if ($this->plugin['pi_flexform']) $this->pluginFF = $this->flexFormService->convertFlexFormContentToArray($this->plugin['pi_flexform']);
		//merge the settings
		if (is_array($this->pluginFF['settings']) AND is_array($this->settings)) $this->pluginFF['settings'] = array_merge($this->settings,$this->pluginFF['settings']);
		else $this->pluginFF['settings'] = $this->settings;
		$this->plugin['ffdata'] = $this->pluginFF;
		//get the first page given in the plugin data, this is the storage pid
		$pids = explode(',',$this->plugin['pages']);
		$this->storagePid = $pids[0];
	}

	/**
	 * action index
	 */
	public function indexAction() {
		$this->view->assign('questionnaires',$this->questionnaireRepository->findAll());
	}
	
	/**
	 * AuthCode Action
	 * 
	 * @param integer $storage
	 * @param array $plugin
	 * @ignorevalidaton $plugin
	 */
	public function authCodesAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		//get the authCodes for this plugin
		$authCodes = $this->authCodeRepository->findAllForPid($this->storagePid);
		$this->view->assign('authCodes',$authCodes);		
		$this->view->assign('plugin',$this->plugin);
                
                //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->extConf, 'extConf');
	}
	
	/**
	 * AuthCode Simple Action
	 * 
	 * @param integer $storage
	 * @param array $plugin
	 * @ignorevalidaton $plugin
	 */
	public function authCodesSimpleAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		
		$this->view->assign('plugin',$this->plugin);
	}
	
	/**
	 * AuthCode Mail Action
	 * Action to send the mails for the authcodes
	 * 
	 * @param integer $storage
	 * @param array $plugin
	 * @ignorevalidaton $plugin
	 */
	public function authCodesMailAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		
		$this->view->assign('plugin',$this->plugin);
		
		//feUser
		$userRepository = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserRepository');
		$groupRepository = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserGroupRepository');
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		$userRepository->setDefaultQuerySettings($querySettings);
		$groupRepository->setDefaultQuerySettings($querySettings);
		$this->view->assign('feusers',$userRepository->findAll());
		$this->view->assign('fegroups',$groupRepository->findAll());
		
		//tt_address
		$addresses = false;
		//check if extension is installed
		if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_address')) {
			$res = $GLOBALS['TYPO3_DB']->sql_query("SELECT * from tt_address WHERE hidden=0 and deleted=0");
			$addresses = array();
			while ($address = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
				$addresses[] = $address;
			}
		}
		$this->view->assign('ttaddresses', $addresses);
		
		//create the preview with the plugin or standard-texts
		$preview = array();
		$this->view->assign('authCode',array('authCode'=>'AUTHCODE'));
		$preview['subject'] = ($this->plugin['ffdata']['settings']['email']['invite']['subject']?$this->plugin['ffdata']['settings']['email']['invite']['subject']:\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.standard.subject', $this->extensionName));
		$text['before'] = trim(($this->plugin['ffdata']['settings']['email']['invite']['text']['before']?$this->plugin['ffdata']['settings']['email']['invite']['text']['before']:\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.standard.text.before', $this->extensionName)));
		$text['after'] = trim(($this->plugin['ffdata']['settings']['email']['invite']['text']['after']?$this->plugin['ffdata']['settings']['email']['invite']['text']['after']:\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.standard.text.after', $this->extensionName)));
		$this->view->assign('text',$text);
		//render the preview with the "CreatedMail"-Template
		$preview['body'] = trim($this->view->render('CreatedMail'));
		
		$this->view->assign('preview', $preview);		
	}
	
	/**
	 * generate AuthCodes
	 */
	public function createAuthCodesAction() {
		//amount of authCodes to create
		$amount = $this->request->getArgument('amount');
		//length of created AuthCode
		$codeLength = $this->settings['authCodes']['length'];
		//create the codes and store them in the storagepid of the plugin
		for ($i = 0; $i < $amount; $i++){
			$newAuthCode = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Model\\AuthCode');
			$newAuthCode->generateAuthCode($codeLength,$this->storagePid);
			$newAuthCode->setPid($this->storagePid);
			$this->authCodeRepository->add($newAuthCode);
			$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			/* @var $persistenceManager \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager */
			$persistenceManager->persistAll();
		}
		//forward to the standard-Action
		$this->forward('authCodes');
	}
	
	/**
	 * generate and mail AuthCodes
	 */
	public function createAndMailAuthCodesAction() {
                $this->view->assign('plugin',$this->plugin);
		//emails to send the authcodes to
		foreach (explode(',',$this->request->getArgument('emails')) as $mail){
			if ($mail){
				$email['email'] = $mail;
                                $email['sourcetype'] = 'email';
				$emails[] = $email;
			}
		}
		
		if ($this->request->hasArgument('feusers')){
			$add = $this->request->getArgument('feusers');
			if (is_array($add)){
				foreach ($add as $mail){
					$mail = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('fe_user',$mail);
                                        $mail['sourcetype'] = 'feuser';
					$emails[] = $mail;
				}
			}
		}
		if ($this->request->hasArgument('fegroups')){
			$add = $this->getMailsFromFeGroups($this->request->getArgument('fegroups'));
			if (is_array($add)){
				foreach ($add as $mail){
                                        $mail['sourcetype'] = 'feuser';
					$emails[] = $mail;
				}
			}
		}
		if ($this->request->hasArgument('ttaddress')){
			$add = $this->request->getArgument('ttaddress');
			if (is_array($add)){
				foreach ($add as $mail){
					$mail = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_address',$mail);
                                        $mail['sourcetype'] = 'ttaddress';
					$email = $mail;
					$emails[] = $mail;
				}
			}
		}
                //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($emails, 'emails');exit;
                if ($this->settings['authCodes']['length']) $codeLength = $this->settings['authCodes']['length'];
		else $codeLength = 10;
		
		//send the mail for each given email
		foreach ($emails as $mail){
			if ($mail['email'] != ''){
				//create the authcode
				$newAuthCode = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Model\\AuthCode');
				$newAuthCode->generateAuthCode($codeLength,$this->storagePid);
				$newAuthCode->setPid($this->storagePid);
				$newAuthCode->setEmail($mail['email']);
                                switch($mail['sourcetype']) {
                                    case 'feuser': 
                                            $userRepository = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserRepository');
                                            $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
                                            $querySettings->setRespectStoragePage(FALSE);
                                            $userRepository->setDefaultQuerySettings($querySettings);
                                            $newAuthCode->setFeUser($userRepository->findByUid($mail['uid']));
                                        break;
                                    case 'ttaddress':
                                            $addrRepository = $this->objectManager->get('TYPO3\\TtAddress\\Domain\\Repository\\AddressRepository');
                                            $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
                                            $querySettings->setRespectStoragePage(FALSE);
                                            $addrRepository->setDefaultQuerySettings($querySettings);
                                            $newAuthCode->setTtAddress($addrRepository->findByUid($mail['uid']));
                                        break;
                                    default:
                                        break;
                                }
                                //store the authcode
				$this->authCodeRepository->add($newAuthCode);
				//add mail data to view
				$this->view->assign('authCode',$newAuthCode);
				$subject = ($this->plugin['ffdata']['settings']['email']['invite']['subject']?$this->plugin['ffdata']['settings']['email']['invite']['subject']:\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.standard.subject', $this->extensionName));
				$text['before'] = trim(($this->plugin['ffdata']['settings']['email']['invite']['text']['before']?$this->plugin['ffdata']['settings']['email']['invite']['text']['before']:\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.standard.text.before', $this->extensionName)));
				$text['after'] = trim(($this->plugin['ffdata']['settings']['email']['invite']['text']['after']?$this->plugin['ffdata']['settings']['email']['invite']['text']['after']:\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.standard.text.after', $this->extensionName)));
				foreach ($mail as $field => $value){
					$text['before'] = str_replace('###'.strtoupper($field).'###', $value, $text['before']);
					$text['after'] = str_replace('###'.strtoupper($field).'###', $value, $text['after']);
				}
				$this->view->assign('text',$text);
				
				//create mailSender
				$this->mailSender
					->addReceiver($mail['email'])
					->setHtml($this->view->render('createdMail'))
					->setSubject($subject)
					->sendMail();
				//store the authCode
				$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
				// @var $persistenceManager \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager 
				$persistenceManager->persistAll();
			}			
		}
		//forward to standard-action
		$this->forward('authCodes');
	}
	
	/**
	 * Get the Emails from the members of the chosen fe_groups
	 * @param array fe_groups
	 * @return array
	 */
	private function getMailsFromFeGroups($fe_groups){
		$mails = array();
		
		foreach ($fe_groups as $uid){
			$group = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('fe_groups',$uid);
			$userRepository = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserRepository');
			$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
			$querySettings->setRespectStoragePage(FALSE);
			$userRepository->setDefaultQuerySettings($querySettings);
			$user = $userRepository->findAll();
			foreach ($user as $use){				
				foreach ($use->getUsergroup() as $ugroup){
					if ($uid == $ugroup->getUid() AND $use->getEmail()){
						$mails[$use->getUid()] = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('fe_users',$use->getUid());
					}
				}
			}			
		}	
		return $mails;
	}
    
    /**
	 * Count the participations of the chosen questionnaire
	 * @return array
	 */
	public function countParticipations(){
		$counter = array();
		
		$counter['all'] =  $this->resultRepository->countAllForPid($this->storagePid);
		$counter['finished'] = $this->resultRepository->countFinishedForPid($this->storagePid);
        $counter['connected']  = $this->resultRepository->countConnectedForPid($this->storagePid);
		$counter['not'] = $counter['all']-$counter['finished'];
		
		return $counter;
	}
    
	
	/**
	 * Pase the FF data of the chosen Questionnaire
	 * @param array $data
	 * @return array
	 */
	private function parseFFData($data){
		$ff = array();
		
		foreach ($data['data'] as $element => $more){
			$ff[$element] = array();
			foreach ($more['lDEF'] as $key => $vDef){
				$ff[$element][$key] = $vDef['vDEF'];
			}
		}		
		return $ff;
	}
        
        /**
	 * AuthCode Reminder Action
	 * 
	 * @param integer $storage
	 * @param array $plugin
	 * @ignorevalidaton $plugin
	 */
	public function authCodesRemindAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		
		$this->view->assign('plugin',$this->plugin);
                //SignalSlot for Action
                $this->signalSlotDispatcher->dispatch(__CLASS__, 'authCodesRemindAction', array($this,$this->storagePid,$this->extensionName));
	}
        
        /**
	 * remind and mail AuthCodes
	 */
	public function remindAndMailAuthCodesAction() {
            $this->view->assign('plugin',$this->plugin);
		//SignalSlot for Action
                $this->signalSlotDispatcher->dispatch(__CLASS__, 'remindAndMailAuthCodesAction', array($this,$this->request,$this->extensionName));
                
		//forward to standard-action
		$this->forward('authCodes');
	}
}
?>