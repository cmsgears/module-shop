<?php
namespace cmsgears\shop\common\config;

/**
 * The ShopGlobal class defines the global constants and variables available for shop module.
 *
 * @since 1.0.0
 */
class ShopGlobal {

	// System Sites ---------------------------------------------------

	// System Pages ---------------------------------------------------

	// Grouping by type ------------------------------------------------

	const TYPE_PRODUCT	= 'product';

	// Templates -------------------------------------------------------

    const TEMPLATE_NOTIFY_SUBMIT    = 'product-submit';
    const TEMPLATE_NOTIFY_RESUBMIT	= 'resubmit-product';
	const TEMPLATE_NOTIFY_UP_FREEZE	= 'uplift-product-freeze';
	const TEMPLATE_NOTIFY_UP_BLOCK	= 'uplift-product-block';
	const TEMPLATE_NOTIFY_REJECT	= 'reject-product';
	const TEMPLATE_NOTIFY_APPROVE	= 'approve-product';
	const TEMPLATE_NOTIFY_FREEZE	= 'freeze-product';
	const TEMPLATE_NOTIFY_BLOCKED	= 'block-product';

    const TITLE_REGISTERED		= 'Product Registered';
	const TITLE_RESUBMIT		= 'Product Resubmitted';
	const TITLE_UPLIFT_FREEZE	= 'Requested Uplift Freeze';
	const TITLE_UPLIFT_BLOCK	= 'Requested Uplift Block';
	const TITLE_REJECT          = 'Product Rejected';
	const TITLE_APPROVE         = 'Product Approved';
	const TITLE_FREEZE          = 'Product Frozen';
	const TITLE_BLOCKED         = 'Product Blocked';

	// Config ----------------------------------------------------------

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

	const FIELD_PRODUCT			= 'productField';
	const FIELD_ADDON_PRODUCT	= 'addonProductField';

}
