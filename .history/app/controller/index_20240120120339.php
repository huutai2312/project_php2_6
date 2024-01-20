<?php

namespace App\controller;

use App\model\SanPham;
use App\model\Order;
use App\model\User;

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

    public function sendMail()
    {
        include "../project_php2_5/app/view/sendmail.php";
    }

    public function taiKhoan()
    {
        session_start();
        // Kiểm tra xem có session người dùng hay không
        if (isset($_SESSION['user'])) {
            // Nếu có, hiển thị trang "Tài Khoản" với thông tin người dùng
            $this->importHeader();
            include "../project_php2_5/app/view/tai-khoan.php";
            $this->importFooter();
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function login()
    {
        $this->importHeader();
        include "../project_php2_5/app/view/login.php";
        $this->importFooter();
    }

    public function loginUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                session_start();
                $_SESSION['user'] = $user;

                // Chuyển hướng về trang home với tham số query "loginSuccess"
                if ($user['is_admin'] == 1) {
                    // Nếu là admin, chuyển hướng đến trang admin
                    header("Location: /admin");
                } else {
                    // Nếu không phải admin, chuyển hướng về trang home
                    header("Location: /?loginSuccess=1");
                }
                exit();
            } else {
                // echo "<script>alert('Đăng nhập không thành công. Vui lòng kiểm tra lại thông tin đăng nhập.');</script>";
                header("Location: /login?loginFailed=1");
                exit();
            }
        }
    }

    public function register()
    {
        $this->importHeader();
        include "../project_php2_5/app/view/register.php";
        $this->importFooter();
    }

    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
                header("Location: /register?registerFailed=1");
                exit();
                // echo "Vui lòng điền đầy đủ thông tin!";
                // return;
            }

            // Ví dụ: Kiểm tra mật khẩu và xác nhận mật khẩu
            if ($password !== $confirmPassword) {
                header("Location: /register?registerFailed=2");
                exit();
                // echo "Mật khẩu và xác nhận mật khẩu không khớp!";
                // return;
            }

            if ($this->checkUserExists($email)) {
                header("Location: /register?registerFailed=3");
                exit();
                // echo "Email đã được sử dụng, vui lòng chọn email khác!";
                // return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Thêm thông tin người dùng vào cơ sở dữ liệu
            $userModel = new User();
            $userModel->registerUser($name, $email, $hashedPassword);

            // Điều hướng hoặc hiển thị thông báo thành công
            echo "<script>alert('Đăng ký thành công!')</script>";
            return;
        }

        // Nếu không phải là phương thức POST, hiển thị trang đăng ký
        $this->importHeader();
        include "../project_php2_5/app/view/register.php";
        $this->importFooter();
    }

    public function checkUserExists($email)
    {
        $userModel = new User();
        $existingUser = $userModel->getUserByEmail($email);

        return $existingUser !== false;
    }

    public function logout()
    {
        session_start();
        // Xóa toàn bộ thông tin người dùng khỏi session
        unset($_SESSION['user']);
        // Hủy toàn bộ session
        session_destroy();
        // Chuyển hướng về trang đăng nhập với thông báo đăng xuất thành công
        header("Location: /");
        exit();
    }

    public function cuahang()
    {
        $this->importHeader();
        $sanPhamModel = new SanPham();
        $products = $sanPhamModel->getAllProducts();
        include "../project_php2_5/app/view/cua-hang.php";
        $this->importFooter();
    }

    public function productDetail($product_id)
    {
        session_start();
        $this->importHeader();
        $sanPhamModel = new SanPham();
        $productDetail = $sanPhamModel->getProductById($product_id);
        if ($productDetail) {
            include "../project_php2_5/app/view/san-pham.php";
        } else {
            echo "<script>window.location.href = '/'</script>";
        }
        $this->importFooter();
    }

    public function cart()
    {
        session_start();

        $this->importHeader();
        $cartItems = $_SESSION['cart'] ?? [];
        include "../project_php2_5/app/view/cart.php";
        $this->importFooter();
    }

    public function checkout()
    {

        session_start();
        $this->importHeader();
        $cartItems = $_SESSION['cart'] ?? [];
        include "../project_php2_5/app/view/checkout.php";
        $this->importFooter();
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

    public function adminEditProduct()
    {
        $adminEditProducts = new AdminProductsController();
        $adminEditProducts->adminEditProduct();
    }
    public function adminUpdateProduct()
    {
        $adminUpdateProducts = new AdminProductsController();
        $adminUpdateProducts->adminUpdateProduct();
    }
}
