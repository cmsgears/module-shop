<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\cms\admin\controllers\base\TagController as BaseTagController;

/**
 * TagController provides actions specific to product tags.
 *
 * @since 1.0.0
 */
class TagController extends BaseTagController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = ShopGlobal::PERM_PRODUCT_ADMIN;

		// Config
		$this->type			= ShopGlobal::TYPE_PRODUCT;
		$this->templateType	= ShopGlobal::TYPE_PRODUCT;
		$this->apixBase		= 'shop/tag';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product-tag' ];

		// Return Url
		$this->returnUrl = Url::previous( 'tags' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/tag/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/product/all' ] ] ],
			'all' => [ [ 'label' => 'Tags' ] ],
			'create' => [ [ 'label' => 'Tags', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Tags', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Tags', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TagController -------------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'tags' );

		return parent::actionAll( $config );
	}

}
