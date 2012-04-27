<?php

namespace MooTools\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Slider extends AbstractHelper {
	
	public function __invoke($id) {
		return <<<SLIDER
<div class="slide_container fright" >
	<div class="slider" id="slider_{$id}" >
		<div class="slider_gutter_l slider_gutter_item"></div>
		<div class="slider_gutter_i slider_gutter_item">
			<img class="slider_bkg" src="/images/slider/bkg_slider.gif"/>
			<div class="knob"></div>
		</div>
		<div class="slider_gutter_r slider_gutter_item"> </div>
	</div>
</div>
SLIDER;
	}
}