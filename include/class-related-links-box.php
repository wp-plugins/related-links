<?php
class Related_Links_Box
{
	/**
	 * Class properties
	 */
	private $db_model;

	
	/**
	 * Constructor
	 */
	public function Related_Links_Box()
	{
		global $wpdb;
		$this->db_model = $wpdb;
		
		// Set hooks
		add_action( 'admin_print_styles', array($this, 'add_styles_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
		add_action( 'save_post', array( $this, 'save_box_data' ) );
	}
	
	/**
	 * Add a styles and scripts for the box
	 */
	public function add_styles_scripts()
	{
		wp_enqueue_style('related-links-styles', WP_PLUGIN_URL . '/related-links/css/style.css');
		wp_enqueue_script('related-links-scripts', WP_PLUGIN_URL . '/related-links/js/script.js', array('jquery'), '1.0');
	}
	
	/**
	 * Add the box content
	 */
	public function add_box()
	{
		// Adds a box to the post page.
		add_meta_box( 'related-links-box', __( 'Related Links', 'related_links' ), array( $this, 'create_box_content' ), 'post', 'side', 'low');
		add_meta_box( 'related-links-box', __( 'Related Links', 'related_links' ), array( $this, 'create_box_content' ), 'page', 'side', 'low');
	}
	
	/**
	 * Create the box content
	 */
	public function create_box_content()
	{
		global $post;
		
		// Use nonce for verification
  		wp_nonce_field( plugin_basename( __FILE__ ), 'related_links_nonce' );
		
		// Get the meta information	
		$meta = get_post_meta($post->ID, '_related_links', true);
		
		// Read the settings to know which post types we should read
		$options = get_option('related_links_settings');
		$post_types = "'" . implode("', '", $options) . "'";

		// Grab links
		$sql = "
			SELECT 
				post_title, ID, post_type
			FROM
				{$this->db_model->posts}
			WHERE
				post_status = 'publish'
			AND
				post_type 
			IN 
				($post_types)
			ORDER BY 
				post_type, post_title ASC
			";
			
		// start the output
		$links = $this->db_model->get_results( $sql );
		$post_type = '';
		
		// add the base structure

		// add the tab nav
		if (empty($options))
		{
			?>
			<p>There is no link type enabled in the settings.</p>
			<?php
			return;
		}
		
		if (count($options) > 1)
		{
			?>
			<div id="related-links-types" class="categorydiv">
			<ul id="related-links-tabs" class="category-tabs">
			<?php
			
			foreach( $links as $link )
			{
				if ($link->post_type != $post_type)
				{		
					// add a new tab when the post type changed
					$post_type = $link->post_type;
					?>
					<li class="hide-if-no-js"><a href="#related-links-content-<?php echo $post_type; ?>" tabindex="3"><?php echo ucfirst($post_type); ?></a></li>
					<?php
				}
			}
			
			// close the tab nav
			?>
			</ul>
			<?php
		}
		
		// add the content by post type
		$post_type = '';
		
		foreach( $links as $link )
		{
			if ($link->post_type != $post_type)
			{
				if($post_type != '') 
				{
					// close the previous content div
					?>
					</ul>
					</div>
					<?php
				}
				
				$post_type = $link->post_type;
				
				// add a new content div
				?>
				<div id="related-links-content-<?php echo $post_type; ?>" class="tabs-panel">
				<ul id="related-links-checklist-<?php echo $post_type; ?>" class="related-links-checklist form-no-clear">
				<?php
			}
			
			// add the input and title
			?>
			<li id="related-links-<?php echo $link->ID; ?>">
				<label class="selectit">
					<input type="checkbox" value="<?php echo $link->ID; ?>" name="related_links[<?php echo $link->ID; ?>]" id="in-related-links-<?php echo $link->ID; ?>" <?php if($meta[$link->ID]) { ?>checked="checked"<?php } ?>/> <?php echo $this->truncate( $link->post_title, 30 ); ?>
				</label>
			</li>
			<?php
		}
		
		// close all
		?>
		</ul>
		</div>
		<?php
		
		if (count($options) > 1)
		{
			?>
			</div>
			<?php
		}
	}
	
	/**
	 * Save the box content
	 */
	public function save_box_data( $post_id )
	{		
		// verify this came from the our screen and with 
		// proper authorization, because save_post can be 
		// triggered at other times
		if ( !wp_verify_nonce( $_POST['related_links_nonce'], plugin_basename( __FILE__ ) )) {
			return $post_id;
		}
  
  		// verify if this is an auto save routine. If it is 
  		// our form has not been submitted, so we dont want 
  		// to do anything
  		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
  		{
    		return $post_id;
    	}
  		
  		// Check permissions
		if ( $_POST['post_type'] ==  'page' ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		} 
		else 
		{
			if ( !current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
		// OK, we're authenticated: Now we need to find and 
		// save the data.
		$data = $_POST['related_links'];		
		
		// save, update or delete the custom field of the post
		if(empty($data))
		{
			delete_post_meta( $post_id, '_related_links' );
		}
		else
		{
			add_post_meta( $post_id, '_related_links', $data, true ) or update_post_meta( $post_id, '_related_links', $data );
  		}
  	}

	/**
	 * Truncate the text when a defined 
	 * character length is overpassed
	 */
	public function truncate( $str, $length )
	{
		if ( strlen( $str ) > $length ) 
		{
			$str = substr($str, 0, $length);
			$str .= ' ...';
			return $str;
		} 
		else 
		{
			return $str;
		}
	}	
}
?>