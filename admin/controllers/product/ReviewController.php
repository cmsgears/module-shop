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

use cmsgears\core\common\models\resources\ModelComment;

/**
 * ReviewController provides actions specific to product comments.
 *
 * @since 1.0.0
 */
class ReviewController extends \cmsgears\core\admin\controllers\base\CommentController {

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
		$this->parentType	= ShopGlobal::TYPE_PRODUCT;
		$this->commentType	= ModelComment::TYPE_COMMENT;
		$this->apixBase		= 'shop/product/review';
		$this->title		= 'Review';
		$this->parentUrl	= '/shop/product/update?id=';
		$this->urlKey		= 'product-reviews';

		// Services
		$this->parentService = Yii::$app->factory->get( 'productService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product-reviews' ];

		// Return Url
		$this->returnUrl = Url::previous( $this->urlKey );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/review/all' ], true );

		// All Url
		$allUrl = Url::previous( 'products' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/shop/product/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [ [ 'label' => 'Products', 'url' =>  $allUrl ] ],
			'all' => [ [ 'label' => 'Product Reviews' ] ],
			'create' => [ [ 'label' => 'Product Reviews', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Product Reviews', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Product Reviews', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ReviewController ----------------------

}
