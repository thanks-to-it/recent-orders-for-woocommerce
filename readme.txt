=== Customer Recent Orders History for WooCommerce ===
Contributors: algoritmika, thankstoit, anbinder, karzin
Tags: woocommerce, order, orders, recent orders, woo commerce
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 2.0.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display the customer's recent order list on the frontend in WooCommerce.

== Description ==

Enhance your WooCommerce store with the **Recent Orders Widget for WooCommerce** plugin, offering dynamic displays of recent customer orders, customizable content, and a personalized shopping experience for your customers.

### 🚀 Show Recent Orders on Website Frontend ###

This plugin enables you to display a list of customers' recent orders on the frontend of your WooCommerce store.

You can show recent orders using the "Recent Orders" widget or the `[alg_wc_recent_orders]` shortcode, providing a dynamic view of recent customer activity directly on your site.

### 🚀 Customizable Orders Display Limit ###

Configure the number of recent orders to display in the widget, while the default setting shows the five most recent orders, but you can adjust this number to suit your preferences.

This feature allows you to control how much of the recent order history is visible to your customers in a glance.

### 🚀 Personalized Section Content ###

Customize the content displayed before the recent orders list. Use available placeholders like `%my_account_url%`, `%orders_url%`, and `%user_display_name%` to add a personalized touch.

For example, greet users by name or provide a link to their account page, enhancing user engagement with a more tailored experience.

### 🚀 Detailed Customization for Each Order Row ###

Fine-tune the display for each order in the list with various placeholders like `%order_number%`, `%order_url%`, and `%order_total%`.

You can also control what appears after the list of recent orders. Use placeholders to provide links for further actions, like viewing more orders.

This level of detail ensures that customers can see key information about their recent purchases at a glance, such as order number, date, total, and status.

### ✅ More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

### 🗘 Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Head to the plugin [GitHub Repository](https://github.com/thanks-to-it/recent-orders-for-woocommerce) to find out how you can pitch in.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Recent Orders".

== Changelog ==

= 2.0.0 - 10/06/2025 =
* Fix - "Transients" option fixed.
* Dev - "Each order product" moved to the free plugin version.
* Dev - "Order statuses" moved to the free plugin version.
* Dev - Plugin settings moved back to the "WooCommerce > Settings" menu.
* Dev - "Recommendations" removed.
* Dev - "Key Manager" removed.
* Dev - Security - Output escaped.
* Dev - Coding standards improved.
* WC tested up to: 9.8.
* Tested up to: 6.8.

= 1.4.0 - 07/11/2024 =
* Dev - Plugin settings moved to the "WPFactory" menu.
* Dev - "Recommendations" added.
* Dev - "Key Manager" added.
* Dev - "High-Performance Order Storage (HPOS)" compatibility.
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring and cleanup.
* WC tested up to: 9.3.
* Tested up to: 6.6.
* `woocommerce` added to the "Requires Plugins" (plugin header).
* Plugin name updated.

= 1.3.4 - 31/07/2024 =
* WC tested up to: 9.1.
* Tested up to: 6.6.

= 1.3.3 - 26/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.
* Plugin icon, banner updated.

= 1.3.2 - 19/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.3.1 - 06/12/2022 =
* WC tested up to: 7.1.
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 1.3.0 - 07/11/2022 =
* Fix - Widget - "Order statuses" option was ignored in the widget. This is fixed now.
* Dev - "Each order product" option added.
* Dev - Shortcodes are now processed in the output.
* Dev - Transients - Better transient name: including `$args` hash now.
* Dev - Developers - Filters and actions added, e.g., `alg_wc_recent_orders_output`.

= 1.2.2 - 20/12/2021 =
* Dev - "Escaping late" now.

= 1.2.1 - 17/12/2021 =
* Dev - All output is escaped now.

= 1.2.0 - 17/12/2021 =
* Dev - Advanced Options - "Transients" options added.
* Dev - Widget added.
* Dev - Code refactoring.
* Plugin renamed to "Recent Orders Widget for WooCommerce" (was "Recent Orders for WooCommerce").
* Free plugin version released.
* WC tested up to: 6.0.

= 1.1.0 - 24/11/2021 =
* Dev - "Order statuses" option added.
* Dev - Localization - `load_plugin_textdomain()` function moved to the `init` action.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.
* WC tested up to: 5.9.
* Tested up to: 5.8.

= 1.0.1 - 11/03/2020 =
* Dev - "Order date format" option (and `order_date_format` shortcode attribute) added.
* WC tested up to: 4.0.

= 1.0.0 - 05/03/2020 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
