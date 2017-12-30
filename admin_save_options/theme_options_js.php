<?php
if( !isset($_GET['activated'])) {
header("content-type: application/javascript");
};
global $of_sequoia;
//
//
// THEME OPTIONS ADDITION
$use_nice_scroll	= isset($of_sequoia['use_nice_scroll']) ? $of_sequoia['use_nice_scroll'] : true;
$use_nice_scroll_menus= isset($of_sequoia['use_nice_scroll_menus']) ? $of_sequoia['use_nice_scroll_menus'] : true;

$use_preloader		= $of_sequoia['use_preloader'];

echo'
var $c = jQuery.noConflict();

$c(document).ready(function() {	
	
	//var ajaxurl = "wp-admin/admin-ajax.php";
	var ajaxurl = "'. admin_url("admin-ajax.php") .'";
	
	/**
	 * AJAX - PRODUCT CATEGORIES
	 * 
	 */	
	
	$c(document).on("click", "a.ajax-products", function(e) {

		e.preventDefault();
		
		var aLink			= $c(this);
		var parentblock		= aLink.parent().parent().parent();
		var parentVars		= parentblock.find(".varsHolder");
		var block			= parentblock.find(".content-block");
		var cat_content		= block.find(".category-content");
		var cat_title		= block.find(".cat-title");
		var load_anim		= parentblock.find(".loading-animation");
		
		
		var t_ID			= aLink.attr("data-id");
		var taxonomy		= parentVars.attr("data-tax");
		var tax_name		= aLink.find(".box-title").text();
		var block_id		= parentVars.attr("data-block_id");
		var ptype			= parentVars.attr("data-ptype");
		var totitems		= parentVars.attr("data-totitems");
		var data_filters	= parentVars.attr("data-filters");
		var img				= parentVars.attr("data-img");
		var shop_quick		= parentVars.attr("data-shop_quick");
		var shop_buy_action	= parentVars.attr("data-shop_buy_action");
		var shop_wishlist	= parentVars.attr("data-shop_wishlist");
		var enter_anim		= parentVars.attr("data-enter_anim");
		var no_slider_grid	= parentVars.attr("data-no_slider_grid");
		var zoom_button		= parentVars.attr("data-zoom");
		var link_button		= parentVars.attr("data-link");
		
		// START ACTION:
		
		// 1 - REMOVE ALL CLASSES "CURRENT" AND SHOW LOADING ANIMATION:
		$c("a.ajax-products").parent().removeClass("current"); // remove ALL "current" classes
		
		load_anim.slideDown(500);
		
		// 2 - HIDE THE CAT TITLE:		
		cat_title.delay(0).slideUp(
			500, 
			function() {
				
				$c(this).find(".wrap").html("<h3 class=\"ajax-category\">" + tax_name + "</h3>");
				/* 
				block.stop(false,true).slideUp( 500, function() {});
				*/
				$c(this).find(cat_content).empty();
				aLink.parent().addClass("current");
			}
		);
		
		
		$c.ajax({
			type: "POST",
			url: ajaxurl,
			data: {"action": "load-filter", termID: t_ID, tax: taxonomy, post_type: ptype, total_items: totitems, filters: data_filters,  img_format: img,  shop_quick: shop_quick,  shop_buy_action: shop_buy_action, shop_wishlist: shop_wishlist, enter_anim: enter_anim, no_slider_grid: no_slider_grid, zoom_button: zoom_button, link_button: link_button },
			success: function(response) {
				
				// FILL UP WITH NEW CONTENT
				cat_content.html($c.trim(response));					
				
				load_anim.slideUp(500);
				
				if(tax_name) {
					cat_title.slideDown(500);
					var del = 500;
				}else{
					var del = 0;
				}
				
				// SLIDE BACK DOWN:
				/* 
				block.delay(del).slideDown("slow" , function() { });
				*/
				
				/* ****  SUPPORT FOR PLUGINS AND FUNCTIONS AFTER AJAX LOAD ***** */
				
				// HOVER EFFECT ON PROD. IMAGES:
				$c(function () {
		
					var itemImg = $c(".item-content").find(".item-img");
					
					$c("html").removeClass("csstransforms3d");
					
					itemImg.find(".back").css("opacity", 0 );
					
					itemImg.hover(
						function (event) {
							$c(this).find(".back").stop().animate({opacity: 1}, 500, "easeOutCubic");
						},
						function (event) {
							$c(this).find(".back").stop().animate({opacity: 0 }, 500, "easeOutCubic");			
						}
					);

				});
				
				// PRETTYPHOTO :
				$c("a[data-rel]").each(function() {
					$c(this).attr("rel", $c(this).data("rel"));
				});		
				$c("a[class^=\"prettyPhoto\"], a[rel^=\"prettyPhoto\"]").prettyPhoto(
					{	theme: "light_square",
						slideshow:5000, 
						social_tools: "",
						autoplay_slideshow:false,
						deeplinking: false
					});
				
				// OWL CAROUSEL
				 
				if( cat_content.hasClass("contentslides") ) {
				
					var config = cat_content.prev("input.slides-config");
					
					
					var cs_navig		= config.attr("data-navigation"),
						cs_pagin		= config.attr("data-pagination"),
						cs_auto			= config.attr("data-auto"),
						cs_transition	= config.attr("data-trans"),
						sc_desk			= config.attr("data-desktop"),			
						sc_desksmall	= config.attr("data-desktop-small"),			
						sc_tablet		= config.attr("data-tablet"),			
						sc_mobile		= config.attr("data-mobile");
					
					cat_content.owlCarousel({
								items 				: sc_desk ? sc_desk : 4, 			// above 1400px browser width
								itemsDesktop 		: [1400, sc_desk ? sc_desk : 4], 	// between 1400px and 1025px
								itemsDesktopSmall 	: [1024, sc_desksmall ? sc_desksmall : 3], 	// betweem 1024px and 769px
								itemsTablet			: [768, sc_tablet ? sc_tablet : 2], 	// between 768 and 0;
								itemsMobile 		: [480, sc_mobile ? sc_mobile : 1],	// itemsMobile
								autoPlay			: cs_auto == 0 ? false : true,
								stopOnHover			: true,
								navigation			: cs_navig == 1 ? true : false,
								pagination			: cs_pagin == 1 ? true : false,
								addClassActive		: false,
								autoHeight 			: true,
								mouseDrag			: true,
								rewindNav			: true,
								paginationNumbers	: false,
								navigationText		: ["&#xe16d;","&#xe170;"],
							});
					cs_navig = cs_pagin = cs_auto = "";
					
				}
				
				if( enter_anim !== "none") {
					$c(document).anim_waypoints(block_id,enter_anim);
				}
				$c.waypoints("refresh");
				
				$c(document).foundation();
	
				return false;
				
			}, // end success
			error: function () {
				alert("Ajax fetching or transmitting data error");
			}
		});
			

	}); ';
/**
 *	AJAX - POSTS and PORTFOLIO CATEGORIES
 *
 */
 
echo '
	$c(document).on("click", "a.ajax-posts", function(e) {

		e.preventDefault();
		
		var aLink			= $c(this);
		var parentblock		= aLink.parent().parent().parent();
		var parentVars		= parentblock.find(".varsHolder");
		var block			= parentblock.find(".content-block");
		var cat_content		= block.find(".category-content");
		var cat_title		= block.find(".cat-title");
		var load_anim		= parentblock.find(".loading-animation");
		
		var t_ID			= aLink.attr("data-id");
		var taxonomy		= parentVars.attr("data-tax");
		var tax_name		= aLink.find(".term").text();
		var block_id		= parentVars.attr("data-block_id");
		var ptype			= parentVars.attr("data-ptype");
		var totitems		= parentVars.attr("data-totitems");
		var feat			= parentVars.attr("data-feat");
		var img				= parentVars.attr("data-img");
		var custom_img_w	= parentVars.attr("data-custom-img-w");
		var custom_img_h	= parentVars.attr("data-custom-img-h");
		var icons			= parentVars.attr("data-icons");
		var taxmenu_style	= parentVars.attr("data-taxmenustlye");
		var enter_anim		= parentVars.attr("data-enter_anim");
		var no_slider_grid	= parentVars.attr("data-no_slider_grid");
		var zoom_button		= parentVars.attr("data-zoom");
		var link_button		= parentVars.attr("data-link");
		
		
		// START ACTION:
		
		// 1 - remove all classes "current":
		$c("a.ajax-posts").parent().removeClass("current"); // remove all "current" classes
		
		load_anim.slideToggle(500);
		// 2 - hide the cat title:		
		cat_title.delay(500).slideUp(
			500, 
			function() {
				
				$c(this).find(".wrap").html("<h3 class=\"ajax-category\">" + tax_name + "</h3>");
				/* 
				block.stop(false,true).slideUp( 500, function() { });
				*/
				$c(this).find(cat_content).empty();
				aLink.parent().addClass("current");
			}
		);
		
		$c.ajax({
				type: "POST",
				url: ajaxurl,
				data: {"action": "load-filter2", termID: t_ID, tax: taxonomy, post_type: ptype, total_items: totitems, only_featured: feat,  img_format: img, custom_img_width: custom_img_w , custom_img_height: custom_img_h, display_icons: icons, tax_menu_style: taxmenu_style, block_id: block_id, enter_anim: enter_anim, no_slider_grid: no_slider_grid, zoom_button: zoom_button, link_button: link_button  },
				success: function(response) {

					// FILL UP WITH NEW CONTENT
					cat_content.html($c.trim(response));						
					
					
					load_anim.slideToggle(500);
					
					if(tax_name) {
						cat_title.slideDown(500);
						var del = 500;
					}else{
						var del = 0;
					}
									
					// SLIDE BACK DOWN:
					/* 
					block.delay(del).slideDown("slow" , function() { });
					*/
					
					/*  SUPPORT FOR PLUGINS AND FUNCTIONS AFTER AJAX LOAD */
										
					// HOVER EFFECT ON ITEMS IMAGES:
					$c(function () {
			
						var itemImg = $c(".item-images").find(".item-img");
						
						$c("html").removeClass("csstransforms3d");
						
						itemImg.find(".back").css("opacity", 0 );
						
						itemImg.hover(
							function (event) {
								$c(this).find(".back").stop().animate({opacity: 1}, 500, "easeOutCubic");
							},
							function (event) {
								$c(this).find(".back").stop().animate({opacity: 0 }, 500, "easeOutCubic");			
							}
						);

					});
					
					
					// PRETTYPHOTO :
					$c("a[data-rel]").each(function() {
						$c(this).attr("rel", $c(this).data("rel"));
					});		
					$c("a[class^=\"prettyPhoto\"], a[rel^=\"prettyPhoto\"]").prettyPhoto(
						{	theme: "light_square",
							slideshow:5000, 
							social_tools: "",
							autoplay_slideshow:false,
							deeplinking: false,
							ajaxcallback: function(){
								$c("video,audio").mediaelementplayer();
							}
						});
					
					// OWL CAROUSEL :
					if( cat_content.hasClass("contentslides") ) {
					
						var config = cat_content.prev("input.slides-config");
						
						var cs_navig		= config.attr("data-navigation"),
							cs_pagin		= config.attr("data-pagination"),
							cs_auto			= config.attr("data-auto"),
							cs_transition	= config.attr("data-trans"),
							sc_desk			= config.attr("data-desktop"),			
							sc_desksmall	= config.attr("data-desktop-small"),			
							sc_tablet		= config.attr("data-tablet"),			
							sc_mobile		= config.attr("data-mobile");
						
						cat_content.owlCarousel({
									items 				: sc_desk ? sc_desk : 4, 			// above 1400px browser width
									itemsDesktop 		: [1400, sc_desk ? sc_desk : 4], 	// between 1400px and 1025px
									itemsDesktopSmall 	: [1024, sc_desksmall ? sc_desksmall : 3], 	// betweem 1024px and 769px
									itemsTablet			: [768, sc_tablet ? sc_tablet : 2], 	// between 768 and 0;
									itemsMobile 		: [480, sc_mobile ? sc_mobile : 1],	// itemsMobile
									autoPlay			: cs_auto == 0 ? false : true,
									stopOnHover			: true,
									navigation			: cs_navig == 1 ? true : false,
									pagination			: cs_pagin == 1 ? true : false,
									addClassActive		: false,
									autoHeight 			: true,
									mouseDrag			: true,
									rewindNav			: true,
									paginationNumbers	: false,
									navigationText		: ["&#xe16d;","&#xe170;"]
								});
						cs_navig = cs_pagin = cs_auto = "";
					}
					/**
					 *	POST META and NAV TOGGLER: 
					 *
					 */
						
					$c(".post-meta-bottom .date_meta, .post-meta-bottom .user_meta, .post-meta-bottom .permalink, .post-meta-bottom .cat_meta ,.post-meta-bottom .tag_meta, .post-meta-bottom .comments_meta, .nav-single a").hover(function() {
							
							var parent = $c(this).parent();
							var hoverBox = $c(this).find(".hover-box");
							var leftPos = - ( hoverBox.outerWidth(true)/2 - $c(this).outerWidth(true)/2 );
							
							if( $c(this).hasClass("left") || parent.hasClass("left") ) {
								hoverBox.css("left", 0);
							}else if( $c(this).hasClass("right") || parent.hasClass("right") ) {
								hoverBox.css("left", "auto").css("right", 0);
							}else{
								hoverBox.css("left", leftPos);
							}
							
							hoverBox.fadeToggle(400);
						},
						function () {
							var hoverBox = $c(this).find(".hover-box");

							hoverBox.fadeToggle(150);
						}
					
					);
					
					if( enter_anim !== "none") {
						$c(document).anim_waypoints_posts(block_id, enter_anim);
					}
					
					$c.waypoints("refresh");
					
					$c(document).foundation();
					
					return false;
					
				}, // end success
				error: function () {
					alert("Ajax fetching or transmitting data error");
				}
			});
			
			
			
	});
	
	$c(document).on("click", "a.quick-view", function(e) {

		e.preventDefault();
		
		$c("body").append("<div class=\"qv-overlay\"><div class=\"qv-holder woocommerce\" id=\"qv-holder\"><div class=\"loading-animation\">"+ wplocalize_options.loading_qb + "</div></div></div>");
		
		var aLink		= $c(this);
		var	prod_ID		= aLink.attr("data-id");
		var	lang		= aLink.attr("data-lang");
		var	qv_holder	= $c("#qv-holder");
		var qv_overlay	= $c(".qv-overlay");
		var	images		= qv_holder.find(".images");
		var load_anim	= qv_holder.find(".loading-animation");
		
		qv_overlay.fadeIn();
		
		qv_holder.fadeIn();
		
		// REMOVING ACTIONS:
		qv_holder.parent().on("click", function(e) {
			if(e.target == this) $c(this).fadeOut("slow", function() { this.remove(); });
		});
		
		$c.ajax({
		
			type: "POST",
			url: ajaxurl,
			data: { "action": "load-filter3", productID: prod_ID, lang: lang  },
			success: function(response) {
				
				load_anim.fadeToggle(500);
				
				// fill with response from server:
				qv_holder.html(response);
				
				
				// add QV window remover:
				qv_holder.append("<div class=\"message-remove\"></div>");
						
				// REMOVING ACTIONS:
				qv_holder.find(".message-remove").on("click", function(e) {
					qv_overlay.fadeOut("slow", function() { qv_overlay.remove(); });
				});
				
			}, // end success
			error: function () {
				alert("Ajax fetching or transmitting data error");
			}
		});

	});

	/**
	 *	INFINITE LOAD:
	 *
	 */
	
	var element	= $c("section.infinite-posts");
	
	if( element.length ) {
	
		$c( window ).scroll(function() {
			
			checkScroll( element );
		
		});

		
		var loading	= false;
		function checkScroll (element){
			
			var	elementOffset	= element.offset().top,
				elementHeight	= element.height(),
				elementEnd		= elementOffset + elementHeight;
					
			if($c(window).scrollTop() + $c(window).height()  > elementEnd ) {			
				
				if(loading) return true;
				
				element.append("<div class=\"inf-loading-animation\"></div>");
				
				if(!loading) {
					loading=1;
					var params = {"offset":post_offset,"action":"add_posts"}
					$c.post( ajaxurl , params, function(data){
						
						$c(".inf-loading-animation").fadeOut();
						
						if(data){
							
							post_offset += increment ;

							loading=0;
							element.append(data);
													
							$c.waypoints("refresh");
						}

					});//now load more content
			
				}	
			}
		}	
	}

/**
 * end AJAX
 *
 */
 
';
	


if( $use_nice_scroll ) {

	echo '
	if( !window.isMobile ) {
		$c("body").smoothScroll();
	}
	';
}

if( $use_nice_scroll_menus ) {

	echo '
	/**
	 *	NICESCROLL (FOR SIDE MENU and MEGA MENUS) - smooth page and elements scrolling
	 */

		if( $c.fn.niceScroll && !window.isMobile ) {
			$c("#site-menu.vertical, .vertical-mega").niceScroll({
				horizrailenabled	: false,
				cursorwidth			: 5,
				cursoropacitymax	: 0.7,
				hidecursordelay		: 5
			});
		}
	';

}

if( $use_preloader ) {
	
	echo'
	/**
	 *	PAGE PRELOADER:
	 *
	 */
	
	$c(window).load(function(){

	  $c("#dvLoading").fadeOut(500);
	  
	  var linkLocation = "";
		
		$c("a").click(function(event){
			
			var newPage = false;
			if( ! /#/.test(this.href) ) {
				newPage = true;
			}
			
			if( $c(this).hasAnyClass("product_type_simple item-zoom zoom add_to_wishlist button remove chosen-single") ||  /_blank/.test(this.target) ) {
				newPage = false;
			}
			
			if( newPage ) {
				event.preventDefault();
				linkLocation = $c(this).attr("href");
				$c("#dvLoading").fadeIn(500, redirectPage);
				
			}
	   });
			 
		function redirectPage() {
			
			window.location = linkLocation;
		}
	  
	});
	';
}

echo' }) // end (document).ready; ';
?>