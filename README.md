# Woocommerce Random Catalog Sorting

Adds a random product sorting method with working pagination.

The random order stays the same when navigating through paged results,
so the user will see all the products once when going through all the paged results.

How? The plugin saves a seed to user's WooCommerce session. A WooCommerce session is forced for unlogged users too.
The product query order is then randomized with the seed.

To keep things fresh, the seed is flushed if no paged parameter is present.

To change the sorting option label:

```
add_filter('wc_random_catalog_sorting_label', 'my_theme_random_catalog_sorting_label');
function my_theme_random_catalog_sorting_label($label) {
  // 'Random' is default
  return 'Change the label';
}
```

## Installation

Installation is the same as any other WordPress plugin.

1. Upload the plugin .zip file to your site plugin directory at /wp-content/plugins/.

2. Unzip the plugin file.

3. Visit the plugins page in the WP admin, activate the plugin. 

## Requires

- WooCommerce 3.x

## Performance considerations

This plugin uses SQL's `ORDER BY RAND()`. If you have hundreds of thoudsands of products, the query will be heavy.
