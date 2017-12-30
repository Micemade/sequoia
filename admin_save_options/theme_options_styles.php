<?php
if( !isset($_GET['activated'])) {
header("Content-type: text/css; charset: UTF-8");
};
global $of_sequoia;

$g_FontToggle 		= $of_sequoia['google_typekit_toggle']; // applies to body and titles
$site_bg_toggle 	= $of_sequoia['site_bg_toggle'];
$site_bg_default	= isset($of_sequoia['site_bg_default']) ? $of_sequoia['site_bg_default'] : null; //default tiles
$site_bg_uploaded	= $of_sequoia['site_bg_uploaded']; // uploaded site bg images
$site_bg_repeat		= $of_sequoia['site_bg_controls']['repeat'];
$site_bg_position	= $of_sequoia['site_bg_controls']['position'];
$site_bg_attachment	= $of_sequoia['site_bg_controls']['attachment'];
$site_bg_color		= $of_sequoia['site_back_color'];
/**
 *	BODY styles
 *
 */

echo "body { \n";
	
	$g_FontBody			= $of_sequoia['google_body']['face'];
	$g_FontBodySize		= $of_sequoia['google_body']['size'];
	$g_FontBodyWeight	= $of_sequoia['google_body']['weight'];
	$g_FontBodyColor	= $of_sequoia['google_body']['color'];

	if( $g_FontToggle ) {
		echo "/* BODY FONT STYLE - GOOGLE FONT */\n";
		echo isset( $g_FontBody )		? "font-family:".$g_FontBody.", Helvetica, Arial, sans-serif;\n" : null;
		echo isset( $g_FontBodySize )	? "font-size:".$g_FontBodySize.";\n" : null;
		echo isset( $g_FontBodyWeight )	? "font-weight:".$g_FontBodyWeight.";\n" : null;
		echo isset( $g_FontBodyColor )	? "color:".$g_FontBodyColor.";\n" : null;
	
	};
	
	if( $g_FontToggle == 'none' || $g_FontToggle == 'typekit' ) {
		echo "/* BODY FONT STYLE - TYPEKIT - FALLBACK FONTS */\n";
		echo "font-family:".$of_sequoia['sys_body_font']['face'].", Helvetica, Arial, sans-serif;\n";
		
		echo ( $of_sequoia['sys_body_font']['size'] ? "font-size:" . $of_sequoia['sys_body_font']['size'] . ";\n" : null );
		echo ( $of_sequoia['sys_body_font']['height'] ? "line-height:" . $of_sequoia['sys_body_font']['height'] . " !important;\n " : null );

		echo ( $of_sequoia['sys_body_font']['style'] ? "font-style:" . $of_sequoia['sys_body_font']['style'] . ";\n " : null );
		echo ( $of_sequoia['sys_body_font']['color'] ? "color:" . $of_sequoia['sys_body_font']['color'] . ";\n " : null ); 
		
	};
	
	if( $site_bg_toggle != 'none' ) {
		echo "/* BODY BACKGROUND */\n";
		echo ($site_bg_toggle == 'default' && $site_bg_default ) ? "background-image: url(". $site_bg_default .");\n" : null;
		echo ($site_bg_toggle == 'upload' && $site_bg_uploaded) ? "background-image: url(". $site_bg_uploaded .");\n " : null;
		
		echo $site_bg_repeat	 ? "background-repeat: ".$site_bg_repeat.";\n" : null;
		echo $site_bg_position	 ? "background-position: ".$site_bg_position.";\n" : null;
		echo $site_bg_attachment ? "background-attachment: ".$site_bg_attachment.";\n" : null;
		
	};
	
	echo $site_bg_color ? "background-color: ".$site_bg_color.";\n" : null;
	
echo "}\n\n"; // end echo body
/**  end BODY styles */


/** DEPENDENCIES - OTHER SELECTORS WITH CSS's AS BODY ( overrides )*/
if( $g_FontToggle == 'google' ) {
	echo "#site-menu, .block-subtitle, .bottom-block-link a, .button, .onsale, .taxonomy-menu h4, button, input#s, input[type=\"button\"], input[type=\"email\"], input[type=\"reset\"], input[type=\"submit\"], input[type=\"text\"], select, textarea, ul.post-portfolio-tax-menu li a { \n font-family: ".$g_FontBody.", Helvetica, Arial, sans-serif;\n }\n\n";
}



