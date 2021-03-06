<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}s | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;
$baseUrl		= $this->context->baseUrl;
$reviews		= $this->context->reviews;

// View Templates
$moduleTemplates	= '@cmsgears/module-shop/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'baseUrl' => $baseUrl, 'add' => true, 'addUrl' => 'create', 'data' => [ 'reviews' => $reviews ],
	'title' => $this->title, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'summary' => 'Summary', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title', 'status' => 'Status', 'template' => 'Template',
		'shop' => 'Shop', 'price' => 'Price', 'total' => 'Total',
		'visibility' => 'Visibility', 'order' => 'Order', 'pinned' => 'Pinned', 'featured' => 'Featured',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'status' => [
			'submitted' => 'Submitted', 'rejected' => 'Rejected', 're-submitted' => 'Re Submitted',
			'confirmed' => 'Confirmed', 'active' => 'Active', 'frozen' => 'Frozen', 'uplift-freeze' => 'Uplift Freeze',
			'blocked' => 'Blocked', 'uplift-block' => 'Uplift Block', 'terminated' => 'Terminated'
		],
		'model' => [ 'shop' => 'Shop', 'pinned' => 'Pinned', 'featured' => 'Featured' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'summary' => [ 'title' => 'Summary', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'visibility' => [ 'title' => 'Visibility', 'type' => 'select', 'options' => $visibilityMap ],
		'order' => [ 'title' => 'Order', 'type' => 'range' ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ],
		'shop' => [ 'title' => 'Shop', 'type' => 'flag' ],
		'price' => [ 'title' => 'price', 'type' => 'range' ],
		'total' => [ 'title' => 'Total', 'type' => 'range' ],
		'track' => [ 'title' => 'Track', 'type' => 'flag' ],
		'stock' => [ 'title' => 'Stock', 'type' => 'range' ],
		'sold' => [ 'title' => 'Sold', 'type' => 'range' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'confirm' => 'Confirm', 'approve' => 'Approve', 'reject' => 'Reject', 'activate' => 'Activate', 'freeze' => 'Freeze', 'block' => 'Block', 'terminate' => 'Terminate' ],
		'model' => [ 'pinned' => 'Pinned', 'featured' => 'Featured', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x2', 'x2', null, null, null, null, null, null, null, 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) {
			$icon = "<div class='align align-center'><i class=\"$model->icon\"></i></div>" ; return $icon;
		}],
		'name' => 'Name',
		'template' => [ 'title' => 'Template', 'generate' => function( $model ) { return $model->modelContent->getTemplateName(); } ],
		'qty' => [ 'title' => 'Quantity', 'generate' => function( $model ) {
			return isset( $model->purchasingUnit ) ? $model->purchase . ' ' . $model->purchasingUnit->name : null;
		}],
		'shop' => [ 'title' => 'Shop', 'generate' => function( $model ) { return $model->getShopStr(); } ],
		'price' => 'Price',
		'total' => 'Total',
		'visibility' => [ 'title' => 'Visibility', 'generate' => function( $model ) { return $model->getVisibilityStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/product",
	//'cardView' => "$moduleTemplates/grid/cards/product",
	'actionView' => "$moduleTemplates/grid/actions/product"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => "Delete $title", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
