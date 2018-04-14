<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\components;

// Yii Imports
use Yii;
use yii\base\Component;

/**
 * Shop component register the services provided by Shop Module.
 *
 * @since 1.0.0
 */
class Shop extends Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialize the services.
	 */
	public function init() {

		parent::init();

		// Register components and objects
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cart ----------------------------------

	// Properties ----------------

	// Components and Objects ----

	/**
	 * Register the services.
	 */
	public function registerComponents() {

		// Register services
		$this->registerEntityServices();
		$this->registerMapperServices();
		$this->registerResourceServices();

		// Init services
		$this->initEntityServices();
		$this->initMapperServices();
		$this->initResourceServices();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory	= Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\shop\common\services\interfaces\resources\IProductMetaService', 'cmsgears\shop\common\services\resources\ProductMetaService' );
		$factory->set( 'cmsgears\shop\common\services\interfaces\resources\IProductVariationService', 'cmsgears\shop\common\services\resources\ProductVariationService' );
	}

	/**
	 * Registers mapper services.
	 */
	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\shop\common\services\interfaces\mappers\IProductFollowerService', 'cmsgears\shop\common\services\mappers\ProductFollowerService' );
	}

	/**
	 * Registers entity services.
	 */
	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\shop\common\services\interfaces\entities\IProductService', 'cmsgears\shop\common\services\entities\ProductService' );
	}

	/**
	 * Initialize resource services.
	 */
	public function initResourceServices() {

		$factory	= Yii::$app->factory->getContainer();

		$factory->set( 'productMetaService', 'cmsgears\shop\common\services\resources\ProductMetaService' );
		$factory->set( 'productVariationService', 'cmsgears\shop\common\services\resources\ProductVariationService' );
	}

	/**
	 * Initialize mapper services.
	 */
	public function initMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'productFollowerService', 'cmsgears\shop\common\services\mappers\ProductFollowerService' );
	}

	/**
	 * Initialize entity services.
	 */
	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'productService', 'cmsgears\shop\common\services\entities\ProductService' );
	}

}
