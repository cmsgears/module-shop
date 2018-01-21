<?php
namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

class CategoryController extends \cmsgears\core\admin\controllers\base\CategoryController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	public $templateType;

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission	= ShopGlobal::PERM_SHOP;

		$this->type				= ShopGlobal::TYPE_PRODUCT;
		$this->templateType		= ShopGlobal::TEMPLATE_DEFAULT;

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-shop', 'child' => 'category' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'categories' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/category/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/product/all' ] ] ],
			'all' => [ [ 'label' => 'Categories' ] ],
			'create' => [ [ 'label' => 'Categories', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Categories', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Categories', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryController --------------------

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'categories' );

		return parent::actionAll();
	}
}
