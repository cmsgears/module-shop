<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\frontend\controllers\base\Controller;

/**
 * ProductController provides actions specific to product model.
 *
 * @since 1.0.0
 */
class ProductController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $metaService;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Services
		$this->modelService	= Yii::$app->factory->get( 'productService' );
		$this->metaService	= Yii::$app->factory->get( 'productMetaService' );
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
					// Searching
					'auto-search' => [ 'permission' => $this->crudPermission ],
					// Avatar
					'avatar' => [ 'permission' => $this->crudPermission ],
					'clear-avatar' => [ 'permission' => $this->crudPermission ],
					// Banner
					'banner' => [ 'permission' => $this->crudPermission ],
					'clear-banner' => [ 'permission' => $this->crudPermission ],
					// Metas
					'add-meta' => [ 'permission' => $this->crudPermission ],
					'update-meta' => [ 'permission' => $this->crudPermission ],
					'toggle-meta' => [ 'permission' => $this->crudPermission ],
					'delete-meta' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ],
					// Categories
					'assign-category' => [ 'permission' => $this->crudPermission ],
					'remove-category' => [ 'permission' => $this->crudPermission ],
					// Tags
					'assign-tags' => [ 'permission' => $this->crudPermission ],
					'remove-tag' => [ 'permission' => $this->crudPermission ],
					// Gallery Items
					'get-gallery-item' => [ 'permission' => $this->crudPermission ],
					'create-gallery-item' => [ 'permission' => $this->crudPermission ],
					'update-gallery-item' => [ 'permission' => $this->crudPermission ],
					'delete-gallery-item' => [ 'permission' => $this->crudPermission ],
					// Data Object
					'set-data' => [ 'permission' => $this->crudPermission ],
					'remove-data' => [ 'permission' => $this->crudPermission ],
					'set-attribute' => [ 'permission' => $this->crudPermission ],
					'remove-attribute' => [ 'permission' => $this->crudPermission ],
					'set-config' => [ 'permission' => $this->crudPermission ],
					'remove-config' => [ 'permission' => $this->crudPermission ],
					'set-setting' => [ 'permission' => $this->crudPermission ],
					'remove-setting' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					// Searching
					'auto-search' => [ 'post' ],
					// Avatar
					'avatar' => [ 'post' ],
					'clear-avatar' => [ 'post' ],
					// Banner
					'banner' => [ 'post' ],
					'clear-banner' => [ 'post' ],
					// Metas
					'add-meta' => [ 'post' ],
					'update-meta' => [ 'post' ],
					'toggle-meta' => [ 'post' ],
					'delete-meta' => [ 'post' ],
					'settings' => [ 'post' ],
					// Categories
					'assign-category' => [ 'post' ],
					'remove-category' => [ 'post' ],
					// Tags
					'assign-tags' => [ 'post' ],
					'remove-tag' => [ 'post' ],
					// Gallery Items
					'get-gallery-item' => [ 'post' ],
					'create-gallery-item' => [ 'post' ],
					'update-gallery-item' => [ 'post' ],
					'delete-gallery-item' => [ 'post' ],
					// Data Object
					'set-data' => [ 'post' ],
					'remove-data' => [ 'post' ],
					'set-attribute' => [ 'post' ],
					'remove-attribute' => [ 'post' ],
					'set-config' => [ 'post' ],
					'remove-config' => [ 'post' ],
					'set-setting' => [ 'post' ],
					'remove-setting' => [ 'post' ],
					// Social
					'submit-review' => [ 'post' ],
					'like' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			// Searching
			'auto-search' => [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ],
			// Avatar
			'avatar' => [ 'class' => 'cmsgears\core\common\actions\content\Avatar' ],
			'clear-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\ClearAvatar' ],
			// Banner
			'banner' => [ 'class' => 'cmsgears\cms\common\actions\content\Banner' ],
			'clear-banner' => [ 'class' => 'cmsgears\core\common\actions\content\ClearBanner' ],
			// Metas
			'add-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Create' ],
			'update-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Update' ],
			'toggle-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Toggle' ],
			'delete-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Delete' ],
			'settings' => [ 'class' => 'cmsgears\core\common\actions\meta\UpdateMultiple' ],
			// Categories
			'assign-category' => [ 'class' => 'cmsgears\core\common\actions\category\Assign' ],
			'remove-category' => [ 'class' => 'cmsgears\core\common\actions\category\Remove' ],
			// Tags
			'assign-tags' => [ 'class' => 'cmsgears\core\common\actions\tag\Assign' ],
			'remove-tag' => [ 'class' => 'cmsgears\core\common\actions\tag\Remove' ],
			// Gallery Items
			'get-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\ReadItem' ],
			'create-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\CreateItem' ],
			'update-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\UpdateItem' ],
			'delete-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\DeleteItem' ],
			// Data Object
			'set-data' => [ 'class' => 'cmsgears\core\common\actions\data\SetData' ],
			'remove-data' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveData' ],
			'set-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\SetAttribute' ],
			'remove-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveAttribute' ],
			'set-config' => [ 'class' => 'cmsgears\core\common\actions\data\SetConfig' ],
			'remove-config' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveConfig' ],
			'set-setting' => [ 'class' => 'cmsgears\core\common\actions\data\SetSetting' ],
			'remove-setting' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveSetting' ],
			// Social
			'submit-review' => [ 'class' => 'cmsgears\core\common\actions\comment\Review' ],
			'like' => [ 'class' => 'cmsgears\core\common\actions\follower\Like' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductController ---------------------

}
