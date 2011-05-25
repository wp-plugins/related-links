=== Related Links ===
Contributors: chabis
Donate link: http://www.insofern.ch/
Tags: related, deep, internal, link, post, page, selection
Requires at least: 3.0
Tested up to: 3.1
Stable tag: trunk

Allows to easily access links to your other posts and pages through a widget.

== Description ==

Related Links gives you the possibility to place in your current post a link to another post, page or any custom post-type. The plugin adds a new metabox to the writing page with a list of all links. The link or multiple links are selected manually.

The plugin is very useful if you plan to use a portfolio plugin ex. [Simple Portfolio](http://wordpress.org/extend/plugins/simple-portfolio/ "Manage your portfolio projects easily and use them everywhere you like.") http://wordpress.org/extend/plugins/simple-portfolio/ and at the same time maintainig a blog. A blog post could then be linked quite easy to a project or multiple projects. 

Features:

* Shows a list of links in a widget on the writing page
* Multiple links can be selected
* Link order can be changed
* External URLs can be added
* Search field to quickly find a link
* Works with custom post-types
* Set which post-types should be shown in the widget
* Simple theme integration with `get_related_links($post_type, $post_id)`

== Installation ==

1. Upload the `related-links` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Set in the link types in `Related Links` under the `Settings` menu in WordPress.
4. Place `<?php get_related_links(); ?>` in your templates.

== Frequently Asked Questions ==

= How do I show the links in my theme? =

With the `get_related_links()` function. This will return an array containing the links data of the current post. You have to echo them in your theme. A simple example that show a list of all links:

`<?php $related_links = get_related_links(); ?>
<ul>
	<?php foreach ($related_links as $link): ?>
		<li><a href="<?php echo $link["url"]; ?>"><?php echo $link["type"]; ?>: <?php echo $link["title"]; ?></a></li>
		<?php endforeach; ?>
</ul>`

= What are the properties returned by the `get_related_links()` function?

the `get_related_links()` returns an array containing every related link. when you loop through this array every link consists of another array with the following keys:

`id`: the id of the linked post or `null` for custom links
`url`: the permalink of the linked post or custom link
`title`: the title of the linked post or custom link
`type`: the post type of the linked post or `null` for custom links

= How do I only show the links for a certain post_type in my theme? =

Set the `$post_type` in `get_related_links($post_type)` to `"post"`, `"page"` or any custom post-type. A simple example that show a list of links:

`<?php $related_links = get_related_links("page"); ?>
<ul>
	<?php foreach ($related_links as $link): ?>
		<li><a href="<?php echo $link["url"]; ?>"><?php echo $link["type"]; ?>: <?php echo $link["title"]; ?></a></li>
		<?php endforeach; ?>
</ul>`

= How do I show the related links of another post (not the current one)? =

Set the `$post_id` in `get_related_links(null, $post_id)` to the id of the post. A simple example that show a list of links:

`<?php $related_links = get_related_links(null, 1); ?>
<ul>
	<?php foreach ($related_links as $link): ?>
		<li><a href="<?php echo $link["url"]; ?>"><?php echo $link["type"]; ?>: <?php echo $link["title"]; ?></a></li>
		<?php endforeach; ?>
</ul>`

== Screenshots ==

1. Related links metabox on the post page.
3. Settings page.

== Changelog ==

= 1.5 =
A lot of changes for this version:
* Links order can be changed with drag and drop
* Search field to quickly find a link by name
* External URLs can be added
* Added an `id` property to the get_related_links() function
* New meta data structure but legacy support for older plugin versions is added
* Checking if the post really exists before it is added to the output
* Better list loading through ajax

= 1.0.1 =
* The widget content list is now scrollable

= 1.0 =
Initial release