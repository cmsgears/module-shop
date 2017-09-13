<?php
namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\resources\File;

class GalleryController extends \cmsgears\core\admin\controllers\base\GalleryController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-shop/admin/views/shop/gallery' );

		// Config
		$this->type			= ShopGlobal::TYPE_PRODUCT;
		$this->parentUrl	= '/shop/shop/all';

		// Services
		$this->parentService	= Yii::$app->factory->get( 'productService' );

		// Sidebar
		$this->sidebar		= [ 'parent' => 'sidebar-shop', 'child' => 'shop' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'products' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/shop/all/' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/shop/all' ] ] ],
			'index' => [ [ 'label' => 'Gallery' ] ],
			'items' => [ [ 'label' => 'Gallery', 'url' => $this->returnUrl ], [ 'label' => 'Items' ] ],
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

	public function actionIndex( $pid = null ) {

		if( isset( $pid ) && isset( $this->parentService ) ) {

			$product	= $this->parentService->getById( $pid );

			Url::remember( [ $this->parentUrl ], 'galleries' );

			$gallery = $product->gallery;

			if( isset( $gallery ) ) {

				$avatar	= isset( $product->avatar ) ? $product->avatar : File::loadFile( null, 'Avatar' );

				return $this->render( 'items', [
					'id' => $gallery->id,
					'avatar' => $avatar,
					'shop' => $product
				] );
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
