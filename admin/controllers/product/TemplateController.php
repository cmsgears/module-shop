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

/**
 * TemplateController provide actions specific to product templates.
 *
 * @since 1.0.0
 */
class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

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
		$this->type		= ShopGlobal::TYPE_PRODUCT;
		$this->apixBase = 'shop/product/template';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product-template' ];

		// Return Url
		$this->returnUrl = Url::previous( 'product-templates' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/product/all' ] ] ],
			'all' => [ [ 'label' => 'Product Templates' ] ],
			'create' => [ [ 'label' => 'Product Templates', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Product Templates', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Product Templates', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateController --------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'product-templates' );

		return parent::actionAll( $config );
	}

}
