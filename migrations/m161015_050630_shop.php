<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Meta;

/**
 * The shop migration inserts the database tables of shop module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m161015_050630_shop extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk		= Yii::$app->migration->isFk();
		$this->options	= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Product
		$this->upProduct();
		$this->upProductMeta();
		$this->upProductFollower();

		// Variation
		$this->upVariation();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upProduct() {

		$this->createTable( $this->prefix . 'shop_product', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'avatarId' => $this->bigInteger( 20 ),
			'primaryUnitId' => $this->bigInteger( 20 ),
			'purchasingUnitId' => $this->bigInteger( 20 ),
			'quantityUnitId' => $this->bigInteger( 20 ),
			'weightUnitId' => $this->bigInteger( 20 ),
			'volumeUnitId' => $this->bigInteger( 20 ),
			'lengthUnitId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( CoreGlobal::TYPE_DEFAULT ),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'texture' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'visibility' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'sku' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ), // Ideal for Vendor Code
			'code' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ), // Useful for Shop Code
			'price' => $this->double()->notNull()->defaultValue( 0 ),
			'discount' => $this->double()->defaultValue( 0 ),
			'total' => $this->float()->notNull()->defaultValue( 0 ),
			'primary' => $this->float()->defaultValue( 0 ),
			'purchase' => $this->float()->notNull()->defaultValue( 0 ),
			'quantity' => $this->float()->defaultValue( 0 ),
			'weight' => $this->float()->defaultValue( 0 ),
			'volume' => $this->float()->defaultValue( 0 ),
			'length' => $this->float()->defaultValue( 0 ),
			'width' => $this->float()->defaultValue( 0 ),
			'height' => $this->float()->defaultValue( 0 ),
			'radius' => $this->float()->defaultValue( 0 ),
			'track' => $this->boolean()->defaultValue( false ),
			'stock' => $this->float()->defaultValue( 0 ),
			'sold' => $this->float()->defaultValue( 0 ),
			'warn' => $this->float()->defaultValue( 0 ),
			'shop' => $this->boolean()->notNull()->defaultValue( false ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'reviews' => $this->boolean()->notNull()->defaultValue( false ),
			'startDate' => $this->date()->defaultValue( null ),
			'endDate' => $this->date()->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'shopNotes' => $this->mediumText()->defaultValue( null ),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'product_site', $this->prefix . 'shop_product', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_avatar', $this->prefix . 'shop_product', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_prim', $this->prefix . 'shop_product', 'primaryUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_pur', $this->prefix . 'shop_product', 'purchasingUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_qty', $this->prefix . 'shop_product', 'quantityUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_weight', $this->prefix . 'shop_product', 'weightUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_volume', $this->prefix . 'shop_product', 'volumeUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_length', $this->prefix . 'shop_product', 'lengthUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_creator', $this->prefix . 'shop_product', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_modifier', $this->prefix . 'shop_product', 'modifiedBy' );
	}

	private function upProductMeta() {

		$this->createTable( $this->prefix . 'shop_product_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns site, parent, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'product_meta_parent', $this->prefix . 'shop_product_meta', 'modelId' );
	}

	private function upProductFollower() {

        $this->createTable( $this->prefix . 'shop_product_follower', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'type' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText()
        ], $this->options );

        // Index for columns user and model
		$this->createIndex( 'idx_' . $this->prefix . 'product_follower_user', $this->prefix . 'shop_product_follower', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_follower_parent', $this->prefix . 'shop_product_follower', 'modelId' );
	}

	private function upVariation() {

		$this->createTable( $this->prefix . 'shop_variation', [
			'id' => $this->bigPrimaryKey( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'productId' => $this->bigInteger( 20 )->notNull(),
			'addonId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'galleryId' => $this->bigInteger( 20 ),
			'unitId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'discountType' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'price' => $this->float()->defaultValue( 0 ),
			'discount' => $this->float()->defaultValue( 0 ),
			'total' => $this->float()->defaultValue( 0 ),
			'quantity' => $this->float()->defaultValue( 0 ),
			'free' => $this->float()->defaultValue( 0 ),
			'track' => $this->boolean()->defaultValue( false ),
			'stock' => $this->float()->defaultValue( 0 ),
			'sold' => $this->float()->defaultValue( 0 ),
			'warn' => $this->float()->defaultValue( 0 ),
			'active' => $this->boolean()->defaultValue( false ),
			'startDate' => $this->date(),
			'endDate' => $this->date(),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'variation_template', $this->prefix . 'shop_variation', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_product', $this->prefix . 'shop_variation', 'productId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_addon', $this->prefix . 'shop_variation', 'addonId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_banner', $this->prefix . 'shop_variation', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_video', $this->prefix . 'shop_variation', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_gallery', $this->prefix . 'shop_variation', 'galleryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_unit', $this->prefix . 'shop_variation', 'unitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_creator', $this->prefix . 'shop_variation', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_modifier', $this->prefix . 'shop_variation', 'modifiedBy' );
	}

	private function generateForeignKeys() {

		// Product
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_site', $this->prefix . 'shop_product', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_avatar', $this->prefix . 'shop_product', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_prim', $this->prefix . 'shop_product', 'primaryUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_pur', $this->prefix . 'shop_product', 'purchasingUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_qty', $this->prefix . 'shop_product', 'quantityUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_weight', $this->prefix . 'shop_product', 'weightUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_volume', $this->prefix . 'shop_product', 'volumeUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_length', $this->prefix . 'shop_product', 'lengthUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_creator', $this->prefix . 'shop_product', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_modifier', $this->prefix . 'shop_product', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Product Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_meta_parent', $this->prefix . 'shop_product_meta', 'modelId', $this->prefix . 'shop_product', 'id', 'CASCADE' );

		// Product Follower
        $this->addForeignKey( 'fk_' . $this->prefix . 'product_follower_user', $this->prefix . 'shop_product_follower', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'productpage_follower_parent', $this->prefix . 'shop_product_follower', 'modelId', $this->prefix . 'cms_page', 'id', 'CASCADE' );

		// Product Variation
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_template', $this->prefix . 'shop_variation', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_product', $this->prefix . 'shop_variation', 'productId', $this->prefix . 'shop_product', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_addon', $this->prefix . 'shop_variation', 'addonId', $this->prefix . 'shop_product', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_banner', $this->prefix . 'shop_variation', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_video', $this->prefix . 'shop_variation', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_gallery', $this->prefix . 'shop_variation', 'galleryId', $this->prefix . 'core_gallery', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_unit', $this->prefix . 'shop_variation', 'unitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_creator', $this->prefix . 'shop_variation', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'variation_modifier', $this->prefix . 'shop_variation', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'shop_product' );
		$this->dropTable( $this->prefix . 'shop_product_meta' );
		$this->dropTable( $this->prefix . 'shop_product_follower' );

		$this->dropTable( $this->prefix . 'shop_variation' );
	}

	private function dropForeignKeys() {

		// Product
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_site', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_avatar', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_prim', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_pur', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_qty', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_weight', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_volume', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_length', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_creator', $this->prefix . 'shop_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_modifier', $this->prefix . 'shop_product' );

		// Product Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_meta_parent', $this->prefix . 'shop_product_meta' );

		// Product Follower
        $this->dropForeignKey( 'fk_' . $this->prefix . 'product_follower_user', $this->prefix . 'shop_product_follower' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'productpage_follower_parent', $this->prefix . 'shop_product_follower' );

		// Variation
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_template', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_product', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_addon', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_banner', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_video', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_gallery', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_unit', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_creator', $this->prefix . 'shop_variation' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'variation_modifier', $this->prefix . 'shop_variation' );
	}

}
