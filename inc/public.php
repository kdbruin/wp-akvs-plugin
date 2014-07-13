<?php
class HBPublic extends HBPlugin
{

	protected function init()
	{
		$this->add_shortcode('hb-image', 'hb_image_shortcode');
	}

	function hb_image_shortcode($atts, $content = null, $code = "")
	{
		extract(shortcode_atts(array(
				'id' => null,
				'size' => 'thumbnail',
				'align' => 'none'
		), $atts));
		
		if (empty($id)) return;

		$html = get_image_tag($id, '', '', $align, $size);
		echo $html;
	}
}
