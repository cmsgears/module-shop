<?php
namespace cmsgears\shop\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

class ProductController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------


	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission	= CoreGlobal::PERM_GALLERY_ADMIN;

		// Services
		$this->modelService	= Yii::$app->factory->get( 'productService' );

	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [

					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ],
					'avatar' => [ 'permission' => $this->crudPermission ],
					'assign-category' => [ 'permission' => $this->crudPermission, 'filters' => [ 'discover' ] ],
					'remove-category' => [ 'permission' => $this->crudPermission ],
					'assign-tags' => [ 'permission' => $this->crudPermission ],
					'remove-tag' => [ 'permission' => $this->crudPermission ],
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [

					'bulk' => [ 'post' ],
					'delete' => [ 'post' ],
					'avatar' => [ 'post' ],
					'assign-category' => [ 'post' ],
					'remove-category' => [ 'post' ],
					'assign-tags' => [ 'post' ],
					'remove-tag' => [ 'post' ],
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [

			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ],
			'assign-category' => [ 'class' => 'cmsgears\core\common\actions\category\Assign' ],
			'remove-category' => [ 'class' => 'cmsgears\core\common\actions\category\Remove' ],
			'assign-tags' => [ 'class' => 'cmsgears\core\common\actions\tag\Assign' ],
			'remove-tag' => [ 'class' => 'cmsgears\core\common\actions\tag\Remove' ],
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductController ---------------------

	public function actionAvatar( $id ) {

		$model	= $this->modelService->getById( $id );

		if( isset( $model ) ) {

			$avatar	= File::loadFile( null, 'Avatar' );

			$this->modelService->updateAvatar( $model, $avatar );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
