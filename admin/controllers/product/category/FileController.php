<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\admin\controllers\product\category;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

/**
 * FileController provides actions specific to category files.
 *
 * @since 1.0.0
 */
class FileController extends \cmsgears\core\admin\controllers\base\FileController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = ShopGlobal::PERM_SHOP_ADMIN;

		// Config
		$this->title	= 'Category File';
		$this->apixBase	= 'shop/category/file';

		// Services
		$this->parentService = Yii::$app->factory->get( 'categoryService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product-category' ];

		// Return Url
		$this->returnUrl = Url::previous( 'product-category-files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/category/file/all' ], true );

		// All Url
		$allUrl = Url::previous( 'product-categories' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/shop/product/category/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Product Categories', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Category Files' ] ],
			'create' => [ [ 'label' => 'Category Files', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Category Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Category Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'product-category-files' );

		return parent::actionAll( $pid );
	}

}
