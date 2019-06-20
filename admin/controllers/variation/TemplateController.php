<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\admin\controllers\variation;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

/**
 * TemplateController provide actions specific to variation templates.
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
		$this->type		= ShopGlobal::TYPE_PRODUCT_VARIATION;
		$this->apixBase = 'shop/variation/template';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'variation-template' ];

		// Return Url
		$this->returnUrl = Url::previous( 'variation-templates' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/variation/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [ [ 'label' => 'Product Variations', 'url' =>  [ '/shop/product/variation/all' ] ] ],
			'all' => [ [ 'label' => 'Variation Templates' ] ],
			'create' => [ [ 'label' => 'Variation Templates', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Variation Templates', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Variation Templates', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
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

		Url::remember( Yii::$app->request->getUrl(), 'variation-templates' );

		return parent::actionAll( $config );
	}

}
