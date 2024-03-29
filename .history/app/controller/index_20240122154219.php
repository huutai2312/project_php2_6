<?php

namespace App\controller;

use App\model\database;
use App\model\SanPham;
use App\model\Order;
use App\model\User;
use App\model\Cart;
use App\model\Checkout;

class Controller
{
    public function importHeader()
    {
        include_once "../project_php2_6/app/view/inc/header.php";
    }

    public function importFooter()
    {
        include_once "../project_php2_6/app/view/inc/footer.php";
    }

    //Client===========================================
    public function index()
    {
        $this->importHeader();
        $sanPhamModel = new SanPham();
        $products = $sanPhamModel->getAllProducts();
        include "../project_php2_6/app/view/home.php";

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
        include "../project_php2_6/app/view/sendmail.php";
    }

    public function taiKhoan()
    {
        session_start();
        // Kiểm tra xem có session người dùng hay không
        if (isset($_SESSION['user'])) {
            // Nếu có, hiển thị trang "Tài Khoản" với thông tin người dùng
            $this->importHeader();
            include "../project_php2_6/app/view/tai-khoan.php";
            $this->importFooter();
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function login()
    {
        $this->importHeader();
        include "../project_php2_6/app/view/login.php";
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
        include "../project_php2_6/app/view/register.php";
        $this->importFooter();
    }

    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $date_created = date('d-m-Y H:i:s');

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
            $userModel->registerUser($name, $email, $hashedPassword, $date_created);

            // Điều hướng hoặc hiển thị thông báo thành công
            echo "<script>alert('Đăng ký thành công!')</script>";
            return;
        }

        // Nếu không phải là phương thức POST, hiển thị trang đăng ký
        $this->importHeader();
        include "../project_php2_6/app/view/register.php";
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
        include "../project_php2_6/app/view/cua-hang.php";
        $this->importFooter();
    }

    public function productDetail($product_id)
    {
        // session_start();
        $this->importHeader();
        $sanPhamModel = new SanPham();
        $productDetail = $sanPhamModel->getProductById($product_id);
        if ($productDetail) {
            include "../project_php2_6/app/view/san-pham.php";
        } else {
            echo "<script>window.location.href = '/'</script>";
        }
        $this->importFooter();
    }

    public function addToCart()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addToCart'])) {
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $quantity = $_POST['quantity'];

            $cartModel = new Cart();

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
            $index = $cartModel->checkCart($product_id);

