<?php

/**
 * Plugin Name: WooCommerce Random Catalog Sorting
 * Description: Adds a random product sorting method with working pagination
 * Author: Contrast.fi
 * Version: 0.1.0
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_Random_Catalog_Sorting')) {

  class WC_Random_Catalog_Sorting
  {

    public function __construct()
    {
      if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $this->init();
      } else {
        add_action('admin_notices', array($this, 'admin_nag'));
      }
    }

    private function init()
    {
      add_filter('woocommerce_default_catalog_orderby_options', array($this, 'add_random_catalog_sorting_setting'));
      add_filter('woocommerce_catalog_orderby', array($this, 'add_random_catalog_sorting_setting'));
      add_filter('woocommerce_get_catalog_ordering_args', array($this, 'random_orderby_args'));
    }

    public function admin_nag()
    {
      $message = __('WooCommerce has to be enabled for WooCommerce Random Catalog Sorting plugin to work.', 'wc-random-catalog-sorting');
      $format = '<div class="notice error is-dismissible"><p>%s</p></div>';
      printf($format, $message);
    }

    public function add_random_catalog_sorting_setting($sortby)
    {
      $label = esc_html__('Random', 'wc-random-catalog-sorting');
      $sortby['random_order'] = apply_filters('wc_random_catalog_sorting_label', $label);
      return $sortby;
    }

    public function random_orderby_args($args)
    {
      $orderby = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

      if ('random_order' == $orderby) {

        // Force session cookie for non-logged users
        if (!WC()->session->has_session()) {
          WC()->session->set_customer_session_cookie(true);
        }

        $seed = WC()->session->get('random_sorting_seed');

        // Flush seed if not on a defined page
        if (!$seed || !get_query_var('paged')) {
          $seed = rand();
          WC()->session->set('random_sorting_seed', $seed);
        }

        $args['orderby'] = 'RAND(' . $seed . ')';
        $args['order'] = '';
        $args['meta_key'] = '';
      }

      return $args;
    }
  }

  new WC_Random_Catalog_Sorting();
}
