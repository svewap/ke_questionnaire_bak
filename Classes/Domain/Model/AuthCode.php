<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;
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
class AuthCode extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * AuthCode
	 *
	 * @var string
	 */
	protected $authCode;
	
	/**
	 * EMail
	 *
	 * @var string
	 */
	protected $email;
	
	/**
	 * Participations
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Kennziffer\KeQuestionnaire\Domain\Model\Result>
	 * @lazy
	 */
	protected $participations;

	/**
	 * Returns the authCode
	 *
	 * @return string $authCode
	 */
	public function getAuthCode() {
		return $this->authCode;
	}

	/**
	 * Sets the authCode
	 *
	 * @param string $authCode
	 * @return void
	 */
	public function setAuthCode($authCode) {
		$this->authCode = $authCode;
	}
	
	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
	
	/**
	 * generate a single and unique AuthCode
	 * 
	 * @param integer $length
	 * @param integer §pid
	 */
	public function generateAuthCode($length = 10, $pid){
		//get the existent authcodes so no duplicates are created
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$ac_rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AuthCodeRepository');
		// Generate authcode
		$loop = 1;
		while($loop){
			$key = '';
			list($usec, $sec) = explode(' ', microtime());
			mt_srand((float) $sec + ((float) $usec * 100000));

			$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

			for($i=0; $i<$length; $i++)
			{
				$key .= $inputs{mt_rand(0,61)};
			}

			$existent = $ac_rep->findByAuthCodeForPid($key,$pid);
			if ($existent) $loop = 0;
		}
		$this->setAuthCode($key);
		return $key;
	}
	
	/**
	 * Returns the results
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $results
	 */
	public function getParticipations() {
		return $this->participations;
	}
        
    /**
	 * Loads the results of this authCode for the be-module
	 */
	public function getAndLoadParticipations() {
            if (count($this->participations) == 0){
                    $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
                    $rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultRepository');
                    $this->participations = $rep->findForAuthCode($this);
            }
            return $this->participations;
	}
}
?>