/**
 *	HEADINGS styles ( and MAIN MENU )
 *
 */
$g_FontHeadings			= $of_sequoia['google_headings']['face'];
$g_FontHeadingsWeight	= $of_sequoia['google_headings']['weight'];
$g_FontHeadingsColor	= $of_sequoia['google_headings']['color'];
	

if ( $g_FontToggle == 'google' ) {
	echo "/* HEADING STYLE - GOOGLE FONT */\n";
	// only headings
	echo isset($g_FontHeadings) ? "h1, h2, h3, h4, h5, h6 { \nfont-family: \"".$g_FontHeadings."\", Helvetica, Arial, sans-serif;\n" :null; 
	echo isset($g_FontHeadingsWeight) ? "font-weight: ".$g_FontHeadingsWeight.";\n" : null;
	echo isset($g_FontHeadingsColor) ? "color: ". $g_FontHeadingsColor .";\n" : null;
	echo "}\n\n";
	
	// others, but with same font
	echo isset( $g_FontHeadings ) ? ".billing_country_chzn, .chzn-drop, .navbar .nav, .price, footer  { \nfont-family: \"".$g_FontHeadings."\", Helvetica, Arial, sans-serif; \n}\n\n" : null;
	
}else{
	echo "/* HEADING STYLE - SYSTEM FONT */\n";
	echo "h1, h2, h3, h4, h5, h6  { \n";
	echo "font-family:".$of_sequoia['sys_heading_font']['face'].", Helvetica, Arial, sans-serif;\n";
	echo $of_sequoia['sys_heading_font']['weight'] ? "font-weight:".$of_sequoia['sys_heading_font']['weight'].";\n" : "font-weight:normal;\n";
	echo $of_sequoia['sys_heading_font']['color'] ? "color:".$of_sequoia['sys_heading_font']['color'].";\n" : null;
	echo $of_sequoia['sys_heading_font']['style'] ? 'font-style:'.$of_sequoia['sys_heading_font']['style'].";\n" : null ;
	echo "} \n\n";
		
	echo ".billing_country_chzn, .chzn-drop, .navbar .nav, .price, footer {\n";
	echo "font-family:".$of_sequoia['sys_heading_font']['face'].", Helvetica, Arial, sans-serif; \n}\n\n";
}
/* end HEADINGS styles  */


/**
 *	IMAGES HOVER OVERLAY
 *
 */

$item_overlay_color		= $of_sequoia['item_overlay_color'];	
$item_overlay_opacity	= $of_sequoia['item_overlay_opacity'];	
$io_opac 				= $item_overlay_opacity / 100;
if( $item_overlay_color ) {
	echo "/* ITEM OVERLAY COLOR */\n";
	echo ".item-overlay { \nbackground: ".$item_overlay_color ."; \nopacity: ".$io_opac.";\n}\n\n";
}


/**
 *	LINKS TEXT COLOR:
 *
 */
$links_color			= $of_sequoia['links_color'];	
$links_hover_color		= $of_sequoia['links_hover_color'];	

if( $links_color ) {
	echo "/* LINKS TEXT COLOR */\n";
	echo "a, a:link, a:visited, .breadcrumbs > * , .has-tip, .has-tip:focus, .tooltip.opened, .panel.callout a:not(.button) , .side-nav li a:not(.button), .side-nav li.heading, .add-to-cart-holder a.wc-loop-button {\ncolor: ". $links_color ." \n}\n\n";
}

if( $links_hover_color ) {
	echo "/* LINKS TEXT COLOR */\n";
	echo "a:hover, a:focus, .breadcrumbs > *:hover, .has-tip:hover, .add-to-cart-holder a.wc-loop-button:hover, .add-to-cart-holder a.wc-loop-button:focus  { \ncolor: ". $links_hover_color ." \n}\n\n";

}



/**
 *	BUTTONS FONT AND BACKGROUND COLOR:
 *
 */
$buttons_bck_color			= $of_sequoia['buttons_bck_color'];	
$buttons_hover_bck_color	= $of_sequoia['buttons_hover_bck_color'];	
$buttons_font_color			= $of_sequoia['buttons_font_color'];	
$buttons_hover_font_color	= $of_sequoia['buttons_hover_font_color'];	

