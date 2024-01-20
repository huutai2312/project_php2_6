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
        session_start();
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

    public function cart()
    {
        session_start();

        $this->importHeader();
        $cartItems = $_SESSION['cart'] ?? [];
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $shortDesc = $_POST['short_desc'];
    $longDesc = $_POST['long_desc'];

    // Kiểm tra lựa chọn của người dùng
    if ($_POST['image_source'] === 'new') {
        // Lựa chọn tải lên ảnh mới
        $image = $_FILES['new_image']['name'];

        // Thực hiện tải lên hình ảnh mới
        $targetDir = "public/uploads/";
        $targetFile = $targetDir . basename($_FILES['new_image']['name']);
        move_uploaded_file($_FILES['new_image']['tmp_name'], $targetFile);
    } else {
        // Lựa chọn chọn ảnh có sẵn
        $image = $_POST['existing_image'];
    }

    // Thực hiện gọi phương thức từ model để cập nhật sản phẩm trong cơ sở dữ liệu
    $productModel = new SanPham();
    $productModel->adminUpdateProduct($id, $name, $price, $quantity, $image, $shortDesc, $longDesc);

    // Chuyển hướng về trang sửa sản phẩm sau khi cập nhật thành công
    $encodedId = urlencode($id);
    header("Location: /admin/edit_product?id=$encodedId");
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

    public function addToCart($product_id)
    {
        session_start();
        // Lấy thông tin sản phẩm từ database
        $sanPhamModel = new SanPham();
        $productDetail = $sanPhamModel->getProductById($product_id);

        if ($productDetail) {
            // Kiểm tra nếu giỏ hàng không tồn tại, tạo mới giỏ hàng
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Thêm sản phẩm vào giỏ hàng
            $cartItem = [
                'id' => $productDetail['id'],
                'name' => $productDetail['name'],
                'price' => $productDetail['price'],
                'quantity' => 1, // Số lượng mặc định là 1
                'image' => $productDetail['image'],
                'total_price' => $productDetail['price']
            ];

            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
            $existingCartItem = array_filter($_SESSION['cart'], function ($item) use ($product_id) {
                return $item['id'] == $product_id;
            });

            if (count($existingCartItem) > 0) {
                // Sản phẩm đã tồn tại trong giỏ hàng, tăng số lượng lên 1
                $_SESSION['cart'][$product_id]['quantity']++;
                $_SESSION['cart'][$product_id]['total_price'] += $productDetail['price'];
            } else {
                // Sản phẩm chưa tồn tại trong giỏ hàng, thêm mới
                $_SESSION['cart'][$product_id] = $cartItem;

                // Thêm thông tin sản phẩm vào cơ sở dữ liệu
                $cartModel = new Cart();
                $cartModel->addToCart(
                    'id_billcart', // Giá trị của id_billcart tùy thuộc vào loại hóa đơn bạn sử dụng
                    $_SESSION['user']['id'], // Thay đổi thành id_user tương ứng
                    $product_id,
                    $productDetail['name'],
                    $productDetail['price'],
                    1, // Số lượng mặc định là 1
                    $productDetail['image'],
                    $productDetail['price']
                );
            }

            var_dump($product_id, $productDetail, $cartItem);
            // Chuyển hướng về trang chi tiết sản phẩm
            header('Location: /san-pham?id=' . $product_id);
            exit;
        } else {
        }
    }
}
