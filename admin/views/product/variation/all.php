<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}s | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;
$baseUrl		= $this->context->baseUrl;

// View Templates
$moduleTemplates	= '@cmsgears/module-shop/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'baseUrl' => $baseUrl, 'add' => true, 'addUrl' => "create?pid={$product->id}", 'data' => [ 'product' => $product ],
	'title' => $this->title, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'type' => 'Type', 'title' => 'Title', 'template' => 'Template',
		'dtype' => 'Discount Type', 'price' => 'Price', 'discount' => 'Discount', 'total' => 'Total',
		'quantity' => 'Quantity', 'track' => 'Track', 'stock' => 'Stock', 'sold' => 'Sold', 'active' => 'Active',
		'sdate' => 'Start Date', 'edate' => 'End Date', 'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'model' => [ 'active' => 'Active', 'inactive' => 'Inactive' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'type' => [ 'title' => 'Type', 'type' => 'select', 'options' => $typeMap ],
		'dtype' => [ 'title' => 'Discount Type', 'type' => 'select', 'options' => $discountTypeMap ],
		'order' => [ 'title' => 'Order', 'type' => 'range' ],
		'price' => [ 'title' => 'price', 'type' => 'range' ],
		'total' => [ 'title' => 'Total', 'type' => 'range' ],
		'track' => [ 'title' => 'Track', 'type' => 'flag' ],
		'stock' => [ 'title' => 'Stock', 'type' => 'range' ],
		'sold' => [ 'title' => 'Sold', 'type' => 'range' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'model' => [ 'active' => 'Activate', 'inactive' => 'Deactivate', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x2', 'x2', null, null, null, null, null, null, null, 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) {
			$icon = "<div class='align align-center'><i class=\"$model->icon\"></i></div>" ; return $icon;
		}],
		'name' => 'Name',
		'template' => [ 'title' => 'Template', 'generate' => function( $model ) { return isset( $model->template ) ? $model->template->name : null; } ],
		'qty' => [ 'title' => 'Quantity', 'generate' => function( $model ) { return $model->quantity . ' ' . $model->unit->name; } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'price' => 'Price',
		'total' => 'Total',
		'track' => [ 'title' => 'Track', 'generate' => function( $model ) { return $model->getTrackStr(); } ],
		'stock' => 'Stock',
		'sold' => 'Sold',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/variation",
	//'cardView' => "$moduleTemplates/grid/cards/variation",
	'actionView' => "$moduleTemplates/grid/actions/variation"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "shop/product/variation/bulk" ]
])?>

<?= Popup::widget([
	'title' => "Delete $title", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "shop/product/variation/delete?id=" ]
])?>
