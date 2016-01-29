<?php
/**
 *
 * @link              https://github.com/enricodeleo/woocommerce-pdf-invoices-packing-slips-sort-by-invoice
 * @since             1.0.0
 * @package           Sort orders by invoice numer
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Enrico Deleo
 * Author URI:        http://enricodeleo.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/TGMPA-TGM-Plugin-Activation-ba41c10/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'wc_sort_orders_by_invoice_number_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 */
function wc_sort_orders_by_invoice_number_register_required_plugins() {
  /*
   * Array of plugin arrays. Required keys are name and slug.
   * If the source is NOT from the .org repo, then source is also required.
   */
  $plugins = array(
    // This is an example of how to include a plugin bundled with a theme.
    array(
      'name'               => 'WooCommerce', // The plugin name.
      'slug'               => 'woocommerce', // The plugin slug (typically the folder name).
      'required'           => true // If false, the plugin is only 'recommended' instead of required.
    ),
    // This is an example of how to include a plugin from an arbitrary external source in your theme.
    array(
      'name'         => 'WooCommerce PDF Invoices & Packing Slips', // The plugin name.
      'slug'         => 'woocommerce-pdf-invoices-packing-slips', // The plugin slug (typically the folder name).
      'required'     => true // If false, the plugin is only 'recommended' instead of required.
    )
  );

  $config = array(
    'id'           => 'tgmpa_sie4sesc',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'parent_slug'  => 'plugins.php',            // Parent menu slug.
    'capability'   => 'activate_plugins',
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'is_automatic' => false,                   // Automatically activate plugins after installation or not.
  );

  tgmpa( $plugins, $config );
}

// Add sorting option in the invoice column
add_filter( 'manage_edit-shop_order_sortable_columns', 'invoice_number_manage_sortable_columns');
function invoice_number_manage_sortable_columns( $columns ) {
    $columns['pdf_invoice_number'] = 'pdf_invoice_number';
    return $columns;
}

// Sorting by invoice number
add_action( 'pre_get_posts', 'invoice_number_orderby' );
function invoice_number_orderby( $query ) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if( 'pdf_invoice_number' == $orderby ) {
        $query->set('meta_key','_wcpdf_invoice_number');
        $query->set('orderby','meta_value_num');
    }
}