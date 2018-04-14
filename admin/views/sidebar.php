<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'shop' ) && $user->isPermitted( ShopGlobal::PERM_SHOP_ADMIN ) ) { ?>
	<div id="sidebar-shop" class="collapsible-tab has-children <?= $parent == 'sidebar-shop' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-basket-air"></span></div>
			<div class="tab-title">Shop</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-shop' ? 'expanded visible' : null ?>">
			<ul>
				<li class="product <?= $child == 'product' ? 'active' : null ?>"><?= Html::a( 'Products', ['/shop/product/all'] ) ?></li>
				<li class="product-category <?= $child == 'product-category' ? 'active' : null ?>"><?= Html::a( 'Product Categories', ['/shop/product/category/all'] ) ?></li>
				<li class="product-tag <?= $child == 'product-tag' ? 'active' : null ?>"><?= Html::a( 'Product Tags', ['/shop/product/tag/all'] ) ?></li>
				<li class="product-reviews <?= $child == 'product-reviews' ? 'active' : null ?>"><?= Html::a( 'Product Reviews', ['/shop/product/review/all'] ) ?></li>
				<li class="product-template <?= $child == 'product-template' ? 'active' : null ?>"><?= Html::a( 'Product Templates', ['/shop/product/template/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
