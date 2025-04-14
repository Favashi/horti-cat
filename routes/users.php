<?php
// routes/users.php

use App\Interfaces\Controllers\UserController;
use Slim\Routing\RouteCollectorProxy;

/** @var RouteCollectorProxy $protected */
$protected->get('/users', [UserController::class, 'getUsers']);