if( $buttons_bck_color || $buttons_font_color ) {
	echo "/* BUTTONS BACK AND TEXT COLORS */\n";
	echo "button, .button:not(.wc-loop-button), a.button:not(.wc-loop-button), button.disabled, button[disabled], button.disabled:focus,  button[disabled]:focus, .woocommerce .quantity .plus, .woocommerce .quantity .minus, .woocommerce #content .quantity .plus, .woocommerce #content .quantity .minus, .woocommerce-page .quantity .plus, .woocommerce-page .quantity .minus, .woocommerce-page #content .quantity .plus, .woocommerce-page #content .quantity .minus {\nbackground-color: rgba(". hex2rgb($buttons_bck_color) .", 1); color: ".$buttons_font_color ."\n}\n\n";
}
if( $buttons_hover_bck_color || $buttons_hover_font_color ) {
	echo "/* BUTTONS HOVER BACK AND TEXT COLORS */\n";
	echo "button:hover, .button:hover:not(.wc-loop-button), a.button:hover:not(.wc-loop-button), button.disabled:hover, button[disabled]:hover, .woocommerce .quantity .plus:hover, .woocommerce .quantity .minus:hover, .woocommerce #content .quantity .plus:hover, .woocommerce #content .quantity .minus:hover, .woocommerce-page .quantity .plus:hover, .woocommerce-page .quantity .minus:hover, .woocommerce-page #content .quantity .plus:hover, .woocommerce-page #content .quantity .minus:hover { \nbackground-color: rgba(". hex2rgb($buttons_hover_bck_color) .", 1) !important; color: ".$buttons_hover_font_color."\n}\n\n";
}


/**
 *	HEADER LINKS and BACKGROUND:
 *
 */
$header_font_color					= $of_sequoia['header_font_color'];	
$header_links_color					= $of_sequoia['header_links_color'];	
$header_links_hover_color			= $of_sequoia['header_links_hover_color'];	
$header_back_color					= $of_sequoia['header_back_color'];
$header_back_opacity				= $of_sequoia['header_back_opacity'];
$sidemenu_back_opacity				= $of_sequoia['sidemenu_back_opacity'];


if( $header_font_color ) {
	echo "/* HEADER FONT COLOR */\n";
	
	echo "header#site-menu { \ncolor:".$header_font_color."; \n}\n\n";
	
	echo "#site-menu,\n";
	echo "#searchform-header input::-webkit-input-placeholder,\n";
	echo "#searchform-header input:-moz-placeholder,\n";
	echo "#searchform-header input::-moz-placeholder,\n";
	echo "#searchform-header input:-ms-input-placeholder { \ncolor:".$header_font_color."; \n}\n\n";
	
}
// HEADER LINKS AND HOVERS :
if( $header_links_color ) {
	echo "/* HEADER LINKS */\n";
	echo "#site-menu a, #main-nav-wrapper a, #secondary-nav a, .mega-clone a, .sub-clone a { \ncolor: ". $header_links_color ." \n}\n\n";
}
if( $header_links_hover_color ) {
	echo "/* HEADER LINKS HOVERS */\n";	
	echo "#site-menu a:hover, #main-nav-wrapper a:hover, #secondary-nav a:hover, .mega-clone a:hover, .sub-clone a:hover { \ncolor: ". $header_links_hover_color ." \n}\n\n";
}

//  HEADER MENU / SIDE MENUBACKGROUND COLOR:
if( $header_back_color ) {

	echo "/* HEADER BACK COLOR */\n";
	echo "#site-menu.horizontal, #site-menu-mobile { \nbackground-color:rgba(".hex2rgb($header_back_color).", ".$header_back_opacity / 100 .");  \n}\n\n";
		
	echo "/* SIDEMENU BACK COLOR */\n";
	echo "#site-menu.vertical, nav.st-menu   { \nbackground-color:rgba(".hex2rgb($header_back_color).", ".$sidemenu_back_opacity / 100 .");  \n}\n\n";
	
	echo ".stick-it-header, ul.navigation li ul { \nbackground-color:rgba(".hex2rgb($header_back_color).", 0.9 );  \n}\n\n";
	
	echo "/* SUBS, MEGAS and MINI CART back color */\n";
	echo ".mega-clone, .sub-clone, .sub-clone li .sub-menu ,.mobile-sticky.stuck, #secondary-nav ul.navigation li ul, .mini-cart-list { \nbackground-color: ".$header_back_color .";  \n}\n\n";
	
	echo ".mini-cart-list .arrow-up, .mini-cart-list .arrow-up:before { \n border-bottom-color: ".$header_back_color .";  \n}\n\n";
	
	echo ".menu-border:before { \nbackground-color:". $header_back_color ."; \n}\n\n";
	
	echo ".active-mega span { \nborder-right-color:". $header_back_color ." \n}\n\n";
}


