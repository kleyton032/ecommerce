<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index"); 

});

$app->get('/admin', function() {
    
    User::verifyLogin();

	$page = new PageAdmin();
	
	$page->setTpl("index"); 

});

$app->get("/admin/login", function(){
	$page = new PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("login");
});

$app->post("/admin/login", function(){

	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;	

});

$app->get("/admin/logout", function(){

	User::logout();
	header("Location: /admin/login");
	exit;	

});

//tela de listagem de usuários 
$app->get("/admin/users", function(){

	User::verifyLogin();
	$users = User::listAll();
	
	$page = new PageAdmin();
	
	$page->setTpl("users", array(
		"users"=> $users

	));

});

//tela cadastra usuário
$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");


});

//deleta usuário
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;


});

//rota da tela de alterar usuário
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

//cadastra usuário
$app->post("/admin/users/create", function(){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

//altera usuário
$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();	

	header("Location: /admin/users");

	exit;


});


$app->get("/admin/forgot", function(){

$page = new PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("forgot");

});

$app->post("/admin/forgot", function(){

	$user = User::getForgot($_POST["email"]);


});


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



$app->run();

?>