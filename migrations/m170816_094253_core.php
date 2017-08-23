<?php

use yii\db\Migration;

class m170816_094253_core extends Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Product
		$this->upProduct();
		$this->upProductMeta();
		$this->upProductVariation();

		// Coupon/Voucher
		$this->upCoupon();

		// Subscription
		$this->upSubscription();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upProduct() {

		$this->createTable( $this->prefix . 'cart_product', [
				'id' => $this->bigPrimaryKey( 20 ),
				'avatarId' => $this->bigInteger( 20 ),
				'galleryId' => $this->bigInteger( 20 ),
				'uomId' => $this->bigInteger( 20 ),
				'primaryUnitId' => $this->bigInteger( 20 ),
				'purchasingUnitId' => $this->bigInteger( 20 ),
				'quantityUnitId' => $this->bigInteger( 20 ),
				'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'active' => $this->boolean()->notNull()->defaultValue( false ),
				'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
				'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
				'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
				'visibility' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
				'summary' => $this->text(),
				'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
				'sku' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
				'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'discount' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'discount' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'primary' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'purchase' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'quantity' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'total' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'weight' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'volume' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'length' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'width' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'height' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'radius' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'createdBy' => $this->bigInteger( 20 )->notNull(),
				'modifiedBy' => $this->bigInteger( 20 ),
				'createdAt' => $this->dateTime()->notNull(),
				'modifiedAt' => $this->dateTime(),
				'startDate' => $this->date()->defaultValue( null ),
				'endDate' => $this->date()->defaultValue( null ),
				'shop' => $this->boolean()->notNull()->defaultValue( false ),
				'content' => $this->text(),
				'data' => $this->text()
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'product_avatar', $this->prefix . 'cart_product', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_gallery', $this->prefix . 'cart_product', 'galleryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_prim', $this->prefix . 'cart_product', 'primaryUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_pur', $this->prefix . 'cart_product', 'purchasingUnitId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_uom', $this->prefix . 'cart_product', 'uomId' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_creator', $this->prefix . 'cart_product', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_modifier', $this->prefix . 'cart_product', 'modifiedBy' );
	}

	private function upProductMeta() {

		$this->createTable( $this->prefix . 'cart_product_meta', [
				'id' => $this->bigPrimaryKey( 20 ),
				'modelId' => $this->bigInteger( 20 )->notNull(),
				'name' => $this->string( Yii::$app->core->mediumText )->notNull(),
				'label' => $this->string( Yii::$app->core->largeText )->notNull(),
				'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'default' ),
				'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'text' ),
				'value' => $this->text()
		], $this->options );

		// Index for columns site, parent, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'product_meta_parent', $this->prefix . 'cart_product_meta', 'modelId' );
	}

	private function upProductVariation() {

		$this->createTable( $this->prefix . 'cart_product_variation', [
				'id' => $this->bigPrimaryKey( 20 ),
				'modelId' => $this->bigInteger( 20 ),
				'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
				'quantity' => $this->float( 2 )->notNull()->defaultValue( 0 ),
				'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
				'value' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'startDate' => $this->date(),
				'endDate' => $this->date(),
				'active' => $this->boolean()->notNull()->defaultValue( false ),
				'content' => $this->text(),
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'product_variation', $this->prefix . 'cart_product_variation', 'modelId' );
	}

	private function upCoupon() {

		$this->createTable( $this->prefix . 'cart_coupon', [
				'id' => $this->bigPrimaryKey( 20 ),
				'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
				'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
				'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
				'amount' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'taxType' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'shippingType' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'minPurchase' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'maxDiscount' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'createdAt' => $this->dateTime()->notNull(),
				'modifiedAt' => $this->dateTime(),
				'expireAt' => $this->dateTime(),
				'usageLimit' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'usageCount' => $this->smallInteger( 6 )->defaultValue( 0 )
		], $this->options );
	}

	private function upSubscription() {

		$this->createTable( $this->prefix . 'cart_sub', [
				'id' => $this->bigPrimaryKey( 20 ),
				'userId' => $this->bigInteger( 20 ),
				'productId' => $this->bigInteger( 20 ),
				'planId' => $this->bigInteger( 20 ),
				'period' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'trial' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'interval' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'startDate' => $this->dateTime()->notNull(),
				'lastPaymentDate' => $this->dateTime()->defaultValue( null ),
				'nextPaymentDate' => $this->dateTime()->defaultValue( null )
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_user', $this->prefix . 'cart_sub', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_product', $this->prefix . 'cart_sub', 'productId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_plan', $this->prefix . 'cart_sub', 'planId' );
	}

	private function generateForeignKeys() {

		// Product
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_avatar', $this->prefix . 'cart_product', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_gallery', $this->prefix . 'cart_product', 'galleryId', $this->prefix . 'core_gallery', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_prim', $this->prefix . 'cart_product', 'primaryUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_pur', $this->prefix . 'cart_product', 'purchasingUnitId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_uom', $this->prefix . 'cart_product', 'uomId', $this->prefix . 'cart_uom', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_creator', $this->prefix . 'cart_product', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_modifier', $this->prefix . 'cart_product', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Product Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_meta_parent', $this->prefix . 'cart_product_meta', 'modelId', $this->prefix . 'cart_product', 'id', 'CASCADE' );

		// Product Variation
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_variation', $this->prefix . 'cart_product_variation', 'modelId', $this->prefix . 'cart_product', 'id', 'SET NULL' );

		// Subscription
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'cart_sub', 'userId', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_product', $this->prefix . 'cart_sub', 'productId', $this->prefix . 'cart_product', 'id', 'SET NULL' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'cart_product' );
		$this->dropTable( $this->prefix . 'cart_product_meta' );
		$this->dropTable( $this->prefix . 'cart_product_variation' );
		$this->dropTable( $this->prefix . 'cart_coupon' );
		$this->dropTable( $this->prefix . 'cart_sub' );
	}

	private function dropForeignKeys() {

		// Product
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_avatar', $this->prefix . 'cart_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_gallery', $this->prefix . 'cart_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_prim', $this->prefix . 'cart_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_pur', $this->prefix . 'cart_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_uom', $this->prefix . 'cart_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_creator', $this->prefix . 'cart_product' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_modifier', $this->prefix . 'cart_product' );

		// Product Variation
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_variation', $this->prefix . 'cart_product_variation' );

		// Subscription
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'cart_sub' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_product', $this->prefix . 'cart_sub' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_plan', $this->prefix . 'cart_sub' );

		// Product Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_meta_parent', $this->prefix . 'cart_product_meta' );
	}
}
