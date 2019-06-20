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
 * AttributeController provides actions specific to product attributes.
 *
 * @since 1.0.0
 */
class AttributeController extends \cmsgears\core\admin\controllers\base\AttributeController {

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
		$this->title	= 'Product Attribute';
		$this->apixBase	= 'shop/product/attribute';

		// Services
		$this->modelService		= Yii::$app->factory->get( 'productMetaService' );
		$this->parentService	= Yii::$app->factory->get( 'productService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product' ];

		// Return Url
		$this->returnUrl = Url::previous( 'product-attributes' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/attribute/all' ], true );

		// All Url
		$allUrl = Url::previous( 'products' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/shop/product/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Products', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Product Attributes' ] ],
			'create' => [ [ 'label' => 'Product Attributes', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Product Attributes', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Product Attributes', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AttributeController -------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'product-attributes' );

		return parent::actionAll( $pid );
	}

}
