<?php
class HBAdmin extends HBPlugin
{

	protected function init()
	{
		register_activation_hook($this->_config->plugin_file, array(
				$this,
				'activate'
		));
		
		$this->add_filter('image_send_to_editor', 'image_send_to_editor', 20, 8);
	}

	public function activate()
	{
		// plugin activate code
	}

	/**
	 * Replace the HTML code with a shortcode with the same parameters so the image code is
	 * generated by the_content().
	 *
	 * @param $html string
	 *        	generated HTML for the inserted image
	 * @param $id int
	 *        	image ID
	 * @param $caption string
	 *        	caption for the image
	 * @param $title string
	 *        	title for the image
	 * @param $align string
	 *        	image alignment
	 * @param $url string
	 *        	source URL of the image
	 * @param $size string
	 *        	size for the inserted image
	 * @param $alt string
	 *        	alt text for the image
	 * @return formatted shortcode
	 */
	function image_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt)
	{
		$result = '[hb-image id="' . $id . '" size="' . $size . '" align="' . $align . '"';
		if (!empty($caption))
		{
			$result = $result . ' caption="' . $caption . '"';
		}
		if (!empty($title))
		{
			$result = $result . ' title="' . $title . '"';
		}
		if (!empty($alt))
		{
			$result = $result . ' alt="' . $alt . '"';
		}
		$result = $result . ']';
		return $result;
	}
}
