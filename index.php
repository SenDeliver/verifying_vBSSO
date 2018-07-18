<?php
/*
Plugin Name: vbsso_check
Description: The WordPress plugin to check if vBSSO installed properly on user's platform .
Version: 1.0.
Author: Denys Selivanov.
*/

require_once __DIR__ . "/CheckingVBSSO.php";
require_once  __DIR__ . "/WidgetFormWithURI.php";


function register_WidgetFormWithURI_widget() {
    register_widget('WidgetFormWithURI');
}
add_action('widgets_init', 'register_WidgetFormWithURI_widget');