/**
 *	LOGO AND TITLE SETTINGS
 *
 *	- logo width (height s auto)
 *	- title font size and word-wrap: break-word toggle 
 *
 */
$logo_width		  = $of_sequoia['logo_width'];
$logo_height	  = $of_sequoia['logo_height'];
$title_size		  = $of_sequoia['title_font_size'];
$title_break_word = $of_sequoia['title_break_word'];
 
if( $logo_width  ) {
	if( $logo_width >= 300 ) {
		echo "/* LOGO WIDTH - IF SET > 300 px */\n";
		echo "#site-menu.vertical  { width: 320px; }\n";
		echo ".vertical #site-title h1 img  { width: 100%; }\n";
		echo "#page.vertical, footer.vertical { margin-left: 320px; }\n\n";

	}elseif ( $logo_width < 300 && $logo_width >= 250 ) {
		echo "/* LOGO WIDTH - IF SET 250 - 300 px */\n";
		echo "#site-menu.vertical  { width: ". ( $logo_width + 20 ) ."px; }\n";
		echo ".vertical #site-title h1 img  { width: ". $logo_width ."px; }\n";
		echo "#page.vertical, footer.vertical { margin-left: ". ( $logo_width + 20 ) ."px; }\n\n";
		 
	}elseif ( $logo_width < 250 ) {
		echo "/* LOGO WIDTH - IF SET < 250px */\n";
		echo "#site-menu.vertical  { width: 270px; }\n";
		echo ".vertical #site-title h1 img  { width: ". $logo_width ."px; }\n";
		echo "#page.vertical, footer.vertical { margin-left: 270px; }\n\n";
	}
}

if(	$logo_height ) {
	echo "/* LOGO HEIGHT - FOR HORIZ LAYOUT */\n";
	echo ".horizontal #site-title h1 img { \nmax-height: ".$logo_height."px \n}\n\n";
}

if( $title_size ) {
	echo "/* SITE TITLE FONT SIZE */\n";
	echo "#site-title h1, .stick-it-header h1 {\nfont-size: ".$title_size."%;\n}\n\n";
}
if( $title_break_word ) {
	echo "/* SITE TITLE BREAK-WORD PROPERTY */\n";
	echo "#site-title h1 {\n word-wrap: break-word; \n}\n\n";
}



/**
 *	BODY BACKGROUND PROPERTIES:
 *
 */
$body_bg_toggle 	= $of_sequoia['body_bg_toggle'];
$body_bg_default	= isset($of_sequoia['body_bg_default']) ? $of_sequoia['body_bg_default'] : null; //default tiles
$body_bg_uploaded	= $of_sequoia['body_bg_uploaded']; // uploaded site bg images
$body_bg_repeat		= $of_sequoia['body_bg_controls']['repeat'];
$body_bg_position	= $of_sequoia['body_bg_controls']['position'];
$body_bg_attachment	= $of_sequoia['body_bg_controls']['attachment'];
$body_bg_size		= $of_sequoia['body_bg_controls']['size'];
$body_bg_color		= $of_sequoia['body_back_color'];
$body_c_opacity		= $of_sequoia['body_back_color_opacity'];

