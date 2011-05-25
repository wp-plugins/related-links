<?php

if (!class_exists('Related_Links_Settings')) {
class Related_Links_Settings
{	
	/**
	 * Constructor
	 */
	public function Related_Links_Settings()
	{	
		add_action('admin_init', array($this, 'init_page'));
		add_action('admin_menu', array($this, 'add_page'));
	}
	
	/**
	 * Add default settings
	 */
	static function add_default_settings() 
	{
		$args = array('public' => true, 'show_ui' => true);
		$post_types = get_post_types($args);
		$option = array('types' => array());
		
		foreach($post_types as $index => $value)
		{
			$option['types'][$index] = $value;
		}
		
		add_option('related_links_settings', $option);
	}
	
	/**
	 * Remove default settings
	 */
	static function remove_default_settings() 
	{
		delete_option('related_links_settings');
	}

	/**
	 * Register our settings. Add the settings section, and settings fields
	 */
	public function init_page()
	{
		register_setting('related_links_settings', 'related_links_settings');
		add_settings_section('post_types_section', __('Types', 'related_links'), array($this, 'create_post_types_section'), __FILE__);
		add_settings_field('post_types_checkboxes', __('Show related links types:', 'related_links'), array($this, 'create_post_types_checkboxes'), __FILE__, 'post_types_section');
	}
	
	public function create_post_types_section() 
	{
		?>
		<p><?php _e( 'Select which types of related links you want to show in the "Related Links" box located on the writing pages', 'related_links' ); ?>.</p>
		<?php
	}

	public function create_post_types_checkboxes() 
	{
		$options = get_option('related_links_settings');
		$args = array('public' => true, 'show_ui' => true);
		$post_types = get_post_types($args);
		
		// add all link types that have a gui
		foreach($post_types as $post_type)
		{
			$post_type_object = get_post_type_object( $post_type );
			
			?>
			<label><input name="related_links_settings[types][<?php echo $post_type; ?>]" value="<?php echo $post_type; ?>" type="checkbox" <?php if($options['types'] && $options['types'][$post_type]) { ?> checked="checked"<?php } ?> /> <?php echo $post_type_object->label; ?></label><br />
			<?php
		}
	}
			
	/**
	 * Add sub page to the Settings Menu
	 */
	public function add_page() 
	{
		add_options_page('Related Links Page', 'Related Links', 'administrator', __FILE__, array($this, 'create_page_content'));
	}
	
	/**
	 * Add the page structure to the sub page
	 */
	public function create_page_content() 
	{
		?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
				<h2>Related Links Settings</h2>
				<form action="options.php" method="post">
				<?php settings_fields('related_links_settings'); ?>
				<?php do_settings_sections(__FILE__); ?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}
}
?>