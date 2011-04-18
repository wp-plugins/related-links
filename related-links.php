<?php

/*
 * Plugin Name: Related Links
 * Plugin URI: http://wordpress.org/extend/plugins/related-links/
 * Description: Allows to easily access links to your other posts and pages through a widget.
 * Version: 1.0.1
 * Author: Insofern
 * Author URI: http://www.insofern.ch
 *
 * Copyright (C) 2011 Insofern
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
 
 
// ------------------------------------------
// backend
// ------------------------------------------


include_once('include/class-related-links-settings.php');
include_once('include/class-related-links-box.php');

/**
 * Add default settings
 */
function related_links_add_default_settings() 
{
	$args = array('public' => true, 'show_ui' => true);
	$post_types = get_post_types($args);
	
	foreach($post_types as $index => $value)
	{
		$db_type = 'type_' . $index;
		$post_types[$db_type] = $value;
	}
	
	update_option('related_links_settings', $post_types);
}

/**
 * Remove default settings
 */
function related_links_remove_default_settings() 
{
	delete_option('related_links_settings');
}

register_activation_hook(__FILE__, 'related_links_add_default_settings');
register_deactivation_hook(__FILE__, 'related_links_remove_default_settings');

// initialize objects
$Related_Links_Settings = new Related_Links_Settings();
$Related_Links_Box = new Related_Links_Box();


// ------------------------------------------
// frontend
// ------------------------------------------


/**
 * Get a list of related links.
 *
 * @param $post_id int (optional) The post id for which you want to retreive the links, if null it will pull from global $post.
 * @param $post_type string (optional) Filter by all registered post types, if null it will pull all post types.
 * @return array The array is keyed.
 *
 * Example retrieve links from all post types:
 *
 * 	<?php $related_links = get_related_links(); ?>
 *	<ul>
 * 		<?php foreach ($related_links as $link): ?>
 *		<li><a href="<?php echo $link["url"]; ?>"><?php echo $link["type"]; ?>: <?php echo $link["title"]; ?></a></li>
 *		<?php endforeach; ?>
 *	</ul>
 *
 * Example retrieve only links from "page" post type:
 *
 * 	<?php $related_links = get_related_links( "page" ); ?>
 *	<ul>
 * 		<?php foreach ($related_links as $link): ?>
 *		<li><a href="<?php echo $link["url"]; ?>"><?php echo $link["type"]; ?>: <?php echo $link["title"]; ?></a></li>
 *		<?php endforeach; ?>
 *	</ul>
 */


function get_related_links( $post_type = null, $post_id = null )
{
	global $post;
	
	if ($post_id === null || $post_id == '') 
	{
		$post_id = $post->ID;
	}
	
	// Get the meta information	
	$meta = get_post_meta($post_id, '_related_links', true);
	$values = array();
	
	// Parse it
	if(!empty($meta))
	{
		foreach($meta as $id) 
		{
			if( $post_type == get_post_type($id) || $post_type === null || $post_type == '')
			{
				$values[] = array('title' => get_the_title($id), 'url' => get_permalink($id), 'type' => get_post_type($id));
			}
		}
	}

	return $values;
}

?>
