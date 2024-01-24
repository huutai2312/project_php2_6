<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart']

require_once(__DIR__ . '/public/router.php');
require_once(__DIR__ . '/app/controller/index.php');
require_once realpath("vendor/autoload.php");

use App\controller\Controller;

$router = new Router();
$router
    //==========================================================================
    //================================CLIENT====================================
    //==========================================================================
    ->get('/', [Controller::class, 'index'])
    ->get('/sendmail', [Controller::class, 'sendMail'])
    ->get('/cua-hang', [Controller::class, 'cuahang'])
    ->get('/san-pham', [Controller::class, 'productDetail'])
    ->get('/tai-khoan', [Controller::class, 'taikhoan'])
    ->get('/login', [Controller::class, 'login'])
    ->post('/loginUser', [Controller::class, 'loginUser'])
    ->get('/register', [Controller::class, 'register'])
    ->post('/register', [Controller::class, 'registerUser'])
    ->get('/logout', [Controller::class, 'logout'])
    ->post('/cart', [Controller::class, 'cart'])
    ->get('/cart', [Controller::class, 'cart'])
    ->get('/checkout', [Controller::class, 'checkout'])
    ->post('/checkout', [Controller::class, 'userOrder'])
    //==========================================================================
    //=================================ADMIN====================================
    //==========================================================================
    ->get('/admin', [Controller::class, 'adminIndex'])
    //+Product
    ->get('/admin/products', [Controller::class, 'adminProducts'])
    ->get('/admin/add_product', [Controller::class, 'adminAddProduct'])
    ->post('/admin/add_product', [Controller::class, 'adminAddProduct'])
    ->get('/admin/edit_product', [Controller::class, 'adminEditProduct'])
    ->get('/admin/update_product', [Controller::class, 'adminUpdateProduct'])
    ->post('/admin/update_product', [Controller::class, 'adminUpdateProduct'])
    ->get('/admin/delete_product', [Controller::class, 'adminDeleteProduct'])
    //+User
    ->get('/admin/users', [Controller::class, 'adminUsers'])
    ->get('/admin/add_user', [Controller::class, 'adminAddUser'])
    ->post('/admin/add_user', [Controller::class, 'adminAddUser'])
    ->get('/admin/edit_user', [Controller::class, 'adminEditUser'])
    ->get('/admin/update_user', [Controller::class, 'adminUpdateUser'])
    ->post('/admin/update_user', [Controller::class, 'adminUpdateUser'])
    ->get('/admin/delete_user', [Controller::class, 'adminDeleteUser'])
    //+Order
    ->get('/admin/orders', [Controller::class, 'adminOrders']);

echo $router->resolve(
    $_SERVER['REQUEST_URI'],
    strtolower($_SERVER['REQUEST_METHOD']),
    isset($_GET['id']) ? $_GET['id'] : null
);
