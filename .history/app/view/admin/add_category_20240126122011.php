<head>
    <meta charset="utf-8">
    <title>Suruchi - Fashion eCommerce HTML Template</title>
    <meta name="description" content="Morden Bootstrap HTML5 Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="./../app/view/inc/assets/img/favicon.ico">

    <!-- ======= All CSS Plugins here ======== -->
    <link rel="stylesheet" href="./../app/view/inc/assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="./../app/view/inc/assets/css/plugins/glightbox.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Plugin css -->
    <link rel="stylesheet" href="./../app/view/inc/assets/css/vendor/bootstrap.min.css">

    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="./../app/view/inc/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Thư viện Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->

    <!-- Thư viện Bootstrap JS và jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


</head>

<main class="main__content_wrapper">
    <!-- Start breadcrumb section -->
    <section class="breadcrumb__section">
    </section>
    <!-- End breadcrumb section -->

    <!-- my account section start -->
    <section class="my__account--section section--padding">
        <div class="container">
            <!-- <p class="account__welcome--text">Hello, Admin welcome to your dashboard!</p> -->
            <div class="my__account--section__inner border-radius-10 d-flex">
                <div class="account__left--sidebar">
                    <?php
                    if (isset($_SESSION['user'])) {
                        $user = $_SESSION['user'];
                        echo '<h2 class="account__content--title h3 mb-20">Admin ' . $user['name'] . '</h2>';
                    }
                    ?>
                    <ul class="account__menu">
                        <li class="account__menu--list"><a href="/admin">Dashboard</a></li>
                        <li class="account__menu--list active"><a href="/admin/categories">Categories</a></li>
                        <li class="account__menu--list"><a href="/admin/products">Products</a></li>
                        <li class="account__menu--list"><a href="/admin/users">Users</a></li>
                        <li class="account__menu--list"><a href="/admin/orders">Orders</a></li>
                        <hr>
                        <?php
                        // Kiểm tra xem có thông tin người dùng trong session hay không
                        if (isset($_SESSION['user'])) {
                            $user = $_SESSION['user'];
                            echo '<a href="/logout" class="account__menu--list">Log Out</a>';
                        } else {
                            echo '<p class="account__welcome--text">Vui lòng đăng nhập để xem thông tin tài khoản của bạn.</p>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="account__wrapper">
                    <div class="account__content">
                        <h2 class="account__content--title h3 mb-20">List Categories | <em><a href="/admin/add_category">Add Catery</a></em></h2>
                        <form class="contact__form--inner" action="/admin/add_product" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <div class="contact__form--list mb-20">
                                        <label class="contact__form--label">Name <span class="contact__form--label__star">*</span></label>
                                        <input class="contact__form--input" name="name" placeholder="Name" type="text">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact__form--list mb-20">
                                        <label class="contact__form--label">Price <span class="contact__form--label__star">*</span></label>
                                        <input class="contact__form--input" name="price" placeholder=" Price" type="text">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact__form--list mb-20">
                                        <label class="contact__form--label">Quantity <span class="contact__form--label__star">*</span></label>
                                        <input class="contact__form--input" name="quantity" placeholder="Quantity" type="text">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact__form--list mb-20">
                                        <label class="contact__form--label">Image <span class="contact__form--label__star">*</span></label>
                                        <input class="contact__form--input" name="image" id="imageInput" placeholder="Image" type="file">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact__form--list mb-15">
                                        <label class="contact__form--label">Short Desc <span class="contact__form--label__star">*</span></label>
                                        <textarea class="contact__form--textarea" name="short_desc" placeholder="Short Desc"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="contact__form--list mb-15">
                                        <label class="contact__form--label">Long Desc <span class="contact__form--label__star">*</span></label>
                                        <textarea class="contact__form--textarea" name="long_desc" placeholder="Long Desc"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button class="contact__form--btn primary__btn" type="submit">Submit Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- my account section end -->
</main>
