<?php
/*
Plugin Name: Monotote Wordpress Plugin (MWP)
Plugin URI: https://support.monotote.com/hc/en-us/articles/115005069389-Wordpress-plugin
Description: Easily activate Monotote on your Wordpress website
Version: 1.0.0
Author: Chris Schalenborgh
Author URI: https://www.monotote.com
*/

add_action( 'admin_menu', 'MTWP_add_admin_menu' );
add_action( 'admin_init', 'MTWP_settings_init' );

function MTWP_add_admin_menu() {
	add_menu_page( 'Monotote WP plugin', 'Monotote WP plugin', 'manage_options', 'monotote_wp_plugin', 'MTWP_options_page', plugins_url('Monotote-Wordpress/menulogo.png', 6));
}

function MTWP_settings_init() {

	register_setting( 'pluginPage', 'MTWP_settings' );

	add_settings_section(
		'MTWP_pluginPage_section', 
		__( 'Settings', 'wordpress' ), 
		'MTWP_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'MTWP_text_field_0', 
		__( 'Publisher Key', 'wordpress' ), 
		'MTWP_text_field_0_render', 
		'pluginPage', 
		'MTWP_pluginPage_section' 
	);
}

function MTWP_text_field_0_render() {
	$options = get_option( 'MTWP_settings' );

	echo "<input type='text' name='MTWP_settings[MTWP_text_field_0]' value='" .$options['MTWP_text_field_0']. "'>";
}

function MTWP_settings_section_callback() {
	echo __( 'Enter your publisher key as shown on the Monotote <a href="https://dashboard.monotote.com/include-code" target="_new">embed code page</a>.', 'wordpress');
}


function MTWP_options_page() {
	?>
    <div style="padding:10px;margin:10px 10px 0 0;background:white;">
	<form action="options.php" method="post">

		<p><img src="<?php echo plugin_dir_url( __FILE__ );?>/logo.png"></p>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form></div>

    <p>Monotote Wordpress plugin - <a target="_blank" href="https://www.monotote.com">www.monotote.com</a></p>
    <?php
}

function add_this_script_footer() {
    $monotote = get_option('MTWP_settings');
    $monotote_key = $monotote['MTWP_text_field_0'];

    if (strlen($monotote_key) > 0) {
        echo "<!-- monotote.com tag -->
<script>
  var _mnt = {publisherKey: '" .$monotote_key. "'};
  (function (document) { var s = document.createElement('script'); s.async = true; s.src = 'https://plugin.monotote.com/plugin.min.js?' + Date.now(); document.body.appendChild(s); })(document)
</script>
<!-- End monotote.com tag -->";
    }
}

add_action('wp_footer', 'add_this_script_footer'); ?>