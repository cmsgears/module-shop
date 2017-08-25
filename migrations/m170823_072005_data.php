<?php
// Yii Imports
use yii\db\Migration;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\utilities\DateUtil;

class m170823_072005_data extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	// Entities

	private $master;

	// Config

	private $siteMaster;

	public function init() {

		$this->siteMaster		= Yii::$app->migration->getSiteMaster();
		$this->prefix			= Yii::$app->migration->cmgPrefix;

		$this->master			= User::findByUsername( $this->siteMaster );
	}

	public function up() {

		// RBAC
		$this->insertRolePermission();
	}

	private function insertRolePermission() {

		// Roles

		$superAdminRole	= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );


		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
				[ $this->master->id, $this->master->id, 'Shop', 'shop', CoreGlobal::TYPE_SYSTEM, null, 'The permission Shop is to manage shop from admin and website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$shopPerm			= Permission::findBySlugType( 'shop', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
				[ $superAdminRole->id, $shopPerm->id ],
				[ $adminRole->id, $shopPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}
}
