## Description

The WooCommerce Cart Increase plugin allows WooCommerce store owners to configure products to increase a customer's cart by a given percentage when those products are added to the cart (see below for an example).

This project is for my application to become a Codeable Expert.

## Installation

1. Download the zip file and upload `woocommerce-cart-increase` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

After you've installed and activated the plugin, you can configure a global % increase for products that are configured to increase the cart total. This can be done by navigating to the WooCommerce Settings &rarr; Products tab &rarr; General section.

![WooCommerce Cart Increase - Global Setting](https://renventura.com/wp-content/uploads/2018/05/woocommerce-cart-increase-global-setting.jpg "WooCommerce Cart Increase - Global Setting")

Next, you can begin configuring products that will increase the cart total by a given percentage when added to the cart. To do this, navigate to the editor for one of your products. In the Product Data meta box, under the General tab, you'll find an option for enabling the increase for that product, and an input for the percentage.

![WooCommerce Cart Increase - Product Settings](https://renventura.com/wp-content/uploads/2018/05/woocommerce-cart-increase-product-settings.jpg "WooCommerce Cart Increase - Product Settings")

Remember that for both the global and product settings, percentages are expressed as whole numbers, and should not include the percent sign. For example, a 25% increase would be entered as just 25.

When the customer adds configured products to their cart, the plugin checks each product in the cart, retrieves the highest increase setting, and applies the increase as a fee.

For example, let's say you have three products:

1. Product A ($20) is configured to increase the cart by 20%.
2. Product B ($12) is configured to increase the cart, but you did not specify a percentage. You've set the global default to 10%, so that will apply for this product.
3. Product C ($8) is not configured to increase the cart.

Now, a customer adds all three products to his or her cart, for a cart total of $40. The plugin will check each of those three products, determine that Product A has the highest percentage increase (20% versus 10%), calculate the increase (0.2 X $40 = $8), and add that amount to the cart as a fee. This would make the cart total $48, exclusive of taxes and shipping.

## Bugs

If you find an issue, let me know!

## Changelog

__1.0__
* Initial version