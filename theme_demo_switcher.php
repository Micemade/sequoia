<div id="style_switcher" class="demo-controls">

	<?php	
	if(!empty($_COOKIE['_theme'])) $style = $_COOKIE['_theme'];
        else $style = "none";
		
	$themes_folder = get_theme_root_uri();
	$my_theme = wp_get_theme();
	$theme_folder = $themes_folder . '/sequoia.' . $my_theme->Version;
	
	$dl =  get_template_directory_uri() .'/admin/images';
	?>
	
	<div id="toggle" class="icon-settings" title="Demo options"></div>
	
	<?php $buy_link = "http://themeforest.net/item/sequoia-ecommerce-and-multipurpose-wp-theme/8406654?ref=micemade"; ?>
	
	<div id="buy"  class="icon-cart"><a href="<?php echo esc_url($buy_link);?>" title="Buy this item on Theme forest"></a></div>
	
	
	<?php
	/* USER ADDED GOOGLE FONTS */
	$added_fonts = apply_filters( 'sequoia_options', 'added_google_fonts', '' );
		
	// SANITIZATION:
	$added_fonts = preg_replace('/[^a-zA-Z0-9, ]/','',$added_fonts ) ;// remove all but numbers, letters, spaces and comma
	$added_fonts = preg_replace('/\s+/', ' ', $added_fonts);// remove multiple spaces
	$added_fonts = str_replace(", ",",", $added_fonts ); // remove space after comma
						
	
	$add_fonts_array_simple = explode(",",$added_fonts );
	$add_fonts_array = array_combine(  $add_fonts_array_simple,  $add_fonts_array_simple );
	$fonts_array = apply_filters("as_google_fonts", $add_fonts_array);
	?>
	
	<h5>Titles and menus fonts</h5>
	<select name="titles-fonts" class="fonts-selector titles-fonts">
		<option>--Select font--</option>
		<?php foreach( $fonts_array as $font) {
			echo '<option>' . $font . '</option>';
		}
		?>
	</select>	
	
	<h5>Body fonts</h5>
	<select name="body-fonts" class="fonts-selector body-fonts">
		<option>--Select font--</option>
		<?php foreach( $fonts_array as $font) {
			echo '<option>' . $font . '</option>';
		}
		?>
	</select>	
	
	<hr>
	
	<h5>Header color</h5>
	<input type="text" id="header-color" name="header-color" class="input-color-picker head-backcolor" />
	
	<h5>Header font color</h5>
	<input type="text" id="head-fontcolor" name="head-fontcolor" class="input-color-picker head-fontcolor" />
	
	<h5>Site Background color<br /><small>visible only on higher resolutions</small></h5>
	<input type="text" id="site-color" name="site-color" class="input-color-picker site-color" />
		
	<h5>Body background color</h5>
	<input type="text" id="body-color" name="body-color" class="input-color-picker body-color" />
	
	<h5>Body font color</h5>
	<input type="text" id="body-fontcolor" name="body-fontcolor" class="input-color-picker body-fontcolor" />

	<h5>Links color</h5>
	<input type="text" id="body-linkcolor" name="body-linkcolor" class="input-color-picker body-linkcolor" />

	<p class="notice">This is just a very small part of theme options.<br />
	<strong>NOTE: global theme options can be overriden with page builder blocks settings.</strong>
	</p>


</div>

<?php 
/* if ( !wp_script_is( 'iris', 'enqueued' )) {

	//wp_register_script( 'wc-add-to-cart-variation', WP_PLUGIN_DIR . '/woocommerce/assets/frontend/add-to-cart-variation.min.js');
	wp_enqueue_script( 'iris' );
	
} */
?>