            if ($index !== -1) {
                // Nếu sản phẩm đã tồn tại, cập nhật số lượng
                $_SESSION['cart'][$index][1] += $quantity;
            } else {
                // Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
                $_SESSION['cart'][] = [$product_id, $quantity, $product_name, $product_price];
            }
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'product_name' => $product_name,
                'product_price' => $product_price,
            ];
            // Chuyển hướng người dùng về trang giỏ hàng
            header('Location: /cart');
            exit();
        }
    }


    public function cart()
    {
        // session_start();

        $this->importHeader();
        $cartItems = $_SESSION['cart'] ?? [];
        var_dump($_SESSION['cart'])
        include "../project_php2_6/app/view/cart.php";
        $this->importFooter();
    }

    public function checkout()
    {
        session_start();
        $this->importHeader();
        $cartItems = $_SESSION['cart'] ?? [];
        include "../project_php2_6/app/view/checkout.php";
        $this->importFooter();
    }

    public function userOrder()
    {
        session_start();

        // Lấy thông tin từ form
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $postal_code = $_POST['postal_code'];
        $payment = $_POST['payment'];
        // $total_price = $_SESSION['total_price']; // Lấy từ session hoặc tính toán lại tùy vào cách bạn thực hiện

        // Thực hiện lưu vào database
        $checkoutModel = new Checkout();
        $checkoutModel->saveOrder($email, $first_name, $last_name, $company, $address, $phone, $city, $country, $postal_code, $payment);
    }


    //Admin==================================================
    public function adminIndex()
    {
        session_start();
        $this->importHeader();
        $user = $_SESSION['user'];
        if ($user['is_admin'] == 1) {
            include "../project_php2_6//app/view/admin/index.php";
        } else {
            echo "<script>alert('Bạn không có quyền truy cập!')</script>";
            echo "<script>window.location.href='/';</script>";
            exit;
        }
        $this->importFooter();
    }
    public function adminProducts()
    {
        session_start();
        $this->importHeader();
        $sanPhamModel = new SanPham();
        $products = $sanPhamModel->getAllProductsDesc();
        include "../project_php2_6/app/view/admin/products.php";
        $this->importFooter();
    }

    public function adminAddProduct()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $name = $_POST['name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $image = $_FILES['image']['name'];
            $shortDesc = $_POST['short_desc'];
            $longDesc = $_POST['long_desc'];

            // Xử lý tải lên hình ảnh
            // Kiểm tra xem người dùng đã chọn hình ảnh hay chưa
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "public/uploads/";
                $targetFile = $targetDir . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            // Thực hiện gọi phương thức từ model để thêm sản phẩm vào cơ sở dữ liệu
            $productModel = new SanPham();
            $productModel->adminAddProduct($name, $price, $quantity, $image, $shortDesc, $longDesc);

            // Chuyển hướng về trang danh sách sản phẩm sau khi thêm thành công
            header("Location: /admin/products");
            exit;
        }

        $this->importHeader();
        include "../project_php2_6/app/view/admin/add_product.php";
        $this->importFooter();
    }

    public function adminEditProduct()
    {
        session_start();
        $this->importHeader();
        // Lấy thông tin sản phẩm từ cơ sở dữ liệu dựa trên ID
        $id = $_GET['id'];
        $sanPhamModel = new SanPham();
        $product = $sanPhamModel->getProductById($id);
        include "../project_php2_6/app/view/admin/edit_product.php";
        $this->importFooter();
    }

    public function adminUpdateProduct()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['product_id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $shortDesc = $_POST['short_desc'];
            $longDesc = $_POST['long_desc'];
            $imageSource = $_POST['image_source'];
            $keepCurrentImage = isset($_POST['keep_current_image']) && $_POST['keep_current_image'] == "1";

            if ($imageSource === 'new' && !$keepCurrentImage) {
                $image = $_FILES['new_image']['name'];
                $targetDir = "public/uploads/";
                $targetFile = $targetDir . basename($_FILES['new_image']['name']);
                move_uploaded_file($_FILES['new_image']['tmp_name'], $targetFile);
            } elseif ($imageSource === 'existing' && !$keepCurrentImage) {
                $image = $_POST['existing_image'];
            } elseif ($keepCurrentImage) {
                $productModel = new SanPham();
                $existingProduct = $productModel->getProductById($id);
                $image = $existingProduct['image'];
            }

            $productModel = new SanPham();
            $productModel->adminUpdateProduct($id, $name, $price, $quantity, $image, $shortDesc, $longDesc);

            $encodedId = urlencode($id);
            header("Location: /admin/edit_product?id=$encodedId");
            exit;
        }

        $this->importHeader();
        include "../project_php2_5/app/view/admin/products.php";
        $this->importFooter();
    }

    public function adminDeleteProduct()
    {
        session_start();
        // Kiểm tra xác thực người dùng là quản trị viên
        $user = $_SESSION['user'];
        if ($user['is_admin'] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập!')</script>";
            echo "<script>window.location.href='/';</script>";
            exit;
        }

        // Lấy ID của sản phẩm cần xóa từ tham số truyền vào
        $id = $_GET['id'];

        // Thực hiện gọi phương thức xóa sản phẩm từ model
        $sanPhamModel = new SanPham();
        $sanPhamModel->adminDeleteProduct($id);

        // Chuyển hướng về trang danh sách sản phẩm sau khi xóa thành công
        header("Location: /admin/products");
        exit;
    }

    public function adminUsers()
    {
        session_start();
        $this->importHeader();
        $userModel = new User();
        $users = $userModel->getAllUsersDesc();
        include "../project_php2_6/app/view/admin/users.php";
        $this->importFooter();
    }

    public function adminEditUser()
    {
        session_start();
        $this->importHeader();
        // Lấy thông tin sản phẩm từ cơ sở dữ liệu dựa trên ID
        $id = $_GET['id'];
        $userModel = new User();
        $user = $userModel->getUserById($id);
        include "../project_php2_6/app/view/admin/edit_user.php";
        $this->importFooter();
    }

    public function adminUpdateUser()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['user_id'];
            $name = $_POST['name'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $age = $_POST['age'];
            $company = $_POST['company'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $address = $_POST['address'];
            $address2 = $_POST['address2'];
            $phone = $_POST['phone'];
            $city = $_POST['city'];
            $country = $_POST['country'];
            $postal_code = $_POST['postal_code'];
            $is_admin = ($_POST['is_admin'] == 'Admin') ? 1 : 0;

            $userModel = new User();
            $userModel->adminUpdateUser($id, $name, $first_name, $last_name, $age, $company, $email, $password, $address, $address2, $phone, $city, $country, $postal_code, $is_admin);

            $encodedId = urlencode($id);
            header("Location: /admin/edit_user?id=$encodedId");
            exit;
        }

        $this->importHeader();
        include "../project_php2_5/app/view/admin/users.php";
        $this->importFooter();
    }

    public function adminDeleteUser()
    {
        session_start();
        // Kiểm tra xác thực người dùng là quản trị viên
        $user = $_SESSION['user'];
        if ($user['is_admin'] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập!')</script>";
            echo "<script>window.location.href='/';</script>";
            exit;
        }

        // Lấy ID của sản phẩm cần xóa từ tham số truyền vào
        $id = $_GET['id'];

        // Thực hiện gọi phương thức xóa sản phẩm từ model
        $userModel = new User();
        $userModel->adminDeleteUser($id);

        // Chuyển hướng về trang danh sách sản phẩm sau khi xóa thành công
        header("Location: /admin/users");
        exit;
    }

    public function adminOrders()
    {
        session_start();
        $this->importHeader();
        $orderModel = new Order();
        $orders = $orderModel->getAllOrders();
        include "../project_php2_6/app/view/admin/orders.php";
        $this->importFooter();
    }
}
