<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\components;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

/**
 * MessageSource stores and provide the messages and message templates available in
 * Shop Module.
 *
 * @since 1.0.0
 */
class MessageSource extends \cmsgears\core\common\base\MessageSource {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		ShopGlobal::FIELD_PRODUCT => 'Product',
		ShopGlobal::FIELD_ADDON_PRODUCT => 'Addon Product',
		ShopGlobal::FIELD_PRODUCT_VARIATION => 'Product Variation',
		ShopGlobal::FIELD_OFFER => 'Offer',
		ShopGlobal::FIELD_SHOP => 'Shop'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

}
