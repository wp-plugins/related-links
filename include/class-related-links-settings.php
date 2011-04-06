<?php

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
	 * Register our settings. Add the settings section, and settings fields
	 */
	public function init_page()
	{
		register_setting('related_links_settings', 'related_links_settings');
		add_settings_section('post_types_section', 'Types', array($this, 'create_post_types_section'), __FILE__);
		add_settings_field('post_types_checkboxes', 'Show related links types:', array($this, 'create_post_types_checkboxes'), __FILE__, 'post_types_section');
	}
	
	public function create_post_types_section() 
	{
		?>
		<p>Select which types of related links you want to show in the "Related Links" box located on the writing pages.</p>
		<?php
	}

	public function create_post_types_checkboxes() 
	{
		$options = get_option('related_links_settings');
		$args = array('public' => true, 'show_ui' => true);
		$post_types = get_post_types($args);
		
		foreach($post_types as $post_type)
		{
			$db_type = 'type_' . $post_type;
			?>
			<label><input id="post_types_checkboxes" name="related_links_settings[<?php echo $db_type; ?>]" value="<?php echo $post_type; ?>" type="checkbox"<?php if($options[$db_type]) { ?> checked="checked"<?php } ?> /> <?php echo ucfirst($post_type); ?></label><br />
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
?>