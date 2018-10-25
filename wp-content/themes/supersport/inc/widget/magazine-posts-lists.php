<?php
/***
 * Magazine Posts Boxed Widget
 *
 * Display the latest posts from a selected category in a boxed layout. 
 * Intented to be used in the Magazine Homepage widget area to built a magazine layouted page.
 *
 * @package supersport
 */

class SuperSport_Magazine_Posts_Lists_Widget extends WP_Widget {

	protected $enable_postmeta = array();

	/**
	 * Widget Constructor
	 */
	function __construct() {

		// Setup Widget
		parent::__construct(
			'magazine-posts-lists', // ID
			sprintf( esc_html__( 'TC: Magazine List', 'supersport' ), wp_get_theme()->Name ), // Name
			array( 
				'classname'		=> 'supersport-magazine-posts-lists', 
				'description' 	=> esc_html__( 'Add this widgets to appear this style on magazine template.', 'supersport' ),
				'customize_selective_refresh' => true, 
			) // Args
		);

		// Delete Widget Cache on certain actions
		add_action( 'save_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'delete_widget_cache' ) );
		
	}


	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'				=> '',
			'category'			=> 0,
			'layout'			=> 'left',
			'post_layout_style'	=> 'style_1',
			'number'			=> 10,
			'meta_date'			=> true,
			'meta_category'		=> false,
			'meta_tag'			=> false,
			'meta_author'		=> false,
			'meta_comment'		=> false,
		);

