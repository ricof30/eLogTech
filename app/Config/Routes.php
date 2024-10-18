<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/dashboard', 'Home::index');
$routes->get('/contact', 'Home::contact');
$routes->post('/add_contact', 'Home::add_contact');
$routes->get('/delete/(:num)', 'Home::delete_contact/$1');
$routes->get('/delete_message/(:num)', 'Home::deleteMessage/$1');
$routes->get('/deleteByPhone/(:num)', 'Home::deleteByPhoneNumber/$1');
$routes->get('/deleteSentMessage/(:num)', 'Home::deleteSentMessage/$1');
$routes->get('/notifications', 'Home::fetchNotifications');
$routes->post('/update', 'Home::update');
$routes->post('/filterRainfall', 'Home::filterRainfall');
$routes->post('/filterWaterLevel', 'Home::filterWaterlevel');
$routes->get('/logout', 'Home::logout');
$routes->get('/status', 'Home::viewStatus');
$routes->get('/alertHistory', 'Home::alertHistory');
$routes->get('/weather', 'Home::weather');
$routes->post('/updateProfile', 'Home::updateProfile');
$routes->get('/sentMessage', 'Home::sentMessage');
$routes->get('/signin', 'Home::signin');
$routes->post('/adminSignin', 'Home::adminSignin');
$routes->post('/signUp', 'Home::signUp');
$routes->get('google-login', 'Home::googleAuth');
$routes->get('google-callback', 'Home::callback');
$routes->get('/verify_otp', 'Home::otp');
$routes->post('verifyOTP', 'Home::verifyOTP');
$routes->get('/weather', 'Home::getWeather');
$routes->get('/user', 'Home::getUser');
$routes->get('push-notification/generate-keys', 'Home::generateKeys');
$routes->get('/getMessages', 'Home::getMessages');
$routes->post('/sendMessage', 'Home::sendMessage');



$routes->get('/', 'Home::userDashboard');
// $routes->get('weather_view', 'Home::weather');



$routes->group('/', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
});
$routes->group('/', function ($routes) {
    $routes->get('messages', 'Home::message');
    $routes->get('show', 'Home::show/$1');
});


    $routes->post('/sensor-data', 'Home::receiveData');



