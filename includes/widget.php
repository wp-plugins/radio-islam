<?php

/**
 * RII_Player_Widget
 */
class RII_Player_Widget extends WP_Widget {
	
	protected $widget_slug = 'rii';

	/**
	 * the constructor
	 */
	public function __construct() {
		parent::__construct(
			$this->rii_widget_slug(),
			__( 'Radio Islam Indonesia (RII)', $this->rii_widget_slug() ),
			array(
				'classname'		=> $this->rii_widget_slug() . '-player',
				'description'	=> __( 'Radio Islam Indonesia (RII) Player', $this->rii_widget_slug() )
			)
		);

		add_action( 'init', array( $this, 'rii_data_class' ) );
		add_action( 'wp_ajax_rii_data', array( $this, 'rii_data_json' ) );
		add_action( 'wp_ajax_nopriv_rii_data', array( $this, 'rii_data_json' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'rii_widget_assets' ) );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// instance
		$title 		= !empty( $instance['title'] ) ? $instance['title'] : '';
		$interval 	= $instance['interval'];
		$skin 		= $instance['skin'];
		$equalizer 	= $instance['equalizer'];
		$credits 	= $instance['credits'];

		// extract args
		extract( $args, EXTR_SKIP );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . __( $title ) . $after_title;
		}

		include( plugin_dir_path( RII_PLUGIN_FILE ) . 'views/player.php' );

		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// defaults
		$instance = wp_parse_args( (array) $instance, 
			array( 
				'title' 	=> '', 
				'interval' 	=> 15000, 
				'skin' 		=> 'dark',
				'equalizer'	=> true,
				'credits'	=> false
			) 
		);
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->rii_widget_slug() ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'interval' ); ?>"><?php _e( 'Auto Refresh Interval:', $this->rii_widget_slug() ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'interval' ); ?>" name="<?php echo $this->get_field_name( 'interval' ); ?>" type="text" value="<?php echo esc_attr( $instance['interval'] ); ?>">
			<small><?php _e( 'Default is 15000 (15 seconds). Set to 0 to disable auto refresh data.', $this->rii_widget_slug() ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'skin' ); ?>"><?php _e( 'Player Skin:', $this->rii_widget_slug() ); ?></label><br>
			<select class="widefat" id="<?php echo $this->get_field_id( 'skin' ); ?>" name="<?php echo $this->get_field_name( 'skin' ); ?>">
				<?php
					$skin_options = array( 'dark' => __( 'Dark Skin', $this->rii_widget_slug() ), 'light' => __( 'Light Skin', $this->rii_widget_slug() ) );
					foreach ( $skin_options as $s => $n ) {
						printf(
							'<option value="%1$s"%3$s>%2$s</option>',
							esc_attr( $s ),
							esc_html( $n ),
							selected( $instance['skin'], $s, false )
						);
					}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'equalizer' ); ?>"><?php _e( 'Show Equalizer:', $this->rii_widget_slug() ); ?> 
				<input class="checkbox" id="<?php echo $this->get_field_id( 'equalizer' ); ?>" name="<?php echo $this->get_field_name( 'equalizer' ); ?>" type="checkbox" <?php checked($instance['equalizer'], true) ?> />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'credits' ); ?>"><?php _e( 'Show Credits (Powered By):', $this->rii_widget_slug() ); ?> 
				<input class="checkbox" id="<?php echo $this->get_field_id( 'credits' ); ?>" name="<?php echo $this->get_field_name( 'credits' ); ?>" type="checkbox" <?php checked($instance['credits'], true) ?> />
			</label>
		</p>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] 		= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['interval'] 	= ( ! empty( $new_instance['interval'] ) ) ? intval( $new_instance['interval'] ) : 0;
		$instance['skin'] 		= $new_instance['skin'];
		$instance['equalizer'] 	= isset( $new_instance['equalizer'] ) ? 1 : 0;
		$instance['credits'] 	= isset( $new_instance['credits'] ) ? 1 : 0;

		return $instance;
	}

	/**
	 * additional function
	 *
	 * widget slug
	 */
	private function rii_widget_slug() {
		return $this->widget_slug;
	}

	/**
	 * RII data
	 */
	public function rii_data_class() {
		include_once( plugin_dir_path( RII_PLUGIN_FILE ) . 'class/rii.class.php' );
	}

	/**
	 * ajax request
	 * make sure cache folder & rii.stats.json is rwrwrw
	 * url, lifetime cache, cache path file
	 */
	public function rii_data_json() {
		$data = new RII_Data( esc_url( 'http://radioislam.or.id/apl/list/pri2.xml' ), 60, plugin_dir_path( RII_PLUGIN_FILE ) . 'cache/rii.stats.json' );
		$data->generate_json();
	}

	/**
	 * widget assets
	 */
	public function rii_widget_assets() {
		wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.0.3', 'all' );
		wp_register_style( 'jquery-scrollbar', plugins_url( 'assets/css/vendor/jquery.scrollbar.min.css', RII_PLUGIN_FILE ),  array(), '3.0.8', 'all' );
		wp_register_style( $this->rii_widget_slug() . '-style', plugins_url( 'assets/css/rii.min.css', RII_PLUGIN_FILE ), array( 'font-awesome', 'jquery-scrollbar'  ), RII_PLUGIN_VERSION, 'all' );

		wp_register_script( 'jquery-jplayer', plugins_url( 'assets/js/vendor/jquery.jplayer.min.js', RII_PLUGIN_FILE ), array(), '2.9.2', false );
		wp_register_script( 'jquery-mousewheel', plugins_url( 'assets/js/vendor/jquery.mousewheel.min.js', RII_PLUGIN_FILE ), array(), '3.1.12', false );
		wp_register_script( 'jquery-scrollbar', plugins_url( 'assets/js/vendor/jquery.scrollbar.min.js', RII_PLUGIN_FILE ), array(), '3.0.8', false );
		wp_register_script( 'jquery-reverseorder', plugins_url( 'assets/js/vendor/jquery.reverseorder.min.js', RII_PLUGIN_FILE ), array(), RII_PLUGIN_VERSION, false );
		wp_register_script( $this->rii_widget_slug() . '-script', plugins_url( 'assets/js/rii.min.js', RII_PLUGIN_FILE ), array( 'jquery', 'jquery-jplayer', 'jquery-reverseorder', 'jquery-mousewheel', 'jquery-scrollbar' ), RII_PLUGIN_VERSION, false );
		// localize
		wp_localize_script( $this->rii_widget_slug() . '-script', 'rii', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);
		// enqueue
		wp_enqueue_style( $this->rii_widget_slug() . '-style' );
		wp_enqueue_script( $this->rii_widget_slug() . '-script' );
	}
}

/**
 * register widget action
 * PHP 5.2+: 
 */
add_action( 'widgets_init',
     create_function('', 'return register_widget("RII_Player_Widget");')
);