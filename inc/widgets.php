<?php
/*
 * Widgets voor de AKV Soesterkwartier website
 */

// Verjaardagen
class AKVS_VerjaardagenWidget extends WP_Widget
{

	function AKVS_VerjaardagenWidget()
	{
		$widget_ops = array( 
			'classname' => 'AKVS_VerjaardagenWidget', 
			'description' => 'Verjaardagen de komende week' 
		);
		parent::__construct( 'AKVS_VerjaardagenWidget', 'AKVS Verjaardagen', $widget_ops );
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
		parent::__construct( 'AKVS_ActiviteitenWidget', 'AKVS Activiteiten', $widget_ops );
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
		
		$more = __( 'Meer activiteiten<i class="fa fa-arrow-circle-o-right"></i>', 'akvs-plugin' );
		echo '<p class="more-activities"><a href="' . home_url( '/vereniging/activiteiten/' ) . '">' . $more . '</a></p>';
		
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
		parent::__construct( 'AKVS_SponsorWidget', 'AKVS Sponsors', $widget_ops );
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
		parent::__construct( 'AKVS_SocialWidget', 'AKVS Social', $widget_ops );
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
