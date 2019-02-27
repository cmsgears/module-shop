<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\admin;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\base\Module as BaseModule;

/**
 * The Admin Module of Shop Module.
 *
 * @since 1.0.0
 */
class Module extends BaseModule {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $controllerNamespace = 'cmsgears\shop\admin\controllers';

	public $config = [ ShopGlobal::CONFIG_SHOP ];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-shop/admin/views' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

	public function getSidebarHtml() {

		$path	= Yii::getAlias( '@cmsgears' ) . '/module-shop/admin/views/sidebar.php';

		return $path;
	}

}
