jQuery(document).ready(function($) {
		
	// add filtering to the search field
	jQuery("#related-links-searchfield").change(function() {
		var filter = jQuery(this).val();
		if(filter) {
			// this finds all links in a list that contain the input,
			// and hide the ones not containing the input while showing the ones that do
			jQuery("#related-links-list").find("a:not(:Contains(" + filter + "))").parent().hide();
			jQuery("#related-links-list").find("a:Contains(" + filter + ")").parent().show();
		} else {
			jQuery("#related-links-list").find("li").show();
		}
		
		return false;
	}).keyup(function() {
		// fire the above change event after every letter
		jQuery(this).change();
	});
		
	// custom css expression for a case-insensitive contains()
	jQuery.expr[':'].Contains = function(a,i,m){
		return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	};
	
	// add a link to the list
	jQuery("#related-links-list a").live("click", function(event) {
		var id = jQuery(this).attr("href").substr(1);
		var title = jQuery(this).attr("title");
		var type = jQuery(this).parent().find("span").text();
		
		jQuery(this).addClass("selected");
				
		if (jQuery("#related-links-selected-" + id).length == 0) {
			jQuery("#related-links-selected ul").prepend('<li class="related-links-selected menu-item-handle" id="related-links-selected-' + id + '"><input type="hidden" name="related_links[posts][]" value="' + id + '" /><span class="selected-title">' + title + '</span><span class="selected-right"><span class="selected-type">' + type + '</span><a href="#" class="selected-delete">Delete</a></span></li>');
		}
		
		return false;
	});
	
	// add a custom link to the list
	jQuery("#related-links-custom-submit").click(function(event) {
		var id = "custom_" + (new Date() - 0);
		var title = jQuery("#related-links-custom-label").val();
		var url = jQuery("#related-links-custom-url").val();
		var type = "Custom";

		if(title == jQuery("#related-links-custom-label").attr("title")) {
			title = "";
			
			return false;
		}
		
		if(url == jQuery("#related-links-custom-url").attr("title")) {
			url = "";
		}
		
		jQuery("#related-links-selected ul").prepend('<li class="related-links-selected menu-item-handle" id="related-links-selected-' + id + '"><input type="hidden" name="related_links[posts][]" value="' + id + '" /><input type="hidden" name="related_links[custom][' + id + '][]" value="' + title + '" /><input type="hidden" name="related_links[custom][' + id + '][]" value="' + url + '"/><span class="selected-title">' + title + '</span><span class="selected-right"><span class="selected-type">' + type + '</span><a href="#" class="selected-delete">Delete</a></span></li>');
		jQuery("#related-links-custom-label").val("").focus();
		jQuery("#related-links-custom-url").val("").blur();
		
		return false;
	});
	
	// remove a link from the list
	jQuery("#related-links-selected .selected-delete").live("click", function(event) {
		jQuery(this).parent().parent().css({
			"border": "1px solid #A82F00", 
			"background-color": "#F8ECE8", 
			"background-image": "none"
		});

		jQuery(this).parent().parent().fadeOut(400, function() {
			var id = jQuery("input", this).attr("value");
			
			jQuery("#in-related-links-" + id).removeClass("selected");
			jQuery(this).remove();
		});
		
		return false;
	});
	
	// open or close custom link box
	jQuery("#related-links-custom-addurl").click(function() {
		if($("#related-links-custom-content:visible").length > 0) {
			$("#related-links-custom-content").hide();
		} else {
			$("#related-links-custom-content").show();
		}
		
		return false;
	});
	
	// enable sorting
	jQuery("#related-links-selected ul").sortable();
	
	// placeholder text
	var name = 'related-links-textfield-placeholder';

	$('.' + name).each( function(){
		var $t = $(this), title = $t.attr('title'), val = $t.val();
		$t.data( name, title );

		if( '' == val ) $t.val( title );
		else if ( title == val ) return;
		else $t.removeClass( name );
	}).focus( function(){
		var $t = $(this);
		if( $t.val() == $t.data(name) )
			$t.val('').removeClass( name );
	}).blur( function(){
		var $t = $(this);
		if( '' == $t.val() )
			$t.addClass( name ).val( $t.data(name) );
	});	
	
	// load the posts links list in the metabox with ajax	
	var data = {
		action: "load_links_list"
	};
	
	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#related-links-list").empty();
		jQuery("#related-links-list").append(response);
	});
});