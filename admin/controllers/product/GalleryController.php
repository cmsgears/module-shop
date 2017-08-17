<?php
namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\resources\Gallery;

class GalleryController extends \cmsgears\core\admin\controllers\base\GalleryController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $postService;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->type			= ShopGlobal::TYPE_PRODUCT;
		$this->parentUrl	= '/shop/product/all';

		// Services
		$this->parentService	= Yii::$app->factory->get( 'productService' );

		// Sidebar
		$this->sidebar		= [ 'parent' => 'sidebar-shop', 'child' => 'product' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'products' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/all/' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/product/all' ] ] ],
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

			$parent = $this->parentService->getById( $pid );

			Url::remember( [ $this->parentUrl ], 'galleries' );

			$gallery = $parent->gallery;

			if( isset( $gallery ) ) {

				return $this->redirect( [ 'items', 'id' => $gallery->id ] );
			}
			else {

				$gallery 			= new Gallery();
				$gallery->name		= $parent->name;
				$gallery->type		= $this->type;
				$gallery->siteId	= Yii::$app->core->siteId;

				if( $gallery->load( Yii::$app->request->post(), 'Gallery' )  && $gallery->validate() ) {

					$this->modelService->create( $gallery );

					if( $this->parentService->linkGallery( $parent, $gallery ) ) {

						$this->redirect( [ "index?pid=$parent->id" ] );
					}
				}

				$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

				return $this->render( 'create', [
						'model' => $gallery,
						'templatesMap' => $templatesMap
				]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