<script>
jQuery(document).ready(function($){
	/** Colorpicker Field
	----------------------------------------------- */
	function demo_colorpicker() {
		
		$('#style_switcher').find('.input-color-picker').each(function(){
			
			var $_this	= $(this),
				demoControls = $(this).parent(),
				header		= $('#site-menu, #main-nav-wrapper ul.navigation li ul, #secondary-nav ul.navigation li ul, #site-menu-mobile, #site-menu .widget_product_categories ul'),
				
				headerfont	= $('#site-menu, #site-menu a, #site-menu #social a, .header-cart-sequoia, #site-menu-mobile'),
				
				links		= $('a:link, a:visited, button, .button, .woocommerce .quantity .plus, .woocommerce .quantity .minus, .woocommerce #content .quantity .plus, .woocommerce #content .quantity .minus, .woocommerce-page .quantity .plus, .woocommerce-page .quantity .minus, .woocommerce-page #content .quantity .plus, .woocommerce-page #content .quantity .minus');
			
			$_this.wpColorPicker({
				width: 100,
				color: true,
				palettes: false,
				change : function(event, ui) {
				
					if( $_this.hasClass('head-backcolor') ) {
						header.css( 'background-color', ui.color.toString() );
						$('.stick-it-header').css( 'background-color', ui.color.toString() );
						$('.mega-clone').css( 'background-color', ui.color.toString() );
						$('.sub-clone').css( 'background-color', ui.color.toString() );
						$('.sub-clone li .sub-menu').css( 'background-color', ui.color.toString() );
						//$('.mobile-sticky.stuck').css( 'background-color', ui.color.toString() );
						
						header.css( 'opacity', 0.95).css('z-index', 1000);
						$('.mega-clone').css( 'opacity', 0.95);
						$('.sub-clone').css( 'opacity', 0.95);
						$('.sub-clone li .sub-menu').css( 'opacity', 0.95);
						
						//jss.remove('.menu-border:before');
						jss.set('.menu-border:before', {
							background:  ui.color.toString()
						});
						jss.set('.active-mega span', {
							borderRightColor:ui.color.toString()
						});
						
						
					}
					if( $_this.hasClass('head-fontcolor') ) {
						headerfont.css( 'color', ui.color.toString() );
						$('.header-cart-sequoia').css( 'color', ui.color.toString() );
						$('.mega-clone a, .mobile-sticky.stuck a').css( 'color', ui.color.toString() );
					}
					if( $_this.hasClass('site-color') ) {
						$('body').css( 'background-color', ui.color.toString() );
					}
					
					if( $_this.hasClass('body-color') ) {
						$('#page').css( 'background-color', ui.color.toString() );
						$('.product-filters-wrap').css( 'background-color', ui.color.toString() ).css('opacity',0.9);
						
						
						jss.set('.article-border:before, .block-title-border:before, .title-border:before, article .icon', {
							background:	ui.color.toString(),
							borderColor:ui.color.toString()
						});
						
						
					}
					if( $_this.hasClass('body-fontcolor') ) {
						$('body').css( 'color', ui.color.toString() );
						
						jss.set('.article-border:before, .block-title-border:before, .title-border:before, article .icon', {
							color:	ui.color.toString()
						});
					}
					
					if( $_this.hasClass('body-linkcolor') ) {
						links.css( 'color', ui.color.toString() );
					}
				}		
			
			});
		
		}); // end each

	}// end function demo_colorpicker()

	demo_colorpicker();
	
	/**
	 *	Google font select
	 *
	 */
	function addGoogleFont(FontName, el) {
		
		$(".google-"+el).remove();
		
		if( FontName ) {
			$("head").append("<link href='http://fonts.googleapis.com/css?family=" + FontName + ":300,400,600,700,800,400italic,700italic&subset=latin,latin-ext' rel='stylesheet' type='text/css' class='google-"+ el +"'>");
		}
	}

	
	
	$( "select.body-fonts" ).change(function() {
		
		var fontname 		= "";
		var fontname_link	= "";
		$( "select.body-fonts option:selected" ).each(function() {
			fontname = $(this).text();
			fontname_link = fontname.replace(/\ /g, "+");
		
		});
		
		var bodyFonts = 'body, #site-menu, site-menu-mobile, .mega-clone, .block-subtitle, .bottom-block-link a, .button, .onsale, .taxonomy-menu h4, button, input#s, input[type="button"], input[type="email"], input[type="reset"], input[type="submit"], input[type="text"], select, textarea, ul.post-portfolio-tax-menu li a';
		
		if( fontname !== '--Select font--') {

			addGoogleFont( fontname_link, 'body' );
			$( bodyFonts ).css( 'font-family', fontname );
		}else{
			$( bodyFonts ).css( 'font-family', null );
		}
	  });
	 //.trigger( "change" );
	  
	$( "select.titles-fonts" ).change(function() {
		
		var fontname 		= "";
		var fontname_link	= "";
		$( "select.titles-fonts option:selected" ).each(function() {
			fontname = $(this).text();
			fontname_link = fontname.replace(/\ /g, "+");
		
		});
		
		var titles = 'h1, h2, h3, h4, h5, h6, .billing_country_chzn, .chzn-drop, .navbar .nav, .price, footer ';
		
		if( fontname !== '--Select font--') {
			
			addGoogleFont( fontname_link, 'titles' );
			$( titles ).css( 'font-family', fontname );	
		}else{
			$( titles ).css( 'font-family', null );
		}
		
	  });
	  //.trigger( "change" );
	
	/**
	 *	DEMO STYLES LOADER.
	 *
	 */
		$("#style_switcher #dark-light a").click(function(){
				darkLightCSS(this);
				return false;
		});
		function darkLightCSS( selected_CSS ) {
			$.get( selected_CSS.href+'&async',function(data){	
				$('#light-version-css').attr('href', data + '.css');
			});
		}

		/** toggle show/hide demo options: */
		var switcher = false;
		$('#style_switcher #toggle').click(function() {
			if( !switcher ) {
				$(this).parent().animate({'right':-4},{'duration': 200, easing: 'easeInOutQuart'});
				switcher = true;
			}else{
				$(this).parent().animate({'right':-200},{'duration': 200, easing: 'easeInOutQuart'});
				switcher = false;
			}
		});
		
	/** END DEMO STYLES LOADER*/
/*
 * JSS v0.3 - JavaScript Stylesheets
 * https://github.com/Box9/jss
 *
 * Copyright (c) 2011, David Tang
 * MIT Licensed (http://www.opensource.org/licenses/mit-license.php)
 */
var jss = (function(undefined) {
    var adjSelAttrRgx = /((?:\.|#)[^\.\s#]+)((?:\.|#)[^\.\s#]+)/g;

	function getRules(sheet, selector) {
		var rules = sheet.cssRules || sheet.rules || [];
		var results = [];
        // Browsers report selectors in lowercase - TODO check how true this is cross-browser
		selector = selector.toLowerCase();
		for (var i = 0; i < rules.length; i++) {
			// IE8 will split comma-delimited selectors into multiple rules, breaking our matching
			var selectorText = rules[i].selectorText;
			// Note - certain rules (e.g. @rules) don't have selectorText
            if (selectorText && (selectorText == selector || selectorText == swapAdjSelAttr(selector))) {
            	results.push({
            		sheet: sheet,
            		index: i,
            		style: rules[i].style
            	});
            }
		}
		return results;
	}

	function addRule(sheet, selector) {
        var rules = sheet.cssRules || sheet.rules || [];
        var index = rules.length;
        if (sheet.insertRule) {
            sheet.insertRule(selector + ' { }', index);
        } else if (sheet.addRule) {
            sheet.addRule(selector, null, index);
        }
        return {
        	sheet: sheet,
        	index: index,
        	style: rules[index].style
        };
    };

    function removeRule(rule) {
        var sheet = rule.sheet;
        if (sheet.deleteRule) {
            sheet.deleteRule(rule.index);
        } else if (sheet.removeRule) {
            sheet.removeRule(rule.index);
        }
    }

	function extend(dest, src) {
		for (var key in src) {
			if (!src.hasOwnProperty(key))
				continue;
			dest[key] = src[key];
		}
		return dest;
	}

	function aggregateStyles(rules) {
		var aggregate = {};
		for (var i = 0; i < rules.length; i++) {
			extend(aggregate, declaredProperties(rules[i].style));
		}
		return aggregate;
	}

	function declaredProperties(style) {
		var declared = {};
		for (var i = 0; i < style.length; i++) {
			declared[style[i]] = style[style[i]];
		}
		return declared;
	}

	// IE9 stores rules with attributes (classes or ID's) adjacent in the opposite order as defined
    // causing them to not be found, so this method swaps [#|.]sel1[#|.]sel2 to become [#|.]sel2[#|.]sel1
    function swapAdjSelAttr(selector) {
        var swap = '';
        var lastIndex = 0;
            
        while ((match = adjSelAttrRgx.exec(selector)) != null) {
            if (match[0] === '')
            	break;
            swap += selector.substring(lastIndex, match.index);
            swap += selector.substr(match.index + match[1].length, match[2].length);
            swap += selector.substr(match.index, match[1].length);
            lastIndex = match.index + match[0].length;
        }
        swap += selector.substr(lastIndex);
        
        return swap;
    };

	var Jss = function(doc) {
        this.doc = doc;
        this.head = this.doc.head || this.doc.getElementsByTagName('head')[0];
        this.sheets = this.doc.styleSheets || [];
    };

    Jss.prototype = {
    	get: function(selector) {
    		return this.defaultSheet ? aggregateStyles(getRules(this.defaultSheet, selector)) : {};
    	},
    	getAll: function(selector) {
    		var properties = {};
    		for (var i = 0; i < this.sheets.length; i++) {
    			extend(properties, aggregateStyles(getRules(this.sheets[i], selector)));
    		}
    		return properties;
    	},
    	set: function(selector, properties) {
    		if (!this.defaultSheet) {
    			this.defaultSheet = this._createSheet();
    		}
    		var rules = getRules(this.defaultSheet, selector);
    		if (!rules.length) {
    			rules = [addRule(this.defaultSheet, selector)];
    		}
    		for (var i = 0; i < rules.length; i++) {
    			extend(rules[i].style, properties);
    		}
    	},
    	remove: function(selector) {
    		if (!this.defaultSheet)
    			return;
    		var rules = getRules(this.defaultSheet, selector);
    		for (var i = 0; i < rules.length; i++) {
    			removeRule(rules[i]);
    		}
    		return rules.length;
    	},
    	_createSheet: function() {
			var styleNode = this.doc.createElement('style');
	        styleNode.type = 'text/css';
	        styleNode.rel = 'stylesheet';
	        this.head.appendChild(styleNode);
	        return this._getSheetForNode(styleNode);
    	},
    	_getSheetForNode: function(node) {
	        for (var i = 0; i < this.sheets.length; i++) {
	            if (node === this._getNodeForSheet(this.sheets[i])) {
	                return this.sheets[i];
	            }
	        }
	        return null;
    	},
    	_getNodeForSheet: function(sheet) {
            return sheet.ownerNode || sheet.owningElement;
    	}
    };

    var exports = new Jss(document);
    exports.forDocument = function(doc) {
    	return new Jss(doc);
    };
    return exports;
})();	
	
});
</script>