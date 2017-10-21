<?php
namespace cmsgears\shop\common\config;

/**
 * The ShopGlobal class defines the global constants and variables available for shop module.
 */
class ShopGlobal {

	// Permissions -----------------------------------------------------

	const PERM_SHOP	= 'shop';

	// Grouping by type ------------------------------------------------

	// Generic

	// Entities
	const TYPE_PRODUCT	= 'product';

	// Resources

	// Additional

	// Templates -------------------------------------------------------

	const TEMPLATE_DEFAULT	= 'default';
        
    // Notification Templates ------------------------------------------
        
    const TEMPLATE_NOTIFY_SUBMIT    = 'product-submit';
    const TEMPLATE_NOTIFY_RESUBMIT	= 'resubmit-product';
	const TEMPLATE_NOTIFY_UP_FREEZE	= 'uplift-product-freeze';
	const TEMPLATE_NOTIFY_UP_BLOCK	= 'uplift-product-block';
	const TEMPLATE_NOTIFY_REJECT	= 'reject-product';
	const TEMPLATE_NOTIFY_APPROVE	= 'approve-product';
	const TEMPLATE_NOTIFY_FREEZE	= 'freeze-product';
	const TEMPLATE_NOTIFY_BLOCKED	= 'block-product';
	
    // Notification Titles ---------------------------------------------
        
    const TITLE_REGISTERED		= 'Product Registered';
	const TITLE_RESUBMIT		= 'Product Resubmitted';
	const TITLE_UPLIFT_FREEZE	= 'Requested Uplift Freeze';
	const TITLE_UPLIFT_BLOCK	= 'Requested Uplift Block';
	const TITLE_REJECT          = 'Product Rejected';
	const TITLE_APPROVE         = 'Product Approved';
	const TITLE_FREEZE          = 'Product Frozen';
	const TITLE_BLOCKED         = 'Product Blocked';
}