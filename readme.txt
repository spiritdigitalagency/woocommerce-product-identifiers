=== Woocommerce Product Identifier Fields (UPC, EAN, JAN, ISBN, and MPN) ===
Contributors: spiritdigitalagency, vpsnak
Tags: gtin, upc, ean, jan, isbn, mpn, product identifier fields, woocommerce, spirit digital agency
Donate link: https://github.com/sponsors/spiritdigitalagency
Requires at least: 3.9
Requires PHP: 5.4
Tested up to: 6.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides additional GTINs (Global Trade Item Numbers) fields, including UPC, EAN, JAN, ISBN, and MPN, for your WooCommerce store.

== Description ==

Woocommerce Product Identifier Fields provides additional identifiers for all WooCommerce Products. The product global identifier values (ie. UPC, EAN, JAN, ISBN, MPN) are searchable from both the front-end and back-end pages.

Marketing channels like Google Shopping (merchant center), Facebook, Skroutz and many more, requires you to add certain fields to the product feed you create for advertising or listing purposes. However not all of the required fields are present in WooCommerce. Our plugin add’s these fields / attributes for you so you can create a product feed that meets the requirements. The plugin will add the following product input fields for you: UPC, EAN, JAN, ISBN, MPN and has the ability to extend it with more.

### ✨ Features

* Add unique fields for product variations
* Ability to register new custom fields with code
* Expose identifier fields to search engines through WooCommerce structured data
* Suitable for product feeds like Google Shopping and more (requires a feed plugin)

### ⌨️ Extending the plugin

You can always tweak the amount of fields and their data to fit your needs by using `add_filter`. In the example below we will:

1. add a new field called ERP Code
2. disable JAN identifier
3. use an already existing meta for ean field

```
function custom_identifier_logic($fields){
	// add new field
	$fields['erp-code'] = array(
		'key'         => '_sda_erp_code',
		'label'       => 'ERP Code',
        'searchable'  => true,
		'description' => 'ERP software identifier of product'
	);
	// disable existing field
	unset($fields['jan']);
	// edit existing field to lookup an other meta key
	$fields['ean']['key'] = 'old_ean_meta_key';
	return $fields;
}
add_filter('sda_woocommerce_identifier_register_fields','custom_identifier');
```

### ✨ Our Mission

Offer free tooling to help store administrators manage their business easily and reliable without the need of paying fees or subscriptions for essential eCommerce features.

== Installation ==

__To install from your WordPress site__

1. Log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.
2. In the search field, type 'Woocommerce Product Identifier Fields' and click 'Search Plugins'. Select the plugin authored by 'Spirit Digital Agency'. You can install it by simply clicking 'Install Now'.

__To download and install plugin from out Github Repository__

[Spirit Digital Agency's Github repository](https://github.com/spiritdigitalagency/)

__Configure plugin for first use__

After plugin installed:

1. Go to settings page of the plugin.

== Frequently Asked Questions ==

= What is GTIN used for? =

Global Trade Item Number (GTIN) can be used by a company to uniquely identify all of its trade items. GS1 defines trade items as products or services that are priced, ordered or invoiced at any point in the supply chain.

= What is UPC code used for? =

A UPC, short for universal product code, is a type of code printed on retail product packaging to aid in identifying a particular item. It consists of two parts – the machine-readable barcode, which is a series of unique black bars, and the unique 12-digit number beneath it.

= What is an ISBN used for? =

An ISBN is essentially a product identifier used by publishers, booksellers, libraries, internet retailers and other supply chain participants for ordering, listing, sales records and stock control purposes. The ISBN identifies the registrant as well as the specific title, edition and format.

= What does a EAN do? =

The EAN Code is a type of barcode that encodes an article number. Originally, EAN codes were exclusively used to encode “European Article Numbers” (EANs). Since 2009 EAN codes have been used to encode GTINs – Global Trade Item Numbers.
What is the difference between a UPC and EAN?
These two formats are predominantly used in their own regions, the UPC is used only in the US and Canada, while the EAN is used everywhere else globally. The U.P.C. stands for Universal Product Code (aka: UPC-A) and E.A.N. stands for European Article Number (aka: EAN-13 or International Article Number)

= How to convert JAN to EAN? =

Any UPC can be converted into an EAN simply by adding a zero to the front. Japan uses the same standard, calling it a Japan Article Number (JAN). The codes used in Japan start with different digits than the ones used in Europe, so they are globally unique.


== Screenshots ==

1. ss1
2. ss2
3. ss3

== Changelog ==

= 2022-07-30 version 1.0.0 =

* Initial release
