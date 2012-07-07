<?php
namespace Game\View\Helper;
use Zend\Form\View\Helper\AbstractHelper;

class GameIcon extends AbstractHelper {
	public function __invoke($game, $enabled = null) {
		$this->getView()->headLink()->appendStylesheet('/css/gameicon.css');
		$checkbox = '';
		if (! is_null($enabled) ) {
			$checked = $enabled ? 'checked="checked"' : '';
			$checkbox = "<input class=\"game_selector_input\" value=\"{$game['game_id']}\" type=\"checkbox\" {$checked} />";
		}
		return <<<GameIcon
					<div class="game_select_box fleft">
						<div class="image_holder" style="background-image: url(/images/games/dnd.png);">
							{$checkbox}
						</div>
						<label>{$game['game_name']}</label>
					</div>
GameIcon;
	}
}

