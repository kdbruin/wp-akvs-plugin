<?php
class HBPublic extends HBPlugin
{

	protected function init()
	{
		$this->add_shortcode('hb-image', 'hb_image_shortcode');
	}

	function hb_image_shortcode($attr, $content = null, $tag = "")
	{
		extract(shortcode_atts(array(
				'id' => null,
				'size' => 'thumbnail',
				'align' => 'none',
				'title' => '',
				'caption' => '',
				'alt' => ''
		), $attr));

		if (empty($id)) return;

		$img_html = get_image_tag($id, $alt, $title, $align, $size);
		if (empty($caption))
		{
			echo $img_html;
			return;
		}
		
		$id = 'id="' . esc_attr($id) . '"';
		$class = 'class="' . esc_attr("wp-caption align$align") . '"';

		$html5 = "<figure $id $class>";
		$html5 .= $img_html;
		if ($caption)
		{
			$html5 .= "<figcaption class='wp-caption-text'>$caption</figcaption>";
		}
		$html5 .= "</figure>";

		echo $html5;
	}
}
