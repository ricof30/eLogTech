<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/contact', 'Home::contact');
// $routes->get('/messages', 'Home::message');
$routes->post('/add_contact', 'Home::add_contact');
$routes->get('/delete/(:num)', 'Home::delete_contact/$1');
$routes->get('/delete_message/(:num)', 'Home::deleteMessage/$1');
$routes->get('/deleteByPhone/(:num)', 'Home::deleteByPhoneNumber/$1');
$routes->get('/deleteSentMessage/(:num)', 'Home::deleteSentMessage/$1');
// $routes->get('/signin', 'Home::signin');
//$routes->post('/adminSignin', 'Home::adminSignin');
$routes->post('/update', 'Home::update');
$routes->post('/filterRainfall', 'Home::filterRainfall');
$routes->post('/filterWaterLevel', 'Home::filterWaterlevel');
// app/Config/Routes.php
$routes->get('/logout', 'Home::logout');
$routes->get('/status', 'Home::viewStatus');
$routes->get('/alertHistory', 'Home::alertHistory');
$routes->get('/weather', 'Home::weather');
$routes->post('/updatePhoto', 'Home::updatePhoto');
$routes->get('/sentMessage', 'Home::sentMessage');

// app/Config/Routes.php
// app/Config/Routes.php


$routes->get('/signin', 'Home::signin');
$routes->post('/adminSignin', 'Home::adminSignin');

$routes->group('/', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    // Define other dashboard routes here
});

// app/Config/Routes.php

$routes->group('/', function ($routes) {
    // Route to display all messages
    $routes->get('messages', 'Home::message');
    
    // Route to display messages for a specific phone number
    $routes->get('message/show/(:any)', 'Home::show/$1');
    
    // Route to delete a message
    // $routes->get('delete_message/(:num)', 'Home::deleteMessage/$1');
    
    // Add other routes as needed
});



