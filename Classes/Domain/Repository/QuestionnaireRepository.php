<?php
namespace Kennziffer\KeQuestionnaire\Domain\Repository;
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
class QuestionnaireRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
       
       /**
        * find all ke_questionnaires
        * 
        * @return Query Result
        */
       public function findAll() {
           $query = $this->createQuery();
           $query->getQuerySettings()->setRespectStoragePage(FALSE);
           
           $constraint = $query->logicalAnd(
                        $query->equals('ctype','list'),
                        $query->equals('list_type','kequestionnaire_questionnaire')
                   );
           $query->matching($constraint);
           
           return $query->execute();
       }
	
	   /**
        * find ke_questionnaires for the storagePid
        * 
	    * @param integer $storagePid
        * @return Query Result
        */
       public function findByStoragePid($storagePid) {
           $query = $this->createQuery();
           $query->getQuerySettings()->setRespectStoragePage(FALSE);
           
           $constraint = $query->logicalOr(
				   $query->equals('pages',$storagePid),
				   $query->logicalOr(
						   $query->like('pages', $storagePid.',%'),
						   $query->logicalOr(
								   $query->like('pages', '%,'.$storagePid),
								   $query->like('pages', '%,'.$storagePid.',%')
								   )
						   )
				   );
				   
           $query->matching($constraint);
           
           return $query->execute();
       }
       
       /**
        * find ke_questionnaires for uids
        * 
        * @params array $uids
        * @return questionnaires
        */
       public function findForUids($uids) {
			$uids = explode(',',$uids);
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(FALSE);
			$query->matching($query->in('uid', $uids));
			return $query->execute();	   
       }
       
       /**
        * find ke_questionnaires for uid
        * 
        * @params integer $uid
        * @return questionnaire
        */
       public function findForUid($uid) {
           $query = $this->createQuery();
           $query->getQuerySettings()->setRespectStoragePage(FALSE);

           $constraint = $query->equals('uid',$uid);

           $query->matching($constraint);
		   $questionnaires = $query->execute();
		   $questionnaire = $questionnaires[0];
           
           return $questionnaire;
       }
       
       
}
?>