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
use cmsgears\cms\common\config\CmsGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;
use cmsgears\cms\common\models\entities\Page;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The shop data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m161015_050749_shop_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

	public function up() {

		// RBAC
		$this->insertRolePermission();

		// Create product permission groups and CRUD permissions
		$this->insertProductPermissions();
		$this->insertOfferPermissions();

		// Create various config
		$this->insertShopConfig();

		// Init default config
		$this->insertDefaultConfig();

		// Init system pages
		$this->insertSystemPages();

		$this->insertNotificationTemplates();
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Shop Admin', 'shop-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Shop Admin is limited to manage shop from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$shopAdminRole	= Role::findBySlugType( 'shop-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Shop', 'admin-shop', CoreGlobal::TYPE_SYSTEM, null, 'The permission Admin Shop is to manage shop from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Admin Products', 'admin-products', CoreGlobal::TYPE_SYSTEM, null, 'The permission Admin Products is to manage products from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Admin Offers', 'admin-offers', CoreGlobal::TYPE_SYSTEM, null, 'The permission Admin Offers is to manage offers from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$shopAdminPerm	= Permission::findBySlugType( 'admin-shop', CoreGlobal::TYPE_SYSTEM );
		$prodAdminPerm	= Permission::findBySlugType( 'admin-products', CoreGlobal::TYPE_SYSTEM );
		$offerAdminPerm	= Permission::findBySlugType( 'admin-offers', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $shopAdminPerm->id ], [ $superAdminRole->id, $prodAdminPerm->id ], [ $superAdminRole->id, $offerAdminPerm->id ],
			[ $adminRole->id, $shopAdminPerm->id ], [ $adminRole->id, $prodAdminPerm->id ], [ $adminRole->id, $offerAdminPerm->id ],
			[ $shopAdminRole->id, $adminPerm->id ], [ $shopAdminRole->id, $userPerm->id ], [ $shopAdminRole->id, $shopAdminPerm->id ],
			[ $shopAdminRole->id, $prodAdminPerm->id ], [ $shopAdminRole->id, $offerAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertProductPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Products', 'manage-products', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage products allows user to manage products from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Product Author', 'product-author', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission product author allows user to perform crud operations of product belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Product Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Products', 'view-products', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view products allows users to view their products from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Product', 'add-product', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add product allows users to create product from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Product', 'update-product', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update product allows users to update product from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Product', 'delete-product', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete product allows users to delete product from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Product', 'approve-product', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve product allows user to approve, freeze or block product from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Product', 'print-product', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print product allows user to print product from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Products', 'import-products', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import products allows user to import products from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Products', 'export-products', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export products allows user to export products from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$prodManagerPerm	= Permission::findBySlugType( 'manage-products', CoreGlobal::TYPE_SYSTEM );
		$prodAuthorPerm		= Permission::findBySlugType( 'product-author', CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vProductsPerm		= Permission::findBySlugType( 'view-products', CoreGlobal::TYPE_SYSTEM );
		$aProductPerm		= Permission::findBySlugType( 'add-product', CoreGlobal::TYPE_SYSTEM );
		$uProductPerm		= Permission::findBySlugType( 'update-product', CoreGlobal::TYPE_SYSTEM );
		$dProductPerm		= Permission::findBySlugType( 'delete-product', CoreGlobal::TYPE_SYSTEM );
		$apProductPerm		= Permission::findBySlugType( 'approve-product', CoreGlobal::TYPE_SYSTEM );
		$pProductPerm		= Permission::findBySlugType( 'print-product', CoreGlobal::TYPE_SYSTEM );
		$iProductsPerm		= Permission::findBySlugType( 'import-products', CoreGlobal::TYPE_SYSTEM );
		$eProductsPerm		= Permission::findBySlugType( 'export-products', CoreGlobal::TYPE_SYSTEM );

		//Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// Product Manager - Organization, Approver
			[ null, null, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $prodManagerPerm->id, $vProductsPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 17 ],
			[ $prodManagerPerm->id, $aProductPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 3, 16 ],
			[ $prodManagerPerm->id, $uProductPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 15 ],
			[ $prodManagerPerm->id, $dProductPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 5, 14 ],
			[ $prodManagerPerm->id, $apProductPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 13 ],
			[ $prodManagerPerm->id, $pProductPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 7, 12 ],
			[ $prodManagerPerm->id, $iProductsPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 11 ],
			[ $prodManagerPerm->id, $eProductsPerm->id, $prodManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 9, 10 ],

			// Product Author- Individual
			[ null, null, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $prodAuthorPerm->id, $vProductsPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 15 ],
			[ $prodAuthorPerm->id, $aProductPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 3, 14 ],
			[ $prodAuthorPerm->id, $uProductPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 13 ],
			[ $prodAuthorPerm->id, $dProductPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 5, 12 ],
			[ $prodAuthorPerm->id, $pProductPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 11 ],
			[ $prodAuthorPerm->id, $iProductsPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 7, 10 ],
			[ $prodAuthorPerm->id, $eProductsPerm->id, $prodAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertOfferPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Offers', 'manage-offers', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage offers allows user to manage offers from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Offer Author', 'offer-author', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission offer author allows user to perform crud operations of offer belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Offer Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Offers', 'view-offers', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view offers allows users to view their offers from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Offer', 'add-offer', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add offer allows users to create offer from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Offer', 'update-offer', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update offer allows users to update offer from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Offer', 'delete-offer', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete offer allows users to delete offer from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Offer', 'approve-offer', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve offer allows user to approve, freeze or block offer from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Offer', 'print-offer', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print offer allows user to print offer from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Offers', 'import-offers', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import offers allows user to import offers from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Offers', 'export-offers', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export offers allows user to export offers from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$offerManagerPerm	= Permission::findBySlugType( 'manage-offers', CoreGlobal::TYPE_SYSTEM );
		$offerAuthorPerm	= Permission::findBySlugType( 'offer-author', CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vOffersPerm	= Permission::findBySlugType( 'view-offers', CoreGlobal::TYPE_SYSTEM );
		$aOfferPerm		= Permission::findBySlugType( 'add-offer', CoreGlobal::TYPE_SYSTEM );
		$uOfferPerm		= Permission::findBySlugType( 'update-offer', CoreGlobal::TYPE_SYSTEM );
		$dOfferPerm		= Permission::findBySlugType( 'delete-offer', CoreGlobal::TYPE_SYSTEM );
		$apOfferPerm	= Permission::findBySlugType( 'approve-offer', CoreGlobal::TYPE_SYSTEM );
		$pOfferPerm		= Permission::findBySlugType( 'print-offer', CoreGlobal::TYPE_SYSTEM );
		$iOffersPerm	= Permission::findBySlugType( 'import-offers', CoreGlobal::TYPE_SYSTEM );
		$eOffersPerm	= Permission::findBySlugType( 'export-offers', CoreGlobal::TYPE_SYSTEM );

		//Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// Offer Manager - Organization, Approver
			[ null, null, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $offerManagerPerm->id, $vOffersPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 17 ],
			[ $offerManagerPerm->id, $aOfferPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 3, 16 ],
			[ $offerManagerPerm->id, $uOfferPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 15 ],
			[ $offerManagerPerm->id, $dOfferPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 5, 14 ],
			[ $offerManagerPerm->id, $apOfferPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 13 ],
			[ $offerManagerPerm->id, $pOfferPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 7, 12 ],
			[ $offerManagerPerm->id, $iOffersPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 11 ],
			[ $offerManagerPerm->id, $eOffersPerm->id, $offerManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 9, 10 ],

			// Offer Author- Individual
			[ null, null, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $offerAuthorPerm->id, $vOffersPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 15 ],
			[ $offerAuthorPerm->id, $aOfferPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 3, 14 ],
			[ $offerAuthorPerm->id, $uOfferPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 13 ],
			[ $offerAuthorPerm->id, $dOfferPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 5, 12 ],
			[ $offerAuthorPerm->id, $pOfferPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 11 ],
			[ $offerAuthorPerm->id, $iOffersPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 7, 10 ],
			[ $offerAuthorPerm->id, $eOffersPerm->id, $offerAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertShopConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Shop', 'slug' => 'config-shop',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Shop configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-shop', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'active','Active', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Enable/disable shop."}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'valueType', 'value' ];

		$metas	= [
			[ $this->site->id, 'active', 'Active', 'shop', 'flag', '1' ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	private function insertSystemPages() {

		$columns = [ 'siteId', 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'title', 'status', 'visibility', 'order', 'featured', 'comments', 'createdAt', 'modifiedAt' ];

		$pages	= [
			[ $this->site->id, $this->master->id, $this->master->id, 'Cart', ShopGlobal::PAGE_CART, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $this->master->id, $this->master->id, 'Checkout', ShopGlobal::PAGE_CHECKOUT, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $this->master->id, $this->master->id, 'Payment', ShopGlobal::PAGE_PAYMENT, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $this->master->id, $this->master->id, 'Shop', ShopGlobal::PAGE_SHOP, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $this->master->id, $this->master->id, 'Offers', ShopGlobal::PAGE_OFFERS, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			// Search Pages
			[ $this->site->id, $this->master->id, $this->master->id, 'Search Products', ShopGlobal::PAGE_SEARCH_PRODUCTS, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $this->master->id, $this->master->id, 'Search Offers', ShopGlobal::PAGE_SEARCH_OFFERS, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'cms_page', $columns, $pages );

		$summary = "Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It\'s also called placeholder (or filler) text. It\'s a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero.";
		$content = "Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It\'s also called placeholder (or filler) text. It\'s a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero.";

		$columns = [ 'parentId', 'parentType', 'seoName', 'seoDescription', 'seoKeywords', 'seoRobot', 'summary', 'content', 'publishedAt' ];

		$pages	= [
			[ Page::findBySlugType( ShopGlobal::PAGE_CART, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			[ Page::findBySlugType( ShopGlobal::PAGE_CHECKOUT, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			[ Page::findBySlugType( ShopGlobal::PAGE_PAYMENT, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			[ Page::findBySlugType( ShopGlobal::PAGE_SHOP, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			[ Page::findBySlugType( ShopGlobal::PAGE_OFFERS, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			// Search Pages
			[ Page::findBySlugType( ShopGlobal::PAGE_SEARCH_PRODUCTS, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			[ Page::findBySlugType( ShopGlobal::PAGE_SEARCH_OFFERS, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'cms_model_content', $columns, $pages );
	}

	private function insertNotificationTemplates() {

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'description', 'active', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'createdAt', 'modifiedAt', 'message', 'content', 'data' ];

		$templates = [
			// Product
			[ $this->master->id, $this->master->id, 'New Product', ShopGlobal::TPL_NOTIFY_PRODUCT_NEW, null, 'notification', 'Trigger Notification and Email to Admin, when new Product is created by site users.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'New Product - <b>{{model.displayName}}</b>', 'New Product - <b>{{model.displayName}}</b> has been submitted for registration. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ],
			// Offer
			[ $this->master->id, $this->master->id, 'New Offer', ShopGlobal::TPL_NOTIFY_OFFER_NEW, null, 'notification', 'Trigger Notification and Email to Admin, when new Product is created by site users.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'New Offer - <b>{{model.displayName}}</b>', 'New Offer - <b>{{model.displayName}}</b> has been submitted for registration. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

    public function down() {

        echo "m161015_050749_shop_data will be deleted with m160621_014408_core.\n";

        return true;
    }

}
