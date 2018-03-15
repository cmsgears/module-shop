<?php
namespace cmsgears\shop\common\components;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

class MessageSource extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		ShopGlobal::FIELD_PRODUCT => 'Product',
		ShopGlobal::FIELD_ADDON_PRODUCT => 'Addon Product'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}
