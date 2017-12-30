<?php

$of_options[] = array(   "name"     => "JQuery UI Slider example 1",
            "desc"     => "JQuery UI slider description.<br /> Min: 1, max: 500, step: 3, default value: 45",
            "id"     => "slider_example_1",
            "std"     => "45",
            "min"     => "1",
            "step"    => "3",
            "max"     => "500",
            "type"     => "sliderui" 
        );
        
$of_options[] = array(   "name"     => "JQuery UI Slider example 1 with steps(5)",
            "desc"     => "JQuery UI slider description.<br /> Min: 0, max: 300, step: 5, default value: 75",
            "id"     => "slider_example_2",
            "std"     => "75",
            "min"     => "0",
            "step"    => "5",
            "max"     => "300",
            "type"     => "sliderui" 
        );
        
$of_options[] = array(   "name"     => "Switch 1",
            "desc"     => "Switch OFF",
            "id"     => "switch_ex1",
            "std"     => 0,
            "type"     => "switch"
        );   
        
$of_options[] = array(   "name"     => "Switch 2",
            "desc"     => "Switch ON",
            "id"     => "switch_ex2",
            "std"     => 1,
            "type"     => "switch"
        );
        
$of_options[] = array(   "name"     => "Switch 3",
            "desc"     => "Switch with custom labels",
            "id"     => "switch_ex3",
            "std"     => 0,
            "on"     => "Enable",
            "off"     => "Disable",
            "type"     => "switch"
        );
		
$of_options[] = array( "name" => "Example Options",
					"type" => "heading"); 	   

					
$of_options[] = array( "name" => "Media Uploader",
					"desc" => "Upload images using the native media uploader, or define the URL directly",
					"id" => "media_upload",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => "Typography",
					"desc" => "This is a typographic specific option.",
					"id" => "typography",
					"std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
					"type" => "typography");  
					
$of_options[] = array( "name" => "Border",
					"desc" => "This is a border specific option.",
					"id" => "border",
					"std" => array('width' => '2','style' => 'dotted','color' => '#444444'),
					"type" => "border");      
					
$of_options[] = array( "name" => "Colorpicker",
					"desc" => "No color selected.",
					"id" => "example_colorpicker",
					"std" => "",
					"type" => "color"); 
					
$of_options[] = array( "name" => "Colorpicker (default #2098a8)",
					"desc" => "Color selected.",
					"id" => "example_colorpicker_2",
					"std" => "#2098a8",
					"type" => "color");          
                  
$of_options[] = array( "name" => "Upload",
					"desc" => "An image uploader without text input.",
					"id" => "uploader",
					"std" => "",
					"type" => "upload");  
					
$of_options[] = array( "name" => "Upload Min",
					"desc" => "An image uploader with text input.",
					"id" => "uploader2",
					"std" => "",
					"mod" => "min",
					"type" => "upload");     
                                
$of_options[] = array( "name" => "Input Text",
					"desc" => "A text input field.",
					"id" => "test_text",
					"std" => "Default Value",
					"type" => "text"); 
                                  
$of_options[] = array( "name" => "Input Checkbox (false)",
					"desc" => "Example checkbox with false selected.",
					"id" => "example_checkbox_false",
					"std" => 0,
					"type" => "checkbox");    
                                        
$of_options[] = array( "name" => "Input Checkbox (true)",
					"desc" => "Example checkbox with true selected.",
					"id" => "example_checkbox_true",
					"std" => 1,
					"type" => "checkbox"); 
                                                                           
$of_options[] = array( "name" => "Normal Select",
					"desc" => "Normal Select Box.",
					"id" => "example_select",
					"std" => "three",
					"type" => "select",
					"options" => $of_options_select);                                                          

$of_options[] = array( "name" => "Mini Select",
					"desc" => "A mini select box.",
					"id" => "example_select_2",
					"std" => "two",
					"type" => "select2",
					"class" => "mini", //mini, tiny, small
					"options" => $of_options_radio);    

$of_options[] = array( "name" => "Input Radio (one)",
					"desc" => "Radio select with default of 'one'.",
					"id" => "example_radio",
					"std" => "one",
					"type" => "radio",
					"options" => $of_options_radio);
					
$images_url =  ADMIN_DIR . 'images/';
$of_options[] = array( "name" => "Image Select",
					"desc" => "Use radio buttons as images.",
					"id" => "images",
					"std" => "warning.css",
					"type" => "images",
					"options" => array(
						'warning.css' => $images_url . 'warning.png',
						'accept.css' => $images_url . 'accept.png',
						'wrench.css' => $images_url . 'wrench.png'));
                                        
$of_options[] = array( "name" => "Textarea",
					"desc" => "Textarea description.",
					"id" => "example_textarea",
					"std" => "Default Text",
					"type" => "textarea"); 
                                      
$of_options[] = array( "name" => "Multicheck",
					"desc" => "Multicheck description.",
					"id" => "example_multicheck",
					"std" => array("three","two"),
				  	"type" => "multicheck",
					"options" => $of_options_radio);
                                      
$of_options[] = array( "name" => "Select a Category",
					"desc" => "A list of all the categories being used on the site.",
					"id" => "example_category",
					"std" => "Select a category:",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
					"id" => "custom_favicon",
					"std" => "",
					"type" => "upload"); 	
					
					
$images_url =  ADMIN_DIR . 'images/';
$of_options[] = array( "name" => "Main Layout",
					"desc" => "Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.",
					"id" => "layout",
					"std" => "2c-l-fixed.css",
					"type" => "images",
					"options" => array(
						'1col-fixed.css' => $images_url . '1col.png',
						'2c-r-fixed.css' => $images_url . '2cr.png',
						'2c-l-fixed.css' => $images_url . '2cl.png',
						'3c-fixed.css' => $images_url . '3cm.png',
						'3c-r-fixed.css' => $images_url . '3cr.png')
					);
?>