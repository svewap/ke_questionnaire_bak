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
class MatrixRowViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Adds the needed Javascript-File to Additional Header Data
	 *
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Answer $answer Answer to be rendered
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question $question the images are in
	 * @param string $as The name of the iteration variable
	 * @return string
	 */
	public function render($answer,$question,$as) {
		if (get_class($answer) == 'Kennziffer\\KeQuestionnaire\\Domain\\Model\\AnswerType\\MatrixHeader' OR get_class($answer) == 'Kennziffer\\KeQuestionnairePremium\\Domain\\Model\\AnswerType\\ExtendedMatrixHeader'){
		    $rows = $answer->getRows($question);
			
            $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
            if ($question === NULL) {
                return '';
            }

            foreach ($rows as $nr => $element){
                $templateVariableContainer->add($as, $element);
                $output .= $this->renderChildren();
                $templateVariableContainer->remove($as);
            }            
        }
        return $output;
	}	
}
?>