		return $defaults;
		
	}

	public function enable_post_metadata( $post_metadata ) {

		return $this->enable_postmeta;

	}

	function magazine_posts_excerpt_length( $length ) {
		return 15;
	}


	/**
	 * Main Function to display the widget
	 * 
	 * @uses this->render()
	 * 
	 * @param array $args / Parameters from widget area created with register_sidebar()
	 * @param array $instance / Settings for this widget instance
	 */
	function widget( $args, $instance ) {

		$cache = array();

		// Get Widget Object Cache
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_supersport_magazine_posts_lists', 'widget' );
		}
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		// Display Widget from Cache if exists
		if ( isset( $cache[ $this->id ] ) ) {
			echo $cache[ $this->id ];
			return;
		}

		// Start Output Buffering
		ob_start();

		// Get Widget Settings
		$settings = wp_parse_args( $instance, $this->default_settings() );		
		if ( isset( $settings['meta_date'] ) && true == $settings['meta_date'] ) {
			$this->enable_postmeta = wp_parse_args( array('date'), $this->enable_postmeta );
		}

		if ( isset( $settings['meta_author'] ) && true == $settings['meta_author'] ) {

			$this->enable_postmeta = wp_parse_args( array('author') ,$this->enable_postmeta );
		}

		if ( isset( $settings['meta_category'] ) && true == $settings['meta_category'] ) {

			$this->enable_postmeta = wp_parse_args( array('category') ,$this->enable_postmeta );
		}
		if ( isset( $settings['meta_tag'] ) && true == $settings['meta_tag'] ) {

			$this->enable_postmeta = wp_parse_args( array('tag'), $this->enable_postmeta );
		}
		if ( isset( $settings['meta_comment'] ) && true == $settings['meta_comment'] ) {

			$this->enable_postmeta = wp_parse_args( array('comment'), $this->enable_postmeta );
		}

		add_filter( 'basepress_enable_post_metadata', array( $this, 'enable_post_metadata' ) );
		add_action('magazine_homepage_posts', 'supersport_magazine_homepage_'. $settings['post_layout_style'], 30, 1);

		// Output
		echo $args['before_widget'];

	?>
		<div class="widget-magazine-posts-lists widget-magazine-posts clearfix">

			<?php // Display Title
			$this->widget_title( $args, $settings ); ?>
			
			<div class="widget-magazine-posts-content">
			
				<?php do_action('magazine_homepage_posts', $settings) ?>
				
			</div>
			
		</div> <!-- .widget-magazine-posts-lists -->
	<?php
		echo $args['after_widget'];

		remove_filter( 'basepress_enable_post_metadata', array( $this, 'enable_post_metadata' ) );

		// Set Cache
		if ( ! $this->is_preview() ) {
			
			$cache[ $this->id ] = ob_get_flush();
			wp_cache_set( 'widget_supersport_magazine_posts_lists', $cache, 'widget' );
		
		} else {
			
			ob_end_flush();

		}
	
	}
	
	/**
	 * Displays Widget Title
	 */
	function widget_title( $args, $settings ) {

		// Add Widget Title Filter
		$widget_title = apply_filters( 'widget_title', $settings['title'], $settings, $this->id_base );

		if( ! empty( $widget_title ) ) :

			// Link Category Title
			if( $settings['category'] > 0 ) : 

				// Set Link URL and Title for Category
				$link_title = sprintf( esc_html__( 'View all posts from category %s', 'supersport' ), get_cat_name( $settings['category'] ) );
				$link_url = esc_url( get_category_link( $settings['category'] ) );
				
				// Display Widget Title with link to category archive
				echo '<div class="wrap-header">';
				echo '<div class="gamma magazine-widget-title"><a class="category-archive-link" href="'. esc_url( $link_url ) .'" title="'. esc_attr( $link_title ) . '">'. $widget_title . '</a></div>';
				echo '</div>';

			else:

				// Display default Widget Title without link
				echo $args['before_title'] . $widget_title . $args['after_title']; 

			endif;

		endif;

	} // widget_title()


	/**
	 * Update Widget Settings
	 *
	 * @param array $new_instance / New Settings for this widget instance
	 * @param array $old_instance / Old Settings for this widget instance
	 * @return array $instance
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['category'] = (int)$new_instance['category'];
		$instance['layout'] = esc_attr($new_instance['layout']);
		$instance['post_layout_style'] = esc_attr($new_instance['post_layout_style']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['meta_date'] = !empty($new_instance['meta_date']);
		$instance['meta_author'] = !empty($new_instance['meta_author']);
		$instance['meta_category'] = !empty($new_instance['meta_category']);
		$instance['meta_tag'] = !empty($new_instance['meta_tag']);
		$instance['meta_comment'] = !empty($new_instance['meta_comment']);

		$this->delete_widget_cache();

		return $instance;
	}


	/**
	 * Displays Widget Settings Form in the Backend
	 *
	 * @param array $instance / Settings for this widget instance
	 */
	function form( $instance ) {
	
		// Get Widget Settings
		$settings = wp_parse_args( $instance, $this->default_settings() );

	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'supersport' ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('category') ); ?>"><?php esc_html_e( 'Category:', 'supersport' ); ?></label><br/>
			<?php // Display Category Select
				$args = array(
					'show_option_all'	=> esc_html__( 'All Categories', 'supersport' ),
					'show_count' 		 => true,
					'hide_empty'		 => false,
					'selected'			 => esc_attr( $settings['category'] ),
					'name'				 => esc_attr( $this->get_field_name('category') ),
					'id'				 => esc_attr( $this->get_field_id('category') )
				);
				wp_dropdown_categories( $args ); 
			?>
		</p>

		<?php $this->_post_layout_style( $settings ) ?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts:', 'supersport' ); ?>
				<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo (int) $settings['number']; ?>" size="3" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_author' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_author'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'meta_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_author' ) ); ?>" />
				<?php esc_html_e( 'Display post author', 'supersport' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_date' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_date'] ) ; ?> id="<?php echo esc_attr(  $this->get_field_id( 'meta_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_date' ) ); ?>" />
				<?php esc_html_e( 'Display post date', 'supersport' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_category' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_category'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'meta_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_category' ) ); ?>" />
				<?php esc_html_e( 'Display post category', 'supersport' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_tag' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_tag'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'meta_tag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_tag' ) ); ?>" />
				<?php esc_html_e( 'Display post tags', 'supersport' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_comment' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_comment'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'meta_comment' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_comment' ) ); ?>" />
				<?php esc_html_e( 'Display post comment', 'supersport' ); ?>
			</label>
		</p>

	<?php
	} // form()
	
	
	private function _post_layout_style( $settings ) {

		// Default layout
		$layouts = apply_filters( 'supersport_mg_post_list_layouts', array(
			'style_1',
			'style_2',
			'style_3',
		));

		// Make sure at less a layout apply
		if ( empty($layouts) || count($layouts) == 0 ) {

			$layouts = array('style_1');

		}

		echo '<div class="wrap-featured-layout">';
		foreach( $layouts as $layout ) :

			$checked = $settings['post_layout_style'] === $layout ? true : false;
			?>

			<label class="featured-layout-style">
				<input class="radio" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'post_layout_style' ) ); ?>" value="<?php echo esc_attr( $layout ) ?>" <?php checked( $checked, 1 ) ?> />
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() .'/images/featured-images/'. $layout . '.png' ); ?>" />
			</label>

		<?php
		endforeach;
		echo '</div>';
	}

	/**
	 * Delete Widget Cache
	 */
	public function delete_widget_cache() {
		
		wp_cache_delete( 'widget_supersport_magazine_posts_lists', 'widget' );
		
	}
	
}

// Register Widget
function supersport_register_magazine_posts_lists_widget() {

	register_widget( 'SuperSport_Magazine_Posts_Lists_Widget' );

}
add_action( 'widgets_init', 'supersport_register_magazine_posts_lists_widget' );