jQuery(document).ready(function($) {
	jQuery("#related-links-types .tabs-panel").each(function(index, element) {
		if (index > 0) {
			jQuery(element).css("display", "none");
		}
	});
	
	jQuery("#related-links-tabs li").each(function(index, element) {
		jQuery(element).removeClass("hide-if-no-js");
		
		if (index == 0) {
			jQuery(element).addClass("tabs");
		}		
		
		jQuery("a", element).click(function(event) {
			var id = jQuery(this).attr("href");
			
			jQuery("#related-links-types .tabs-panel:visible").css("display", "none");
			jQuery("#related-links-types").find(id).css("display", "");
						
			jQuery("#related-links-tabs li.tabs").removeClass("tabs");
			jQuery(this).parent("li").addClass("tabs");
			
			return false;
		});
	});
});