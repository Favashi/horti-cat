<?php
// routes/auth.php

use App\Interfaces\Controllers\AuthController;
use Slim\Routing\RouteCollectorProxy;

/** @var RouteCollectorProxy $group */

$group->post('/login', [AuthController::class, 'login']);
