<?php
namespace cmsgears\shop\common\components;

// Yii Imports
use Yii;

class Shop extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialise the CMG Shop Component.
	 */
	public function init() {

		parent::init();

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cart ----------------------------------

	// Properties

	// Components and Objects

	public function registerComponents() {

		// Register services
		$this->registerEntityServices();

		// Init services
		$this->initEntityServices();
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\shop\common\services\interfaces\entities\IProductService', 'cmsgears\shop\common\services\entities\ProductService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'productService', 'cmsgears\shop\common\services\entities\ProductService' );
	}
}
