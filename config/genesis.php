<?php

return [

	/*
	|--------------------------------------------------------------------------
	| usermodel
	|--------------------------------------------------------------------------
	|
	| here you set the path to your usermodel class
	|
	*/
	"usermodel"=>\App\User::class,

	/*
	|--------------------------------------------------------------------------
	| authprovider
	|--------------------------------------------------------------------------
	|
	| here you set the type of auth provider you are using. e.g. laravel, jwt...
	|
	*/
	"authprovider"=>"laravel",

	/*
	|--------------------------------------------------------------------------
	| Dynamic Dashboard Tables
	|--------------------------------------------------------------------------
	|
	| all the tables mentioned bellow will be visible in the admin dashboard
	|
	*/
	"dashboardTables"=>[
		(object)[
			'label'=>'Tests', // label of the table in the side navigation
			'value'=>'tests', //name of the actual table in the database
			'modelName'=>'Country', // name of the Model file associated with the table
			'hasCustom'=> false, // defaults is false, true if there is a custom view that needs to be used for a table
		],
	],
];