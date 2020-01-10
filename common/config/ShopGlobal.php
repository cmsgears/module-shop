<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\config;

/**
 * The ShopGlobal class defines the global constants and variables available for shop module.
 *
 * @since 1.0.0
 */
class ShopGlobal {

	// System Sites ---------------------------------------------------

	// System Pages ---------------------------------------------------

	const PAGE_SEARCH_PRODUCTS = 'shop';

	const PAGE_CART		= 'cart';
	const PAGE_CHECKOUT	= 'checkout';
	const PAGE_PAYMENT	= 'payment';

	// Grouping by type ------------------------------------------------

	const TYPE_PRODUCT = 'product';

	const TYPE_OFFER = 'offer';

	const TYPE_PRODUCT_VARIATION = 'product-variation';

	// Templates -------------------------------------------------------

	const TEMPLATE_CART		= 'cart';
	const TEMPLATE_CHECKOUT	= 'checkout';
	const TEMPLATE_PAYMENT	= 'payment';
	const TEMPLATE_SHOP		= 'shop';

	// Config ----------------------------------------------------------

	const CONFIG_SHOP = 'shop';

	// Roles -----------------------------------------------------------

	const ROLE_SHOP_ADMIN		= 'shop-admin';

	// Permissions -----------------------------------------------------

	// Shop
	const PERM_SHOP_ADMIN		= 'admin-shop';

	// Product
	const PERM_PRODUCT_ADMIN	= 'admin-products';

	const PERM_PRODUCT_MANAGE	= 'manage-products';
	const PERM_PRODUCT_AUTHOR	= 'product-author';

	const PERM_PRODUCT_VIEW		= 'view-products';
	const PERM_PRODUCT_ADD		= 'add-product';
	const PERM_PRODUCT_UPDATE	= 'update-product';
	const PERM_PRODUCT_DELETE	= 'delete-product';
	const PERM_PRODUCT_APPROVE	= 'approve-product';
	const PERM_PRODUCT_PRINT	= 'print-product';
	const PERM_PRODUCT_IMPORT	= 'import-products';
	const PERM_PRODUCT_EXPORT	= 'export-products';

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_PRODUCT				= 'productField';
	const FIELD_ADDON_PRODUCT		= 'addonProductField';
	const FIELD_PRODUCT_VARIATION	= 'productVariationField';

	const FIELD_SHOP	= 'shopField';

}
