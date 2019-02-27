<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The shop index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m161015_050842_shop_index extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Product
		$this->createIndex( 'idx_' . $this->prefix . 'product_name', $this->prefix . 'shop_product', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_slug', $this->prefix . 'shop_product', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_type', $this->prefix . 'shop_product', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'product_icon', $this->prefix . 'shop_product', 'icon' );

		// Product Meta
		$this->createIndex( 'idx_' . $this->prefix . 'product_meta_name', $this->prefix . 'shop_product_meta', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'product_meta_type', $this->prefix . 'shop_product_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'product_meta_label', $this->prefix . 'shop_product_meta', 'label' );
		//$this->createIndex( 'idx_' . $this->prefix . 'product_meta_vtype', $this->prefix . 'shop_product_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'product_meta_mit', $this->prefix . 'shop_product_meta', [ 'modelId', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'product_meta_mitn', $this->prefix . 'shop_product_meta', [ 'modelId', 'type', 'name' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'shop_product_meta' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'product_meta_search' . '(name ASC, value ASC)' );

		// Variation
		$this->createIndex( 'idx_' . $this->prefix . 'variation_name', $this->prefix . 'shop_variation', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'variation_type', $this->prefix . 'shop_variation', 'type' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Product
		$this->dropIndex( 'idx_' . $this->prefix . 'product_name', $this->prefix . 'shop_product' );
		$this->dropIndex( 'idx_' . $this->prefix . 'product_slug', $this->prefix . 'shop_product' );
		$this->dropIndex( 'idx_' . $this->prefix . 'product_type', $this->prefix . 'shop_product' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'product_icon', $this->prefix . 'shop_product' );

		// Product Meta
		$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_name', $this->prefix . 'shop_product_meta' );
		$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_type', $this->prefix . 'shop_product_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_label', $this->prefix . 'shop_product_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_vtype', $this->prefix . 'shop_product_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_mit', $this->prefix . 'shop_product_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_mitn', $this->prefix . 'shop_product_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'product_meta_search', $this->prefix . 'shop_product_meta' );

		// Variation
		$this->dropIndex( 'idx_' . $this->prefix . 'variation_name', $this->prefix . 'shop_variation' );
		$this->dropIndex( 'idx_' . $this->prefix . 'variation_type', $this->prefix . 'shop_variation' );
	}

}
