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
class Result extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * FeCruserId
	 *
	 * @var integer
	 */
	protected $feCruserId;
	
	/**
	 * Crdate
	 *
	 * @var integer
	 */
	protected $crdate;

	/**
	 * Finished
	 *
	 * @var integer
	 */
	protected $finished;

	/**
	 * Questions
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion>
	 * @cascade remove
     * @lazy
	 */
	protected $questions;

	/**
	 * Points
	 *
	 * @var integer
	 */
	protected $points;

	/**
	 * MaxPoints
	 *
	 * @var integer
	 */
	protected $maxPoints;
	
	/**
	 * FeUser
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
	 */
	protected $feUser;

	/**
	 * AuthCode
         * @lazy
	 * @var \Kennziffer\KeQuestionnaire\Domain\Model\AuthCode
	 */
	protected $authCode;
	
	/**
	 * selectLabel
	 *
	 * @var string
	 */
	protected $selectLabel;
        
        /**
	 * addParameter
	 *
	 * @var string
	 */
	protected $addParameter;



	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
	$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->questions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the feCruserId
	 *
	 * @return integer $feCruserId
	 */
	public function getFeCruserId() {
		return $this->feCruserId;
	}

	/**
	 * Sets the feCruserId
	 *
	 * @param integer $feCruserId
	 * @return void
	 */
	public function setFeCruserId($feCruserId) {
		$this->feCruserId = $feCruserId;
	}
	
	/**
	 * Returns the crdate
	 *
	 * @return integer $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Sets the crdate
	 *
	 * @param integer $crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Returns the finished
	 *
	 * @return integer $finished
	 */
	public function getFinished() {
		return $this->finished;
	}

	/**
	 * Sets the finished
	 *
	 * @param integer $finished
	 * @return void
	 */
	public function setFinished($finished) {
		$this->finished = $finished;
	}

	/**
	 * Adds a ResultQuestion
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $rquestion
	 * @return void
	 */
	public function addOrUpdateQuestion(\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $rquestion) {
		//check if a resultQuestion with this Question is already here
		//if no question is given		
		$rquestion = $this->checkMatrixType($rquestion);
		if (!$this->questionKnown($rquestion)){
			$this->addQuestion($rquestion);
		} else {
			$oldrquestion = $this->getResultQuestionForQuestion($rquestion->getQuestion());
			$oldrquestion->setAnswers($rquestion->getAnswers());
		}
	}	
	
	/**
	 * Checks the Question if it contains MatrixRows and checks their values
	 * 
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $question
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion return the question
	 */
	public function checkMatrixType(\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $question){
		$answers = $question->getAnswers();
		$newAnswers = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		foreach ($answers as $answer){
			//Check for array is value, this indicates the Extendet Matrix Header
			//Clear the Value and set the MatrixPos so the values will be saved correctly
			if (is_array($answer->getValue())){
				$vals = array();
				foreach ($answer->getValue() as $key => $val){
					$vals[$key]['value'] = $val;
					$vals[$key]['radioVal'] = $val;
				}
				if ($answer->getMatrixPos()){
					$allPos = array_merge($answer->getMatrixPos(),$vals);
					$answer->setMatrixPos($allPos);
				} else $answer->setMatrixPos($vals);						
				$answer->setValue(false);
			}
			//Work on Values for MatrixRows
			//For each Column there must be an answer if there is a MatrixPos given
			if ($answer->getAnswer() AND ($answer->getAnswer()->getType() == 'Kennziffer\\KeQuestionnaire\\Domain\\Model\\AnswerType\\MatrixRow' AND $answer->getMatrixPos())){
				foreach ($answer->getMatrixPos() as $pos_id => $pos){
					if ($pos['value']){
						$newAnswer = $this->duplicateAnswer($answer);	
						$newAnswer->setValue($pos['value']);
						$newAnswer->setAdditionalValue($pos['additionalValue']);							
						if ($pos['text'] == 1 OR !is_numeric($pos['value'])){
							$pos['value'] = $pos_id;
						} else {
							//if ($pos['radioVal']) $newAnswer->setValue($pos['value']);
							//else $newAnswer->setValue($answer->getAnswer()->getUid());
							//$newAnswer->setAdditionalValue($pos['additionalValue']);
						}			
						$newAnswer->setCol($pos['value']);
                        if ($newAnswer->getAnswer()) $newAnswers->attach($newAnswer);
					}
				}
				if ($answer->getValue()){
					if ($answer->getAnswer()) $newAnswers->attach($answer);
				}
			} else {
				if ($answer->getAnswer()) $newAnswers->attach($answer);
			}
            
            //Work on cloned rows
            //For each Column there must be an answer if there is a Clone given
            if ($answer->getAnswer() AND ($answer->getAnswer()->getType() == 'Kennziffer\\KeQuestionnaire\\Domain\\Model\\AnswerType\\MatrixRow' AND $answer->getCloned())){
                $cloned = $answer->getCloned();
				$i = 0;
                foreach ($cloned['title'] as $id => $title){
					if ($title != ''){
						$i++;                               
						foreach ($cloned as $pos_id => $pos){
							//Input Fields
							if (is_numeric($pos_id)){
								if ($pos['value'][$id]){
								   $newAnswer = $this->duplicateAnswer($answer);	
								   $newAnswer->setCloneTitle($title);
								   $newAnswer->setClone($i);
								   $newAnswer->setValue($pos['value'][$id]);
								   $newAnswer->setAdditionalValue($pos['additionalValue'][$id]);
								   if ($pos['text'][$id] == 1 OR !is_numeric($pos['value'][$id])){
									   $pos['value'][$id] = $pos_id;
								   } else {
									   //if ($pos['radioVal'][$id]) $newAnswer->setValue($pos['value'][$id]);
									   //else $newAnswer->setValue($answer->getAnswer()->getUid());
									   //$newAnswer->setAdditionalValue($pos['additionalValue'][$id]);
								   }			
								   $newAnswer->setCol($pos['value'][$id]);
								   if ($newAnswer->getAnswer()) $newAnswers->attach($newAnswer);
							   }
							//RadioButtons
							} elseif ($pos_id == 'value' AND is_array($cloned['value'])){
								if ($cloned['value'][$id]){
								   $newAnswer = $this->duplicateAnswer($answer);	
								   $newAnswer->setCloneTitle($title);
								   $newAnswer->setClone($i);
								   if ($cloned['value'][$id]) $newAnswer->setValue($cloned['value'][$id]);
								   else $newAnswer->setValue($answer->getAnswer()->getUid());
								   $newAnswer->setAdditionalValue($cloned['additionalValue'][$id]);

								   //$newAnswer->setCol($cloned['value'][$id]);
								   if ($newAnswer->getAnswer()) $newAnswers->attach($newAnswer);
								}
							}
						}                    
					}
                }
            }
		}
		$question->setAnswers($newAnswers);
		
		return $question;
	}
	
	/**
	 * 
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Answer $answer
	 * 
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\Answer $newAnswer
	 */
	private function duplicateAnswer($answer){
		$availableProperties = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettablePropertyNames($answer);
		$newAnswer = new \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer();
		foreach ($availableProperties as $propertyName) {
			if (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::isPropertySettable($newAnswer, $propertyName) && !in_array($propertyName, array('uid','pid','resultquestion'))) {
				$propertyValue = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($answer, $propertyName);
				\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($newAnswer, $propertyName, $propertyValue);
			}
		}
		
		return $newAnswer;
	}
	
	/**
	 * Adds a ResultQuestion
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $resultQuestion
	 * @return void
	 */
	public function addQuestion(\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $resultQuestion) {
		$this->questions->attach($resultQuestion);
	}

	/**
	 * Removes a ResultQuestion
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $questionToRemove The ResultQuestion to be removed
	 * @return void
	 */
	public function removeQuestion(\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $questionToRemove) {
		$this->questions->detach($questionToRemove);
	}

	/**
	 * Returns the questions
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion> $questions
	 */
	public function getQuestions() {
		return $this->questions;
	}
	
	/**
	 * Returns the questions
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Question $question
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion
	 */
	public function getResultQuestionForQuestion(\Kennziffer\KeQuestionnaire\Domain\Model\Question $question) {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$rQuestion = $rep->findByQuestionAndResult($question,$this);
		if ($rQuestion[0] AND $this->getQuestions()->contains($rQuestion[0])) {
			return $rQuestion[0];
		} else {
			return false;
		}
	}

	/**
	 * Try to find a resultQuestion with help of the question UID
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $question The question UID. NOT the UID of the resultQuestion
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion
	 */
	public function questionKnown($question) {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$rQuestion = $rep->findByQuestionAndResult($question->getQuestion(),$this);
		if ($rQuestion[0] AND $this->getQuestions()->contains($rQuestion[0])) {
			$rq = $rQuestion[0];
			if ($rq->getQuestion()->fullfillsDependancies($this)){
				$rq->checkAnswers($question->getAnswers());
			} else {
				$rq->clearAnswers();
			}
			return $rq;
		}
		else return FALSE;
	}
	
	/**
	 * Try to find a resultAnswer with help of the answer UID
	 *
	 * @param integer $questionUid The question UID. NOT the UID of the resultQuestion
	 * @param integer $answerUid The answer UID. NOT the UID of the resultAnswer
	 * @param integer $columnUid  The answer UID of the row. NOT the UID of the resultAnswer
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer
	 */
	public function getAnswer($questionUid, $answerUid, $columnUid = 0) {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$resultQuestion = $rep->findByQuestionAndResult($questionUid, $this);
		$resultQuestion = $resultQuestion[0];
		
		if ($resultQuestion){
			foreach ($resultQuestion->getAnswers() as $resultAnswer) {
				$answer = $resultAnswer->getAnswer();
				if ($answer){
					if ($columnUid == 0 AND $resultAnswer->getAnswer()->getShortType() == "MatrixRow") {
						if ($answerUid == $resultAnswer->getAnswer()->getUid() AND $columnUid == intval($resultAnswer->getCol()) AND $resultAnswer->getValue() != $resultAnswer->getAnswer()->getUid()) {
							return $resultAnswer;
						} 
					} elseif ($answerUid == $resultAnswer->getAnswer()->getUid() AND $columnUid == intval($resultAnswer->getCol())) {
						return $resultAnswer;
					} 
				}
			}
		}
		return NULL;
	}	
	
	/**
	 * Try to find a resultAnswer with help of the answer UID
	 *
	 * @param integer $questionUid The question UID. NOT the UID of the resultQuestion
	 * @param integer $answerUid The answer UID. NOT the UID of the resultAnswer
	 * @param integer $columnUid  The answer UID of the row. NOT the UID of the resultAnswer
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer
	 */
	public function getRadioAnswer($questionUid, $answerUid, $columnUid) {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$resultQuestion = $rep->findByQuestionAndResult($questionUid, $this);
		$resultQuestion = $resultQuestion[0];
		
		if ($resultQuestion){
			foreach ($resultQuestion->getAnswers() as $resultAnswer) {
				$answer = $resultAnswer->getAnswer();
				if ($answer){
					if ($answerUid == $resultAnswer->getAnswer()->getUid() AND $columnUid == $resultAnswer->getValue()) {
						return $resultAnswer;
					} 
				}
			}
		}
		return NULL;
	}	
	
	/**
	 * Try to find a resultAnswer with help of the answer UID
	 *
	 * @param integer $answerUid The resultAnswer UID
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer
	 */
	public function getResultAnswer($answerUid) {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultAnswerRepository');
		return $rep->findByUid($answerUid);
	}

	/**
	 * Sets the questions
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion> $questions
	 * @return void
	 */
	public function setQuestions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $questions) {
		$this->questions = $questions;
	}

	/**
	 * Returns the points
	 *
	 * @return integer $points
	 */
	public function getPoints() {
		return $this->points;
	}

	/**
	 * Sets the points
	 *
	 * @param integer $points
	 * @return void
	 */
	public function setPoints($points) {
		$this->points = $points;
	}

	/**
	 * Returns the maxPoints
	 *
	 * @return integer $maxPoints
	 */
	public function getMaxPoints() {
		return $this->maxPoints;
	}

	/**
	 * Sets the maxPoints
	 *
	 * @param integer $maxPoints
	 * @return void
	 */
	public function setMaxPoints($maxPoints) {
		$this->maxPoints = $maxPoints;
	}
	
	/**
	 * Setter for feUser
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser feUser
	 * @return void
	 */
	public function setFeUser(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser) {
		$this->feUser = $feUser;
	}

	/**
	 * Getter for feUser
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser feUser
	 */
	public function getFeUser() {
		return $this->feUser;
	}
	
	/**
	 * Setter for authCode
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\AuthCode $authCode authCode
	 * @return void
	 */
	public function setAuthCode(\Kennziffer\KeQuestionnaire\Domain\Model\AuthCode $authCode) {
		$this->authCode = $authCode;
	}

	/**
	 * Getter for authCode
	 *
	 * @return \Kennziffer\KeQuestionnaire\Domain\Model\AuthCode authCode
	 */
	public function getAuthCode() {
		return $this->authCode;
	}
	
	/**
	 * Returns the selectLabel
	 *
	 * @return string $selectLabel
	 */
	public function getSelectLabel() {
		$label = $this->getUid();
		if ($this->getFinished() > 0){
			$date = date('d.m.Y',$this->getFinished());
			$label .= ' ('.$date.')';
		}
		
		return $label;		
	}
	
	/**
	 * Returns the calculated Average
	 *
	 * @params boolean $calculateAll
	 * @return integer $average
	 */
	public function getAverage($all = false) {
		$qCount = 0;
		foreach ($this->getQuestions() as $rQuestion){
			if ($rQuestion->getMaxPoints() > 0){
				if ($all){
					$qCount ++;
				} else {
					if (count($rQuestion->getAnswers()) > 0) $qCount ++;
				}
			}
		}		
                if ($qCount > 0) $average = $this->getPoints() / $qCount;
		$average = number_format($average,2,',',' ');
		return $average;
	}
	
	/**
	 * check the points for the result object
	 *
	 * @return void
	 */
	public function calculatePoints() {
		$maxPoints = 0;
		$pointsForResult = 0;
		$group = NULL;
		$groupPoints = 0;
		$maxGroupPoints = 0;
		/* @var $resultQuestion \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion */
		//count for all questions in result
		foreach ($this->getQuestions() as $resultQuestion) {
				if ($resultQuestion->getQuestion()){
						// check for point calculation
						$pointsForQuestion = 0;				
						/* @var $resultAnswer \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer */
						if (count($resultQuestion->getAnswers()) > 0){
							//calculate for each answer
							foreach ($resultQuestion->getAnswers() as $resultAnswer) {						
									if ($resultAnswer->getAnswer()){
											$calcPoints = 0;
											$calcPoints = $resultAnswer->getPoints();
											$pointsForResult += $calcPoints;
											$pointsForQuestion += $calcPoints;
									}
									$resultAnswer->setFeCruserId($GLOBALS['TSFE']->fe_user->user['uid']);						
							}
						}
						//set the points for this questions
						$resultQuestion->setPoints($pointsForQuestion);
						$resultQuestion->setFeCruserId($GLOBALS['TSFE']->fe_user->user['uid']);

						//maxPoints are the maximum points for all the questions already part of this result
						$maxPoints += $resultQuestion->getMaxPoints();
						//if there are groups of questions, thex need to be calculated
						$groupPoints += $pointsForQuestion;
						//maximum points for this group
						$maxGroupPoints += $resultQuestion->getMaxPoints();
						//check the matrix type, relevant for calculation
						$resultQuestion = $this->checkMatrixType($resultQuestion);

						if ($group AND $groupPoints > 0) {
								$group->setPoints($groupPoints);
								$group->setMaxPoints($maxGroupPoints);
						}
						//check Group
						if($resultQuestion->getQuestion() instanceof \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group) {					
								$group = $resultQuestion;
								$groupPoints = 0;
								$maxGroupPoints = 0;
						}					
				}
		}
		$this->setPoints($pointsForResult);
		$this->setMaxPoints($maxPoints);			
	}	
        
        /**
	 * Returns the addParameter
	 *
	 * @return string $addParameter
	 */
	public function getAddParameter() {
		return $this->addParameter;
	}

	/**
	 * Sets the addParameter
	 *
	 * @param string $addParameter
	 * @return void
	 */
	public function setAddParameter($addParameter) {
		$this->addParameter = $addParameter;
	}
}
?>