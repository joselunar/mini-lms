<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('', ['filter' => 'guest'], static function (RouteCollection $routes): void {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attempt');
});

$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Catalog::index');
    $routes->get('matriculas', 'Catalog::enrollments');
    $routes->get('cursos/(:num)', 'Catalog::show/$1');
    $routes->post('cursos/(:num)/matricular', 'Catalog::enroll/$1');
    $routes->get('cursos/(:num)/aulas/(:num)', 'Catalog::lesson/$1/$2');
    $routes->post('cursos/(:num)/aulas/(:num)/concluir', 'Catalog::complete/$1/$2');
});

$routes->post('api/login', 'Api\Auth::login');
$routes->get('api/cursos', 'Api\Courses::index');
$routes->get('api/cursos/(:num)', 'Api\Courses::show/$1');

$routes->group('api', ['filter' => 'apiAuth'], static function (RouteCollection $routes): void {
    $routes->post('matriculas', 'Api\Enrollments::create');
    $routes->get('matriculas', 'Api\Enrollments::index');
    $routes->post('aulas/(:num)/concluir', 'Api\Lessons::complete/$1');
});

$routes->group('api', ['filter' => ['apiAuth', 'admin']], static function (RouteCollection $routes): void {
    $routes->post('cursos', 'Api\Courses::create');
    $routes->post('cursos/(:num)/publicar', 'Api\Courses::publish/$1');
    $routes->post('cursos/(:num)/encerrar', 'Api\Courses::close/$1');
    $routes->post('cursos/(:num)/aulas', 'Api\Courses::addLesson/$1');
});
