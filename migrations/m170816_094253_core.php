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
		$this->upProductVariation();
		$this->upProductPlan();

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
				'bannerId' => $this->bigInteger( 20 ),
				'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
				'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
				'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
				'visibility' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
				'summary' => $this->text(),
				'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
				'content' => $this->text(),
				'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'createdBy' => $this->bigInteger( 20 )->notNull(),
				'modifiedBy' => $this->bigInteger( 20 ),
				'createdAt' => $this->dateTime()->notNull(),
				'modifiedAt' => $this->dateTime()
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'product_banner', $this->prefix . 'cart_product', 'bannerId' );
	}

	private function upProductVariation() {

		$this->createTable( $this->prefix . 'cart_product_variation', [
				'id' => $this->bigPrimaryKey( 20 ),
				'productId' => $this->bigInteger( 20 ),
				'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
				'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
				'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'value' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
				'increment' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'product_variation', $this->prefix . 'cart_product_variation', 'productId' );
	}

	private function upProductPlan() {

		$this->createTable( $this->prefix . 'cart_product_plan', [
				'id' => $this->bigPrimaryKey( 20 ),
				'productId' => $this->bigInteger( 20 ),
				'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
				'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
				'price' => $this->double( 2 )->notNull()->defaultValue( 0 ),
				'period' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'trial' => $this->smallInteger( 6 )->defaultValue( 0 ),
				'interval' => $this->smallInteger( 6 )->defaultValue( 0 ),
		], $this->options );

		// Indexes
		$this->createIndex( 'idx_' . $this->prefix . 'product_plan', $this->prefix . 'cart_product_plan', 'productId' );
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
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_banner', $this->prefix . 'cart_product', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );

		// Product Variation
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_variation', $this->prefix . 'cart_product_variation', 'productId', $this->prefix . 'cart_product', 'id', 'SET NULL' );

		// Product Plan
		$this->addForeignKey( 'fk_' . $this->prefix . 'product_plan', $this->prefix . 'cart_product_plan', 'productId', $this->prefix . 'cart_product', 'id', 'SET NULL' );

		// Subscription
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'cart_sub', 'userId', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_product', $this->prefix . 'cart_sub', 'productId', $this->prefix . 'cart_product', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_plan', $this->prefix . 'cart_sub', 'planId', $this->prefix . 'cart_product_plan', 'id', 'SET NULL' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'cart_product' );
		$this->dropTable( $this->prefix . 'cart_product_variation' );
		$this->dropTable( $this->prefix . 'cart_product_plan' );
		$this->dropTable( $this->prefix . 'cart_coupon' );
		$this->dropTable( $this->prefix . 'cart_sub' );
	}

	private function dropForeignKeys() {

		// Product
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_banner', $this->prefix . 'cart_product' );

		// Product Variation
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_variation', $this->prefix . 'cart_product_variation' );

		// Product Plan
		$this->dropForeignKey( 'fk_' . $this->prefix . 'product_plan', $this->prefix . 'cart_product_plan' );

		// Subscription
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'cart_sub' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_product', $this->prefix . 'cart_sub' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_plan', $this->prefix . 'cart_sub' );
	}
}
