<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('about', 'PagesController@about');
Route::get('contact', 'PagesController@contact');

Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController'
]);

//Route::get('facebook', 'Auth\AuthController@redirectToFacebook');
//Route::get('login/facebook', 'Auth\AuthController@handleFacebookCallback');
Route::get('register/facebook', 'RegistrationController@redirectToFacebook');
Route::get('loginWith/facebook', 'RegistrationController@redirectToFacebook');
Route::get('login/facebook', 'RegistrationController@handleFacebookCallback');

//-----------------------------------------------
Route::get('register', 'RegistrationController@register');
Route::post('register', 'RegistrationController@postRegister');
Route::get('register/confirm/{token}', 'RegistrationController@confirmEmail');
Route::get('login', 'SessionsController@login');
Route::post('login', 'SessionsController@postLogin');
Route::get('logout', 'SessionsController@logout');
Route::get('/myAccount', ['middleware' => 'auth', 'uses' => 'UserController@editAccount']);
Route::patch('/myAccount', ['middleware' => 'auth', 'uses' => 'UserController@updateAccount']);
Route::get('/', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@reportList']);
Route::get('/home', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@reportList']);
Route::get('expenseReports', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@reportList']);
Route::get('settledExpenseReports', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@settledReportList']);
Route::get('expenseReports/create', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@createReport']);
Route::get('expenseReports/{id}', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@showReport']);
Route::get('expenseReports/{id}/close', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@closeReport']);
Route::post('expenseReports', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@storeReport']);
Route::post('expenseReports/delete', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@deleteReports']);
Route::get('expenseReports/update/{id}', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@editReport']);
Route::patch('expenseReports/update/{id}', ['middleware' => 'auth', 'uses' => 'ExpenseReportController@updateReport']);

Route::get('expenses/add/{reportId}', ['middleware' => 'auth', 'uses' => 'ExpenseController@createExpense']);
Route::post('expenses/add/{reportId}', ['middleware' => 'auth', 'uses' => 'ExpenseController@storeExpense']);
Route::get('expenses/edit/{expenseId}', ['middleware' => 'auth', 'uses' => 'ExpenseController@editExpense']);
Route::patch('expenses/edit/{expenseId}', ['middleware' => 'auth', 'uses' => 'ExpenseController@updateExpense']);
Route::post('expenses/delete', ['middleware' => 'auth', 'uses' => 'ExpenseController@deleteExpense']);

Route::get('settlements/{reportId}', ['middleware' => 'auth', 'uses' => 'SettlementController@show']);
Route::get('settlements', ['middleware' => 'auth', 'uses' => 'SettlementController@showAll']);
Route::post('settlements/{reportId}/add', ['middleware' => 'auth', 'uses' => 'SettlementController@store']);
Route::get('settlements/{reportId}/add', ['middleware' => 'auth', 'uses' => 'SettlementController@store']);
Route::post('settlements/{reportId}/complete', ['middleware' => 'auth', 'uses' => 'SettlementController@completeReportSettlements']);
Route::post('settlements/complete', ['middleware' => 'auth', 'uses' => 'SettlementController@completeUserSettlements']);

Route::post('addFriend', ['middleware' => 'auth', 'uses' => 'UserController@addFriend']);
Route::post('quickAddFriend', ['middleware' => 'auth', 'uses' => 'UserController@quickAddFriend']);
Route::get('friends', ['middleware' => 'auth', 'uses' => 'UserController@friendsList']);
Route::post('friends/delete', ['middleware' => 'auth', 'uses' => 'UserController@deleteFriends']);