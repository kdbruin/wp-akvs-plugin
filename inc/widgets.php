<?php
/*
 * Widgets voor de AKV Soesterkwartier website
 */

/**
 * Add widget areas for the homepage, contacts page
 */
// function akvs_widgets_init()
// {
// 	// Widget area for the home page
// 	register_sidebar( array( 
// 		'name' => __( 'Homepage Widget Area', 'akvs' ), 
// 		'id' => 'homepage-widget-area', 
// 		'description' => __( 'Shown only on the homepage.', 'akvs' ), 
// 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">', 
// 		'after_widget' => '</li>', 
// 		'before_title' => '<h3 class="widget-title">', 
// 		'after_title' => '</h3>' 
// 	) );
	
// 	// Widget area for the contact pages
// 	register_sidebar( array( 
// 		'name' => __( 'Contact Pages Widget Area', 'akvs' ), 
// 		'id' => 'contacts-widget-area', 
// 		'description' => __( 'Shown only on the contact pages.', 'akvs' ), 
// 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">', 
// 		'after_widget' => '</li>', 
// 		'before_title' => '<h3 class="widget-title">', 
// 		'after_title' => '</h3>' 
// 	) );
// }
// add_action('widgets_init', 'akvs_widgets_init');

// Verjaardagen
class AKVS_VerjaardagenWidget extends WP_Widget
{

	function AKVS_VerjaardagenWidget()
	{
		$widget_ops = array( 
			'classname' => 'AKVS_VerjaardagenWidget', 
			'description' => 'Verjaardagen de komende week' 
		);
		$this->WP_Widget( 'AKVS_VerjaardagenWidget', 'AKVS Verjaardagen', $widget_ops );
	}

	function form( $instance )
	{
		$instance = wp_parse_args( ( array ) $instance, array( 
			'title' => '' 
		) );
		$title = $instance[ 'title' ];
		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title: <input
		class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo esc_attr($title); ?>" /></label>
</p>
<?php
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		return $instance;
	}

	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
		$title = empty( $instance[ 'title' ] ) ? ' ' : apply_filters( 'widget_title', $instance[ 'title' ] );
		
		if ( !empty( $title ) ) echo $before_title . $title . $after_title;
		
		echo do_shortcode( '[akvs_verjaardagen]' );
		
		echo $after_widget;
	}
}

// Activiteiten
class AKVS_ActiviteitenWidget extends WP_Widget
{

	function AKVS_ActiviteitenWidget()
	{
		$widget_ops = array( 
			'classname' => 'AKVS_ActiviteitenWidget', 
			'description' => 'Aankomende activiteiten' 
		);
		$this->WP_Widget( 'AKVS_ActiviteitenWidget', 'AKVS Activiteiten', $widget_ops );
	}

	function form( $instance )
	{
		$instance = wp_parse_args( ( array ) $instance, array( 
			'title' => '' 
		) );
		$title = $instance[ 'title' ];
		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title: <input
		class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo esc_attr($title); ?>" /></label>
</p>
<?php
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		return $instance;
	}

	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
		$title = empty( $instance[ 'title' ] ) ? ' ' : apply_filters( 'widget_title', $instance[ 'title' ] );
		
		if ( !empty( $title ) ) echo $before_title . $title . $after_title;
		
		echo do_shortcode( '[akvs_kalender aantal="3"]' );
		
		echo '<p class="alignright"><a href="' . home_url( '/vereniging/activiteiten/' ) . '">Meer activiteiten...</a></p>';
		
		echo $after_widget;
	}
}

// Sponsors
class AKVS_SponsorWidget extends WP_Widget
{

	function AKVS_SponsorWidget()
	{
		$widget_ops = array( 
			'classname' => 'AKVS_SponsorWidget', 
			'description' => 'Onze sponsors' 
		);
		$this->WP_Widget( 'AKVS_SponsorWidget', 'AKVS Sponsors', $widget_ops );
	}

	function form( $instance )
	{
		$instance = wp_parse_args( ( array ) $instance, array( 
			'title' => '' 
		) );
		$title = $instance[ 'title' ];
		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title: <input
		class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo esc_attr($title); ?>" /></label>
</p>
<?php
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		return $instance;
	}

	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
		$title = empty( $instance[ 'title' ] ) ? ' ' : apply_filters( 'widget_title', $instance[ 'title' ] );
		
		if ( !empty( $title ) ) echo $before_title . $title . $after_title;
		
		echo do_shortcode( '[akvs_sponsors]' );
		
		echo $after_widget;
	}
}

// Social sites
class AKVS_SocialWidget extends WP_Widget
{

	function AKVS_SocialWidget()
	{
		$widget_ops = array( 
			'classname' => 'AKVS_SocialWidget', 
			'description' => 'Social links' 
		);
		$this->WP_Widget( 'AKVS_SocialWidget', 'AKVS Social', $widget_ops );
	}

	function form( $instance )
	{
		$instance = wp_parse_args( ( array ) $instance, array( 
			'title' => '' 
		) );
		$title = $instance[ 'title' ];
		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title: <input
		class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo esc_attr($title); ?>" /></label>
</p>
<?php
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		return $instance;
	}

	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
		$title = empty( $instance[ 'title' ] ) ? ' ' : apply_filters( 'widget_title', $instance[ 'title' ] );
		
		if ( !empty( $title ) ) echo $before_title . $title . $after_title;
		
		echo do_shortcode( '[akvs_social_links]' );
		
		echo $after_widget;
	}
}
