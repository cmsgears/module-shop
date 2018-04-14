<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\components;

// CMG Imports
use cmsgears\core\common\base\Mailer as BaseMailer;

/**
 * Mailer triggers the mails provided by Shop Module.
 *
 * @since 1.0.0
 */
class Mailer extends BaseMailer {

	// Global -----------------

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-cms/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-cms/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-cms/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

}
