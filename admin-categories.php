<?php 

use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;

$app->get("/admin/categories", function(){

		User::verifyLogin();

		$categories = Category::listAll();

		$page = new PageAdmin();

		$page->setTpl("categories", [
			'categories'=> $categories
		]);

});

//tela pra listar todas as categorias
$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

		$page->setTpl("categories-create");


});

//cadastra categoria
$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});

//deleta categoria
$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");
	exit;

});

//carrega tela atualiza categoria
$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update",[
		'category'=>$category->getValues()

	]);

});


$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});



$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", [
		'category'=>$category->getValues(),
		'products'=>[]
	]);

});


 ?>