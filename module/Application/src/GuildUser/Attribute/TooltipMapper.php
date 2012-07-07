<?php
namespace GuildUser\Attribute;
use SimpleXmlElement;

class TooltipMapper {
	/**
	 * @var \SimpleXmlElement
	 */
	private $tooltipsSimpleXml;
	
	
	public function __construct() {
		$this->tooltipsSimpleXml = simplexml_load_file(getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'tooltips.xml');
	}
	
	public function findAllTooltips() {
		$tooltips = array();
		foreach($this->tooltipsSimpleXml->xpath('tooltip[@type="attribute"]') as $tooltip) {
			$tooltips = array_merge($tooltips, $this->hydrateHeirarchy($tooltip));
		}
		return $tooltips;
	}
	
	protected function hydrateHeirarchy(SimpleXmlElement $tooltip) {
		$tooltipArray = array();
		$tooltipArray[(string)$tooltip['id']] = array('name' => (string)$tooltip->name, 'headline' => (string)$tooltip->headline);
		$tooltipArray[(string)$tooltip['id']]['scores'] = array();
		$scores = current((array)$tooltip->scores);
		foreach ($scores as $score) {
			$tooltipArray[(string)$tooltip['id']]['scores'][(string)$score['value']] = array('quote' => (string)$score->quote, 'text' => (string)$score->text);
		}
		return $tooltipArray;
	}
}
