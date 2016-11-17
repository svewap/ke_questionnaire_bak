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
class DDAreaImage extends \Kennziffer\KeQuestionnaire\Domain\Model\Answer {
	/**
	 * pdfType
	 *
	 * @var string
	 */
	protected $pdfType = 'special';
	
	/**
	 * Image
	 *
	 * @var string
	 */
	protected $image;

	/**
	 * coords
	 *
	 * @var string
	 */
	protected $coords;
	
	/**
	 * Width
	 *
	 * @var integer
	 */
	protected $width;

	/**
	 * Height
	 *
	 * @var integer
	 */
	protected $height;
	
	/**
	 * Highlight the areas when mouse over
	 *
	 * @var boolean
	 */
	protected $areaHighlight;
	
	/**
	 * Returns the areaHighlight
	 *
	 * @return boolean areaHighlight
	 */
	public function getAreaHighlight() {
		return $this->areaHighlight;
	}

	/**
	 * Sets the areaHighlight
	 *
	 * @param boolean $areaHighlight
	 * @return void
	 */
	public function setAreaHighlight($areaHighlight) {
		$this->areaHighlight = $areaHighlight;
	}
	
	/**
	 * Returns the image
	 *
	 * @return string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}
	
	/**
	 * Returns the coords
	 *
	 * @return array $coords
	 */
	public function getCoords() {
		$lines = explode("\n", $this->coords);
		foreach ($lines as $key => $line){
			$line = explode(',',trim($line));
			$coords[$key]['key'] = $key+1;
			$coords[$key]['x1'] = $line[0];
			$coords[$key]['y1'] = $line[1];
			$coords[$key]['x2'] = $line[2];
			$coords[$key]['y2'] = $line[3];
			$coords[$key]['width'] = $coords[$key]['x2'] - $coords[$key]['x1'];
			$coords[$key]['height'] = $coords[$key]['y2'] - $coords[$key]['y1'];
			$coords[$key]['title'] = $line[4];
		}
		return $coords;
	}

	/**
	 * Sets the coords
	 *
	 * @param string $coords
	 * @return void
	 */
	public function setCoords($coords) {
		$this->coords = $coords;
	}
	
	/**
	 * Returns the width
	 *
	 * @return integer $width
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Sets the width
	 *
	 * @param integer $width
	 * @return void
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * Returns the height
	 *
	 * @return integer $height
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Sets the height
	 *
	 * @param integer $height
	 * @return void
	 */
	public function setHeight($height) {
		$this->height = $height;
	}
	
	/**
	 * check of all pics are in the correct area
	 * 
	 * @param array $resultAnswers
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Question $question
	 * @return boolean
	 */
	public function imagesCorrectPlaced($resultAnswers, \Kennziffer\KeQuestionnaire\Domain\Model\Question $question){
		foreach ($resultAnswers as $answer){
			$counter = 0;
			$matched = 0;
			if ($answer->getAnswer()->getShortType() == 'DDImage'){
				$counter ++;
				if ($answer->getValue() == $answer->getAnswer()->getAreaIndex()) $matched ++;				
			}
			if ($counter > 0 AND $counter == $matched) return true;
		}
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array(
			$counter, $matched
		), 'result');
		return false;
	}
}
?>