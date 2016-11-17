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
 * get the cloned result-part
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GetClonedResultAnswersViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns a requested question from result record
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Result $result
	 * @param integer $questionUid
	 * @param integer $answerUid
	 * @return
	 */
    public function render($result, $questionUid, $answerUid) {
        $resultQuestions = $result->getQuestions();
        /* @var $resultQuestion \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion */
		foreach ($resultQuestions as $resultQuestion) {
            if ($questionUid == $resultQuestion->getQuestion()->getUid()) {
				/* @var $resultAnswer \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer */
				return $resultQuestion->getClonedAnswers();
			}
		}
        return NULL;
	}
}
?>