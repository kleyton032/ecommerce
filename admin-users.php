<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;


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

 ?>