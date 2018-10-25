<?php
/***
 * Magazine Posts Featured Widget
 *
 * Display the latest posts from a selected category in a featured layout. 
 * Intented to be used in the Magazine Homepage widget area to built a magazine layouted page.
 *
 * @package supersport
 */

class SuperSport_Magazine_Posts_Featured_Widget extends WP_Widget {

	protected $enable_postmeta = array();

	/**
	 * Widget Constructor
	 */
	function __construct() {

		parent::__construct(
			'magazine-posts-featured', // ID
			sprintf( esc_html__( 'TC: Featured Section', 'supersport' ), wp_get_theme()->Name ), // Name
			array( 
				'classname'		=> 'magazine_posts_featured', 
				'description'	=> esc_html__( 'Use this widget to custom display style in featured homepage.', 'supersport' ),
				'customize_selective_refresh' => true, 
			) //Args
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
			'title'							=> '',
			'category'						=> 0,
			'featured_post_layout'			=> 'layout_8',
			'order_posts'					=> 'ASC',
			'cats_post'						=> true,
			'meta_date'						=> true,
			'meta_author'					=> false,
		);
		
		return $defaults;
		
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
			$cache = wp_cache_get( 'supersport_widget_magazine_posts_featured', 'widget' );
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

			$this->enable_postmeta = wp_parse_args( array('author'), $this->enable_postmeta );
		}

		add_filter( 'basepress_enable_post_metadata', array( $this, 'enable_post_metadata' ) );
		
		add_action('magazine_posts_featured', 'supersport_featured_posts_'. $settings['featured_post_layout'], 30, 1);
		
		// Output
		echo $args['before_widget'];
		?>
		
		<div class="magazine-posts-featured widget-magazine-posts clearfix">

			<?php // Display Title
			$this->widget_title( $args, $settings ); ?>
			
			<div class="widget-magazine-posts-content">

				<?php do_action('magazine_posts_featured', $settings) ?>
				
			</div>
			
		</div>

		<?php
			echo $args['after_widget'];

			remove_filter( 'basepress_enable_post_metadata', array( $this, 'enable_post_metadata' ) );
			
			// Set Cache
			if ( ! $this->is_preview() ) {
				
				$cache[ $this->id ] = ob_get_flush();
				wp_cache_set( 'supersport_widget_magazine_posts_featured', $cache, 'widget' );
			
			} else {
				
				ob_end_flush();

			}

	}

	public function enable_post_metadata( $post_metadata ) {

		return $this->enable_postmeta;

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
				echo '<div class="widget-header clearfix">';
				echo '<h3 class="magazine-widget-title"><a class="category-archive-link" href="'. esc_url( $link_url ) .'" title="'. esc_attr( $link_title ) . '">'. $widget_title . '</a></h3>';
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
		$instance['layout']	= esc_attr($new_instance['layout']);
		$instance['featured_post_layout'] = esc_attr($new_instance['featured_post_layout']);
		$instance['order_posts'] = esc_attr($new_instance['order_posts']);
		$instance['cats_post'] = !empty($new_instance['cats_post']);
		$instance['meta_date'] = !empty($new_instance['meta_date']);
		$instance['meta_author'] = !empty($new_instance['meta_author']);
		
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
					'show_count'		=> true,
					'hide_empty'		=> false,
					'selected'			=> esc_attr( $settings['category'] ),
					'name'				=> esc_attr( $this->get_field_name('category') ),
					'id'				=> esc_attr( $this->get_field_id('category') )
				);
			wp_dropdown_categories( $args ); 
			?>
		</p>
		
		<?php $this->_render_layouts( $settings ); ?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('order_posts') ); ?>"><?php esc_html_e( 'Order Posts:', 'supersport' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id('order_posts') ); ?>" name="<?php echo esc_attr(  $this->get_field_name('order_posts') ); ?>">
				<option <?php selected( $settings['order_posts'], 'DESC' ); ?> value="DESC" ><?php esc_html_e( 'Latest Posts', 'supersport' ); ?></option>
				<option <?php selected( $settings['order_posts'], 'ASC' ); ?> value="ASC" ><?php esc_html_e( 'Oldest Posts', 'supersport' ); ?></option>
				<option <?php selected( $settings['order_posts'], 'RAND' ); ?> value="RAND" ><?php esc_html_e( 'Random Posts', 'supersport' ); ?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cats_post' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['cats_post'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'cats_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cats_post' ) ); ?>" />
				<?php esc_html_e( 'Display categories', 'supersport' ); ?>
			</label>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_date' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_date'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'meta_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_date' ) ); ?>" />
				<?php esc_html_e( 'Display post date', 'supersport' ); ?>
			</label>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_author' ) ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_author'] ) ; ?> id="<?php echo esc_attr( $this->get_field_id( 'meta_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_author' ) ); ?>" />
				<?php esc_html_e( 'Display post author', 'supersport' ); ?>
			</label>
		</p>

<?php
	} // form()
	
	private function _render_layouts( $settings ) {

		// Default layout
		$layouts = apply_filters( 'supersport_mg_featured_posts_layout', array(
			'layout_8',
			'layout_6',
		));

		// Make sure at less a layout apply
		if ( empty($layouts) || count($layouts) == 0 ) {
			$layouts = array('layout_8');
		}
		?>

			<div class="wrap-featured-layout">

				<?php
				foreach( $layouts as $layout) :
					$checked = $settings['featured_post_layout'] === $layout ? true : false;
				?>

					<label class="featured-layout-style">
						<input class="radio" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'featured_post_layout' ) ); ?>" <?php checked($checked, 1) ?> value="<?php echo esc_attr($layout) ?>" />
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() .'/images/featured-images/'. $layout .'.png' ) ; ?>" title="<?php esc_attr_e('Featured Style', 'supersport') ?>" alt="<?php esc_attr_e('Featured Style', 'supersport') ?>" />
					</label>

				<?php
				endforeach;
				?>

			</div>

		<?php

	}

	/**
	 * Delete Widget Cache
	 */
	public function delete_widget_cache() {
		
		wp_cache_delete( 'supersport_widget_magazine_posts_featured', 'widget' );

	}
}

// Register Widget
function supersport_register_magazine_posts_featured_widget() {

	register_widget( 'SuperSport_Magazine_Posts_Featured_Widget' );
	
}
add_action( 'widgets_init', 'supersport_register_magazine_posts_featured_widget' );