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

/**
 * The Shop Factory component initialise the services available in Shop Module.
 *
 * @since 1.0.0
 */
class Factory extends \cmsgears\core\common\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerEntityServices();
		$this->registerMapperServices();
		$this->registerResourceServices();
	}

	public function registerServiceAlias() {

		$this->registerEntityAliases();
		$this->registerMapperAliases();
		$this->registerResourceAliases();
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
	 * Registers resource aliases.
	 */
	public function registerResourceAliases() {

		$factory	= Yii::$app->factory->getContainer();

		$factory->set( 'productMetaService', 'cmsgears\shop\common\services\resources\ProductMetaService' );
		$factory->set( 'productVariationService', 'cmsgears\shop\common\services\resources\ProductVariationService' );
	}

	/**
	 * Registers mapper aliases.
	 */
	public function registerMapperAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'productFollowerService', 'cmsgears\shop\common\services\mappers\ProductFollowerService' );
	}

	/**
	 * Registers entity aliases.
	 */
	public function registerEntityAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'productService', 'cmsgears\shop\common\services\entities\ProductService' );
	}

}
