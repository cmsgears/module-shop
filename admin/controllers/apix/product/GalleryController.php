<?php
namespace cmsgears\shop\admin\controllers\apix\product;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

class GalleryController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'createItem' => [ 'permission' => $this->crudPermission ],
					'deleteItem' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'createItem' => [ 'post' ],
					'deleteItem' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [

			'create-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\CreateItem' ],
			'delete-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\DeleteItem' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------
}
