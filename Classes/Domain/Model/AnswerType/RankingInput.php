<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model\AnswerType;
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
class RankingInput extends \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence{
    /**
	 * pdfType
	 *
	 * @var string
	 */
	protected $pdfType = 'normal';
	
	/**
	 * Get the Ranking-Order as string
	 * 
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Question $question
	 * 
	 * @return string line for Analysis
	 */
	public function getRankingLine (\Kennziffer\KeQuestionnaire\Domain\Model\Result $result, \Kennziffer\KeQuestionnaire\Domain\Model\Question $question){		
		$line = array();
		
		$terms = $this->getTerms($question, $result);
		foreach ($terms as $term){
			$line[$term->getOrder()] = $term->getTitle();
		}
		return implode(', ',$line);
	}
	
	/**
	 * Gets the Images
	 * 
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question $question the terms are in
     * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @return array
	 */
	public function getTerms($question, $result){
		$terms = array();
		
		// workaround for pointer in question, so all following answer-objects are rendered.
		$addIt = false;
        $type = '';
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AnswerRepository');
		$repRQ = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$repRQA = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultAnswerRepository');
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		$rep->setDefaultQuerySettings($querySettings);
		$repRQ->setDefaultQuerySettings($querySettings);
		$repRQA->setDefaultQuerySettings($querySettings);
		$answers = $rep->findByQuestion($question);
        $ranswers = array();
        if ($result){
			$rQuestions = $repRQ->findByResult($result);
            foreach ($rQuestions as $rquestion){
                if ($rquestion->getQuestion()->getUid() == $question->getUid()){
					$rQAnswers = $repRQA->findByResultquestion($rquestion);
                    foreach ($rQAnswers as $ranswer){
                        $ranswers[$ranswer->getAnswer()->getUid()] = $ranswer->getValue();
                    }
                }
            }
        }
        ksort($ranswers);
		$counter = 0;
		foreach ($answers as $answer){
			//Add only after the correct Header is found, only following rows will be added.
			if ((
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder'
					) AND $answer === $this) {
                        $addIt = true; 
                        $type = get_class($answer);                        
            } elseif (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder') $addIt = false;
			if ($addIt){
				if (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm'){
                    $counter ++;
                    if ($answer->getOrder() == 0) $answer->setOrder($counter);
					if ($ranswers[$answer->getUid()]){
						$answer->setOrder($ranswers[$answer->getUid()]);
                    }
                    $terms[$answer->getOrder()] = $answer;
                }
			}
        }		
        $selectItems = array();
        for ($i = 0; $i < $counter; $i++){
            $selectItems[$i+1] = $i+1;
        }
        foreach ($terms as $nr => $term){
            $terms[$nr]->setSelectItems($selectItems);
        }
        ksort($terms);
		return $terms;
	}
}
?>