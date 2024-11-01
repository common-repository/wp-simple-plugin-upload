<?php
/**
 * Plugin Name: WP Simple Plugin Upload
 * Plugin URI: http://seerox.com
 * Description: WP Simple Pluign Upload makes the plugins to upload easier and more simpler. Now you dont have to take an extra step each time while uploading the plugin. As we bring this altogether on one page.
 * Version: 2.2.8
 * Author: Seerox
 * Author URI: http://seerox.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Requires at least: 3.8
 * Tested up to: 6.6.2
 */

// Prevents direct file access
if ( ! defined( 'WPINC' ) ) {
    die();
}

/* Runs When plugin is activated */
register_activation_hook( __FILE__, 'seerox_wpspu_activation' );
/**
 * Plugin activation time.
 *
 * @since 1.0.0
 *
 * @uses update_option() Adds a new option if exists or update if exists.
 * 
 * @return void
 */
function seerox_wpspu_activation() {
    
    /* Loads activation functions */
    update_option( 'seerox_wpspu_activated', time() );
}

/* Runs When plugin is deactivated */
register_deactivation_hook( __FILE__, 'seerox_wpspu_deactivation' );
/**
 * Clears any temporary data stored by plugin
 *
 * @since 1.0.0
 *
 * @uses update_option()
 * 
 * @return void
 */
function seerox_wpspu_deactivation() {
    
    /* Loads deactivation functions */
    update_option( 'seerox_wpspu_deactivated', time() );
}

// Define Constants
define ( 'SEEROX_WPSPU_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define ( 'SEEROX_WPSPU_PLUGIN_ASSETS_URL', SEEROX_WPSPU_PLUGIN_URL . '/assets' );
define ( 'SEEROX_WPSPU_PLUGIN_JS_URL', SEEROX_WPSPU_PLUGIN_ASSETS_URL . '/js' );

/**
 * Enqueue admin scripts
 * 
 * @since 1.0.0
 * 
 * @return void
 */
function seerox_wpspu_register_admin_scripts() {
    wp_register_script( 'seerox_wpspu_admin_js', SEEROX_WPSPU_PLUGIN_JS_URL . '/admin.js' );
    wp_enqueue_script( 'seerox_wpspu_admin_js' );
}
add_action( 'admin_enqueue_scripts', 'seerox_wpspu_register_admin_scripts' );

/**
 * Adds Upload Plugin Button
 * 
 * @since 1.0.0
 *
 * @uses self_admin_url()
 * @uses seerox_wpspu_install_plugins_upload()
 * 
 * @param  array $plugins Information of all plugins
 *
 * @return void
 */
function seerox_wpspu_pre_current_active_plugins( $plugins ) {

    if ( ! current_user_can( 'upload_plugins' ) ) 
        return;
    
    $tab = 'upload';
    printf( ' <a href="%s" class="upload-view-toggle page-title-action"><span class="upload">%s</span><span class="browse">%s</span></a>',
        ( 'upload' === $tab ) ? self_admin_url( 'plugin-install.php' ) : self_admin_url( 'plugin-install.php?tab=upload' ),
        __( 'Upload Plugin' ),
        __( 'Browse Plugins' )
    );

    seerox_wpspu_install_plugins_upload();
}
add_action( 'pre_current_active_plugins', 'seerox_wpspu_pre_current_active_plugins', 10, 1 );

/**
 * Shows an Upload Plugin Form that Uploads from zip.
 * 
 * @since 1.0.0
 *
 * @return void
 */
function seerox_wpspu_install_plugins_upload() { ?>
<div class="upload-plugin">
    <p class="install-help"><?php _e('If you have a plugin in a .zip format, you may install it by uploading it here.'); ?></p>
    <form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo self_admin_url('update.php?action=upload-plugin'); ?>">
        <?php wp_nonce_field( 'plugin-upload' ); ?>
        <label class="screen-reader-text" for="pluginzip"><?php _e( 'Plugin zip file' ); ?></label>
        <input type="file" id="pluginzip" name="pluginzip" />
        <?php submit_button( __( 'Install Now' ), '', 'install-plugin-submit', false ); ?>
    </form>
</div>
<?php
}
