<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\base\Migration;

use cmsgears\core\common\models\resources\Stats;
use cmsgears\shop\common\models\base\ShopTables;

/**
 * The shop stats migration insert the default row count for all the tables available in
 * shop module. A scheduled console job can be executed to update these stats.
 *
 * @since 1.0.0
 */
class m170829_050912_shop_stats extends Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Table Stats
		$this->insertTables();
	}

	private function insertTables() {

		$columns 	= [ 'tableName', 'type', 'count' ];

		$tableData	= [
			[ $this->prefix . 'shop_product', 'rows', 0 ],
			[ $this->prefix . 'shop_product_meta', 'rows', 0 ],
			[ $this->prefix . 'shop_product_follower', 'rows', 0 ],
			[ $this->prefix . 'shop_variation', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_stats', $columns, $tableData );
	}

	public function down() {

		Stats::deleteByTableName( ShopTables::getTableName( ShopTables::TABLE_PRODUCT ) );
		Stats::deleteByTableName( ShopTables::getTableName( ShopTables::TABLE_PRODUCT_META ) );
		Stats::deleteByTableName( ShopTables::getTableName( ShopTables::TABLE_PRODUCT_FOLLOWER ) );
		Stats::deleteByTableName( ShopTables::getTableName( ShopTables::TABLE_VARIATION ) );
	}

}
