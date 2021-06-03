<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\ISimilar;
use cmsgears\core\common\services\interfaces\mappers\ICategory;
use cmsgears\cms\common\services\interfaces\base\IContentService;

/**
 * IProductService declares methods specific to product model.
 *
 * @since 1.0.0
 */
interface IProductService extends IContentService, ICategory, ISimilar {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model );

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
