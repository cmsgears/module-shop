<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\components;

/**
 * Mailer triggers the mails provided by Shop Module.
 *
 * @since 1.0.0
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Global -----------------

	const MAIL_PRODUCT_CREATE	= 'product/create';
	const MAIL_PRODUCT_REGISTER	= 'product/register';

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-core/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-shop/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	public function sendCreateProductMail( $product ) {

		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		$user = $product->getOwner();

		$name	= $user->getName();
		$email	= $user->email;

		if( empty( $email ) ) {

			return;
		}

		// Send Mail
		$this->getMailer()->compose( self::MAIL_PRODUCT_CREATE, [ 'coreProperties' => $this->coreProperties, 'product' => $product, 'name' => $name, 'email' => $email ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Product Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "text" )
			->send();
	}

	public function sendRegisterProductMail( $product ) {

		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		$user = $product->getOwner();

		$name	= $user->getName();
		$email	= $user->email;

		if( empty( $email ) ) {

			return;
		}

		// Send Mail
		$this->getMailer()->compose( self::MAIL_PRODUCT_REGISTER, [ 'coreProperties' => $this->coreProperties, 'product' => $product, 'name' => $name, 'email' => $email ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Product Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "text" )
			->send();
	}

}
