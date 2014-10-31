<?php  
	/* 
    Plugin Name: Advanced Dates
    Plugin URI: http://studiohyperset.com/wordpress-advanced-dates-plugin/4016
    Description: Extending the literary, documentary, and archival potential of WordPress, this plugin allows publishers to easily customize the publication year of posts and pages. <em>w/ special thanks to <a href="http://www.ryanajarrett.com">Ryan Jarrett</a> and <a href="http://cantuaria.net.br/">Bruno Cantuaria</a></em>.
	Version: 1.0.1
    Author: Studio Hyperset, Inc. 
    Author URI: http://studiohyperset.com
	License: GPL3
    */  
	
	// Add css
	function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/style.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
	}
	add_action('admin_head', 'admin_register_head');

	// Add JavaScript
	wp_enqueue_script('functions', '/wp-content/plugins/advanced-dates/js/functions.js');	
		
	// Define admin option group
	add_action('admin_init', 'advanceddates_group_init' );
	function advanceddates_group_init(){
		register_setting( 'advanceddates_group', 'advanceddates_global' );
		register_setting( 'advanceddates_group', 'advanceddates_differential' );
		register_setting( 'advanceddates_group', 'advanceddates_bypost' );
		register_setting( 'advanceddates_group', 'advanceddates_freeze_global' );
	}
	
	// New get_the_date function
	function get_advanced_dates($the_date) {
		global $post, $prefix;
		if (get_option('advanceddates_bypost') && get_post_meta($post->ID,$prefix.'post_enable',true)) {
			if (!get_post_meta($post->ID,$prefix.'post_freeze',true))
				$year =  date('Y') + get_post_meta($post->ID,$prefix.'post_differential',true);
			else
				$year =  mysql2date('Y', $post->post_date) + get_post_meta($post->ID,$prefix.'post_differential',true);
			$the_date = str_replace(mysql2date("Y", $post->post_date),$year,$the_date);
		} elseif (get_option('advanceddates_global')) {
			if (!get_option('advanceddates_freeze_global'))
				$year =  date('Y') + get_option('advanceddates_differential');
			else
				$year =  mysql2date('Y', $post->post_date) + get_option('advanceddates_differential');
			$the_date = str_replace(mysql2date("Y", $post->post_date),$year,$the_date);
		}
		return $the_date;
	}	

	add_filter('get_the_date', 'get_advanced_dates');
	add_filter('the_date', 'get_advanced_dates');
	
	// Add same functionality to the_time and get_the_time functions	
	add_filter('get_the_time', 'get_advanced_dates');
	add_filter('the_time', 'get_advanced_dates');
	
	// Setup Advanced Dates metabox
	$prefix = 'advanceddates_meta_'; // a custom prefix to help us avoid pulling data from the wrong meta box

	$meta_box = array(
		'id' => 'advanceddates_metabox', // the id of our meta box
		'title' => 'Advanced Dates', // the title of the meta box
		'page' => 'post', // display this meta box on post editing screens
		'context' => 'side',
		'priority' => 'default', // high, core, default or low
		'fields' => array( // all of the options inside of our meta box
			array(
				'name' => 'Enable?',
				'id' => $prefix . 'post_enable',
				'type' => 'checkbox',
				'std' => ''
				),
			array(
				'name' => 'Differential:',
				'id' => $prefix . 'post_differential',
				'type' => 'text',
				'std' => '0'
			),
			array(
				'name' => 'Freeze?',
				'id' => $prefix . 'post_freeze',
				'type' => 'checkbox',
				'std' => ''
			)
		)
	);
	
	function add_metaboxes() {
		global $meta_box;
		if (get_option('advanceddates_bypost')) {
			add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
			add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', 'page', $meta_box['context'], $meta_box['priority']);

			$cps = get_post_types(array('_builtin' => false));
			foreach ($cps as $cp) {
				add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $cp, $meta_box['context'], $meta_box['priority']);
			}

		}
	}
	
	add_action('admin_menu', 'add_metaboxes');
	
	function mytheme_show_box() {
		global $meta_box, $post;

		// Use nonce for verification
		echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<table class="form-table">';
		foreach ($meta_box['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);
			echo '<tr>',
					'<th><label for="', $field['id'], '" id="label-', $field['id'], '">', $field['name'], '</label></th>',
					'<td>';
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '"/>', '<br />';
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4"', $meta ? $meta : $field['std'], '</textarea>', '<br />';
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>';
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
					}
					break;
				case 'checkbox':
					echo '<input type="checkbox" onclick="uncheck2(); uncheck3();" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					break;
			}
			echo     '<td>',
				'</tr>';
			if ($field['desc']) {
				echo "<tr><td colspan='2'>".$field['desc']."</td></tr>";
			}
		}

		echo '<tr id="spacer"><td></td></tr></table>
		<a href="http://studiohyperset.com" target="_blank" title="Studio Hyperset, Inc."><img src="http://studiohyperset.com/wp-content/uploads/2011/03/logo-lg.png" id="logo2" /></a>
		<p class="learn-more"><a id="instructions" href="javascript:toggle();">Instructions &amp; Links</a></p>		
		<div id="meta-instructions">
		<p class="p"><strong>Differential</strong><br />Enter + <span class="x">x</span> if you want your page/post to appear as if it&#39;s been written in the future (e.g., +150) and - <span class="x">x</span> if you want it to appear as if it&#39;s been written in the past (e.g, -150). If you omit the "+" or "-," the plugin defaults to the former ("+").</p>
		<p class="p"><strong>Freezing Dates</strong><br />This prevents the server from adjusting the date of a page/post when the calendar year rolls over. With this option enabled, a given page/post&#39;s date will always read <span class="x">x</span> +/- the original publication year (where <span class="x">x</span> = your differential). Without this option enabled, your page/post date will increase by one year every time a new calendar year begins.</p>
		
		<p class="p-small"><strong>Links</strong><br />
		<a href="http://wordpress.org/extend/plugins/advanced-dates/" target="_blank">Advanced Dates</a> :: <a href="http://studiohyperset.com/wordpress-advanced-dates-plugin/4016" target="_blank">Discussion</a> | <a href="http://getsatisfaction.com/studio_hyperset/products/studio_hyperset_wordpress_plugins" target="_blank">Support</a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=C2KQADH2TGTS4" target="_blank">Donate</a>
		<br /><a href="http://studiohyperset.com" target="_blank">SH</a> :: <a href="http://studiohyperset.com/#solutions" target="_blank">WordPress Plugins</a> | <a href="http://www.facebook.com/pages/Studio-Hyperset-Inc/10395843341" target="_blank">Facebook</a> | <a href="http://twitter.com/#!/studiohyperset" target="_blank">Twitter</a></p>
		
		</div>
		<p class="learn-more"><a href="options-general.php?page=advanced_dates">Settings &raquo; Advanced Dates</a></p>';
	}
	
	add_action('save_post', 'advanceddates_save_data');

	// Save data from meta box
	function advanceddates_save_data($post_id) {
		global $meta_box;

		// verify nonce
		if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		foreach ($meta_box['fields'] as $field) {
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];

			if ($new && $new != $old) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		}
	}
	
	// Setup Advanced Dates admin menu
	add_action('admin_menu', 'advanced_dates_menu');

	function advanced_dates_menu() {
		add_options_page('Advanced Dates Configuration', 'Advanced Dates', 'manage_options', 'advanced_dates', 'advanced_dates_options');
	}

	function advanced_dates_options() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		} ?>
		
		<div class="wrap">
			<h2>Advanced Dates</h2>   
			<div class="border"></div>
            <div id="logo-wrap"><a href="http://studiohyperset.com" target="_blank"><img src="http://studiohyperset.com/wp-content/uploads/2011/03/logo-lg.png" id="logo" /></a><a href="http://studiohyperset.com" target="_blank">studiohyperset.com</a></div>
            <p id="intro">Extending the literary, documentary, and archival potential of WordPress, <a href="http://studiohyperset.com" target="_blank">Studio Hyperset</a>'s "Advanced Dates" WordPress plugin allows publishers to easily customize the publication year of posts and pages.</p>
            
           <p id="list">&raquo; <a href="http://studiohyperset.com/wordpress-advanced-dates-plugin/4016" target="_blank">Share ideas and read more about</a> the "Advanced Dates" plugin.<br />
           &raquo; Plugin <a href="http://getsatisfaction.com/studio_hyperset/products/studio_hyperset_wordpress_plugins" target="_blank">support, feedback, and troubleshooting</a>.<br /> 
           &raquo; Browse <a href="http://studiohyperset.com/#solutions" target="_blank">SH's other WordPress plugins</a>.<br />
           &raquo; Like the plugin? <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=C2KQADH2TGTS4" target="_blank">Send a donation</a>.<br />
           &raquo; <a href="http://studiohyperset.com/#contact" target="_blank">Contact SH</a> and/or link up with us on <a href="http://www.facebook.com/pages/Studio-Hyperset-Inc/10395843341" target="_blank">Facebook</a> and <a href="http://twitter.com/#!/studiohyperset" target="_blank">Twitter</a>.
           </p>
			<div class="border2"></div>
     
			<form name="form" id="admin" method="post" action="options.php">
				<?php settings_fields( 'advanceddates_group' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th width="718" scope="row"><strong>OPTION 1: Customize Dates Sitewide</strong></th>
                        <td width="413"><input type="checkbox" onclick="document.getElementById('advanceddates_bypost').checked=false; uncheck();"  name="advanceddates_global" id="advanceddates_global" value="true" <?php if (get_option('advanceddates_global')) echo "checked"; ?> /></td>
					</tr>
					
					<tr valign="top">
						<th scope="row">Default differential: </th>
						<td><input type="text" name="advanceddates_differential" id="advanceddates_differential" value="<?php echo get_option('advanceddates_differential'); ?>" /></td>
					</tr>
                    
                    <tr>
						<td colspan=2>
						  <p class="adv">If you'd like to adjust your dates <span class="x">x</span> number of years site-wide, enter your standard differential above. For example, enter +150 if you want all your pages and posts to appear as if they were written 150 years in the future and -150 if you want them to appear as if they were written 150 years in the past. If you omit the "+" or "-," the plugin  defaults to the former ("+").<br /><br />
                          <em><strong>Please note</strong>, all dates are adjusted per, and rendered according to, <a href="http://wwp.greenwichmeantime.com/" target="_blank">GMT</a> and the <a href="http://en.wikipedia.org/wiki/Gregorian_calendar" target="_blank">Gregorian calendar</a>: the internationally accepted civil calendar.</em></p>
						</td>
					</tr>
                    
					<tr valign="top">
						<th scope="row">Would you like to freeze these dates?</th>
						<td><input type="checkbox" name="advanceddates_freeze_global" onclick="document.getElementById('advanceddates_bypost').checked=false;document.getElementById('advanceddates_global').checked=true;" id="advanceddates_freeze_global" value="true" <?php if (get_option('advanceddates_freeze_global')) echo "checked"; ?> /></td>
						</tr>
					<tr>
						<td colspan=2>
							<p class="adv">Freezing dates prevents the server from adjusting the date of a page/post when the calendar year rolls over. With this option enabled, your page/post dates will always read <span class="x">x</span> +/- the original publication year (where <span class="x">x</span> = your differential). Without this option enabled, your page/post dates will increase by one year each time a new calendar year begins.</p> 
						</td>
					</tr>			
					<tr valign="top">
						<th scope="row"><strong>OPTION 2: Customize Dates on Individual Posts, Custom Posts &amp; Pages</strong></th>
						<td><input type="checkbox" onclick="document.getElementById('advanceddates_freeze_global').checked=false; document.getElementById('advanceddates_global').checked=false;" name="advanceddates_bypost" id="advanceddates_bypost" value="true" <?php if (get_option('advanceddates_bypost')) echo "checked"; ?> /></td>
					</tr>
					<tr>
						<td colspan=2>
							<p class="adv">If you want to adjust your dates on a page-by-page and post-by-post basis, select this option, and click "Save Changes." You'll be able to  selectively (de)activate the plugin, customize the annual differential, and (un)freeze dates on each page/post's editing screen.</p>
						</td>
					</tr>
				</table>
				
				<div class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</div>

			</form>
		</div>

	<?php }

?>