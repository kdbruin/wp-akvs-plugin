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
				'align' => 'none',
				'title' => '',
				'caption' => '',
				'alt' => ''
		), $atts));
		
		if (empty($id)) return;
		
		$img_html = get_image_tag($id, $alt, $title, $align, $size);
		
		$html5 = "<figure id='post-$id media-$id' class='figure align$align'>";
		$html5 .= $img_html;
		if ($caption)
		{
			$html5 .= "<figcaption>$caption</figcaption>";
		}
		$html5 .= "</figure>";

		echo $html5;
	}
}