if( $body_bg_toggle != 'none' ) {

echo "#page, .page-template-page-blank > section, .page-template-page-blank_footerwidgets > section {\n";

	if( $body_bg_toggle != 'none' ) {
		echo "/* PAGE ( CONTENT - #page element) BACK IMAGE  */\n";
		echo ($body_bg_toggle == 'default' && $body_bg_default ) ? "\nbackground-image: url(". $body_bg_default .") ;\n" : null;
		echo ($body_bg_toggle == 'upload' && $body_bg_uploaded) ? "\nbackground-image: url(". $body_bg_uploaded .") ;\n" : null;
		
		echo $body_bg_repeat	 ? "background-repeat: ".$body_bg_repeat.";\n" : null;
		echo $body_bg_position	 ? "background-position: ".$body_bg_position.";\n" : null;
		echo $body_bg_attachment ? "background-attachment: ".$body_bg_attachment.";\n" : null;
		echo $body_bg_size		 ? "background-size: ".$body_bg_size.";\n" : null;
		
	};

echo '}';
	
};

if( $body_bg_color ) {
	echo "/* PAGE BACK COLOR  */\n";
	echo "#page {\nbackground-color: rgba(".hex2rgb($body_bg_color).', '.$body_c_opacity / 100 .");\n}\n\n";

	// BODY BACK FOR ONEPAGER MENU BACK
	echo "/* ONEPAGER BACK COLOR  */\n";
	echo ".aq_block_onepager_menu { \nbackground-color: rgba(".hex2rgb( $body_bg_color ).", 0.9);  \n}\n\n";

	echo ".product-filters-wrap { \nbackground-color: rgba(".hex2rgb($body_bg_color).", 0.9); \n}\n\n";

	// WIDGET ICONS BACKGROUNDS
	echo ".widget h4:before { \nbackground: ".$body_bg_color."; \n}\n\n";
}



/**
 *	FOOTER LINKS AND BUTTONS COLOR:
 *
 */
$footer_font_color			= $of_sequoia['footer_font_color'];	
$footer_links_color			= $of_sequoia['footer_links_color'];	
$footer_links_hover_color	= $of_sequoia['footer_links_hover_color'];	
$footer_back_color			= $of_sequoia['footer_back_color'];
$footer_back_opacity		= $of_sequoia['footer_back_opacity'];	

$footer_bc_IE8 = str_replace('#','', $footer_back_color);

// links and hovers
if( $footer_links_color ) {
	echo "/* FOOTER LINKS COLOR  */\n";
	echo "footer a:link, footer a:visited, footer button, footer .button { \ncolor: ". $footer_links_color ." \n}\n\n";
}
if( $footer_links_hover_color ) {
	echo "/* FOOTER LINKS HOVER COLOR  */\n";	
	echo "footer a:hover, footer button:hover, footer .button:hover { \ncolor: ". $footer_links_hover_color ." \n}\n\n";
}
if( $footer_back_color ) {	
	echo "/* FOOTER BACK COLOR  */\n";	
	echo "footer { \nbackground-color: rgba(". hex2rgb($footer_back_color) .", ". $footer_back_opacity/100 ."); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\"#cc".$footer_bc_IE8."\", endColorstr=\"#cc".$footer_bc_IE8."\",GradientType=0 );  \n}\n\n";
}


/**
 *	BORDER STYLES
 *	- disabled
 

$border_width = $of_sequoia['border_icon']['width'];
$border_style = $of_sequoia['border_icon']['style'];
$border_color = $of_sequoia['border_icon']['color'];

echo '
.single-product-block h3, .menu-border, .breadcrumbs-search-border, .footer-border, .title-border  {
	border-bottom-width: '.$border_width.'px;
	border-bottom-style: '.$border_style.';
	border-color: '.$border_color.';
	color: '.$border_color.';
}
';

# SIDE MENU / HEADER MENU BORDER SETTINGS 
echo '
#site-menu:after {
	border-width: '.$border_width.'px;
	border-style: '.$border_style.';
	border-color: '.$border_color.';
}
';
if( $of_sequoia['no_border_sitemenu'] ) {
	echo '#site-menu:after {
		border: none;
	}';
};
if( $border_width ) {
	echo '.menu-border:before { margin-bottom: -'. (floor($border_width / 2) + 1) .'px; }';
	
}
*/

/**	
 *	BODY COLOR - to product filter widget area
 */





/**
 *	CUSTOM CSS
 *
 */
if( $of_sequoia['header_custom_css'] && $of_sequoia['orientation'] == 'horizontal' ) {
	echo $of_sequoia['header_custom_css'];
}

if( $of_sequoia['custom_css'] ) {
	echo $of_sequoia['custom_css'];
}

?>