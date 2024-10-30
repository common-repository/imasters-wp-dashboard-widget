<?php
/*
Plugin Name: iMasters WP Dashboard Widget
Plugin URI: http://code.imasters.com.br/wordpress/plugins/imasters-wp-dashboard-widget/
Description: This plugin creates a new dashboard widget and lists out the latest imasters news.
Author: Apiki
Version: 0.2
Author URI: http://apiki.com/
*/

/*  Copyright 2009  Apiki (email : leandro@apiki.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class IMASTERS_WP_Dashboard_Widget {

    /**
     * Construct Function
     */
    function IMASTERS_WP_Dashboard_Widget()
    {
        //Call the function to insert the JavaScript for admin
        add_action( 'admin_print_scripts', array( &$this, 'admin_header' ) );
        //Call Function to textdomain for translation language
        add_action( 'init', array( &$this, 'textdomain' ) );
        // Hoook into the 'wp_dashboard_setup' action to register our other functions
        add_action('wp_dashboard_setup', array( &$this, 'add_dashboard_widgets' ) );
    }

    /**
     * This function insert JS in admin plugin
     */
    function admin_header()
    {
        echo '<link href="'.get_bloginfo('wpurl').'/wp-content/plugins/imasters-wp-dashboard-widget/assets/css/imasters-wp-dashboard-widget.css" rel="stylesheet" type="text/css" />'."\n";
    }

    /**
     * Create the textdomain for translation language
     */
    function textdomain()
    {
        load_plugin_textdomain('iwpdw','','wp-content/plugins/imasters-wp-dashboard-widget/assets/languages');
    }

    /**
     * Create the function to output the contents of our Dashboard Widget
     */
    function dashboard_widget_function()
    {
        global $wpdb;

	include_once(ABSPATH . WPINC . '/rss.php');
	$tech_rss_feed = "http://imasters.uol.com.br/feed/";

        echo "<div id='identity'></div>";
	echo "<ul>";

	$rss = fetch_rss($tech_rss_feed);

	$rss->items = array_slice($rss->items, 0, 6);
	$channel = $rss->channel;

	foreach ($rss->items as $item ) {
		$parsed_url = parse_url(wp_filter_kses($item['link']));
		echo "<li><a href=" . wp_filter_kses($item['link']) . ">" . wptexturize(wp_specialchars($item['title'])) . "</a></li>";
                echo "<div class=\"pubdate\">" . date( get_option( 'date_format' ), strtotime(wptexturize(wp_specialchars($item['pubdate']))) ). "</div>";
		echo "<p>" . wptexturize(wp_specialchars($item['description'])) . "</p>";
	}
	echo "</ul>";
        echo "<div id=\"button\"><a class=\"button-primary\" href=\"http://imasters.uol.com.br/\">" . __( 'Access iMasters site', 'iwpdw') . "</a></div>";

        
    }

    /**
     * Create the function use in the action hook
     */
    function add_dashboard_widgets()
    {
        wp_add_dashboard_widget('imasters_wp_dashboard_widget', __('iMasters Feed', 'iwpdw' ), array( &$this, 'dashboard_widget_function' ) );
    }
}
$imasters_wp_dashboard_widget = new IMASTERS_WP_Dashboard_Widget();
?>