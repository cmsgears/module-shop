<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\models\base;

// CMG Imports
use cmsgears\core\common\models\base\DbTables;

/**
 * It provide table name constants of db tables available in Shop Module.
 *
 * @since 1.0.0
 */
class ShopTables extends DbTables {

	// Entities -------------

	// Product
	const TABLE_PRODUCT			= 'cmg_shop_product';

	// Resources ------------

	// Product
	const TABLE_PRODUCT_META	= 'cmg_shop_product_meta';

	// Variation
	const TABLE_PRODUCT_VARIATION = 'cmg_shop_variation';

	// Mappers --------------

	const TABLE_PRODUCT_FOLLOWER = 'cmg_shop_product_follower';

}
