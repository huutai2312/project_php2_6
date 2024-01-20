<?php

namespace App\controller;

use App\model\SanPham;
use App\model\Order;
use App\model\User;

// Hàm include tất cả các tệp trong một thư mục
class Controller
{
    public function importHeader()
    {
        include_once "../project_php2_5/app/view/inc/header.php";
    }

    public function importFooter()
    {
        include_once "../project_php2_5/app/view/inc/footer.php";
    }

    //Client===========================================
    public function index()
    {
        $this->importHeader();
        $sanPhamModel = new SanPham();
        $products = $sanPhamModel->getAllProducts();
        include "../project_php2_5/app/view/home.php";

        // Kiểm tra nếu có tham số query "success"
        if (isset($_GET['registerSuccess']) && $_GET['registerSuccess'] == 1) {
            echo "<script>alert('Đăng ký thành công, vui lòng đăng nhập lại tài khoản của bạn!');</script>";
        }

        if (isset($_GET['loginSuccess']) && $_GET['loginSuccess'] == 1) {
            echo "<script>alert('Đăng nhập thành công!');</script>";
        }
        $this->importFooter();
    }

    public function sendMail(){
        include "../project_php2_5/app/view/sendmail.php";
    }

    public function taiKhoan()
    {
        $userController = new UserController();
        $userController->taiKhoan();
    }

    public function login()
    {
        $userController = new UserController();
        $userController->login();
    }

    public function loginUser()
    {
        $userController = new UserController();
        $userController->loginUser();
    }

    public function register()
    {
        $userController = new UserController();
        $userController->register();
    }

    public function registerUser()
    {
        $userController = new UserController();
        $userController->registerUser();
    }

    public function checkUserExists($email)
    {
        $userController = new UserController();
        return $userController->checkUserExists($email);
    }

    public function logout()
    {
        $userController = new UserController();
        $userController->logout();
    }

    public function cuahang()
    {
        $shopController = new ShopController();
        $shopController->cuahang();
    }

    public function productDetail($product_id)
    {
        $productDetailController = new ProductDetailController();
        $productDetailController->productDetail($product_id);
    }

    public function cart()
    {
        $cartController = new CartController();
        $cartController->cart();
    }

    public function checkout()
    {
        $checkoutController = new CheckoutController();
        $checkoutController->checkout();
    }

    //Admin==================================================
    public function adminIndex()
    {
        session_start();
        $this->importHeader();
        $user = $_SESSION['user'];
        if ($user['is_admin'] == 1) {
            include "../project_php2_5//app/view/admin/index.php";
        } else {
            echo "<script>alert('Bạn không có quyền truy cập!')</script>";
            echo "<script>window.location.href='/';</script>";
            exit;
        }
        $this->importFooter();
    }
    public function adminProducts()
    {
        $adminProducts = new AdminProductsController();
        $adminProducts->adminProducts();
    }
    public function adminAddProduct()
    {
        $adminAddProducts = new AdminProductsController();
        $adminAddProducts->adminAddProduct();
    }
    public function adminOrders()
    {
        $adminOrders = new AdminOrdersController();
        $adminOrders->adminOrders();
    }
    
    public function adminEditProduct(){
        $adminEditProducts = new AdminProductsController();
        $adminEditProducts->adminEditProduct();
    }
    public function adminUpdateProduct(){
        $adminUpdateProducts = new AdminProductsController();
        $adminUpdateProducts->adminUpdateProduct();
    }
}
