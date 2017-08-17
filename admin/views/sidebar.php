<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'shop' ) && $user->isPermitted( ShopGlobal::PERM_SHOP ) ) { ?>
	<div id="sidebar-shop" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-shop' ) == 0 ) echo 'active';?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-cart"></span></div>
			<div class="tab-title">Shop</div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-shop' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='sidebar-shop <?php if( strcmp( $child, 'product' ) == 0 ) echo 'active';?>'><?= Html::a( "Products", ['/shop/product/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>