<?php
class HalfjeBruin_Public extends HalfjeBruin_Plugin
{

	protected function init()
	{
		// shortcodes
		$this->add_shortcode( 'hb-image', 'hb_image_shortcode' );
		
		// hooks
		if ( $this->_config->ga_enabled ) $this->add_action( 'wp_footer', 'hb_google_analytics' );
	}

	function hb_image_shortcode( $attr, $content = null, $tag = "" )
	{
		extract( shortcode_atts( array( 
			'id' => null, 
			'size' => 'thumbnail', 
			'align' => 'none', 
			'title' => '', 
			'caption' => '', 
			'alt' => '', 
			'url' => '' 
		), $attr ) );
		
		if ( empty( $id ) ) return;
		
		$img_html = get_image_tag( $id, $alt, $title, $align, $size );
		if ($url) $img_html = '<a href="' . esc_attr($url) . '">' . $img_html . '</a>';
		if ( empty( $caption ) )
		{
			return $img_html;
		}
		
		$id = 'id="' . esc_attr( $id ) . '"';
		$class = 'class="' . esc_attr( "wp-caption align$align" ) . '"';
		
		$html5 = "<figure $id $class>";
		$html5 .= $img_html;
		if ( $caption )
		{
			$html5 .= "<figcaption class='wp-caption-text'>$caption</figcaption>";
		}
		$html5 .= "</figure>";
		
		return $html5;
	}

	function hb_google_analytics()
	{
		$ga_user_id = $this->_config->ga_user_id;
		$ga_url = $this->_config->ga_url;
		
		if ( empty( $ga_user_id ) || empty( $ga_url ) ) return;
		
		echo "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
  ga('create', '" . $ga_user_id . "', '" . $ga_url . "');
  ga('send', 'pageview');
	
</script>";
	}
}
