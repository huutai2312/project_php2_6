<main class="main__content_wrapper">

    <!-- Start breadcrumb section -->
    <section class="breadcrumb__section">
    </section>
    <!-- End breadcrumb section -->

    <!-- cart section start -->
    <section class="cart__section section--padding">
        <div class="container-fluid">
            <div class="cart__section--inner">
                <form action="#">
                    <h2 class="cart__title mb-40">Shopping Cart</h2>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="cart__table">
                                <table class="cart__table--inner">
                                    <thead class="cart__table--header">
                                        <tr class="cart__table--header__items">
                                            <th class="cart__table--header__list">Product</th>
                                            <th class="cart__table--header__list">Price</th>
                                            <th class="cart__table--header__list">Quantity</th>
                                            <th class="cart__table--header__list">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="cart__table--body body-table-product">
                                        <?php if (isset($_GET['cleared'])) : ?>
                                            <div class="cart__success-message">Cart has been cleared successfully!</div>
                                        <?php endif; ?>

                                        <?php if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) : ?>
                                            <?php foreach ($_SESSION['cart'] as $cartItem) : ?>
                                                <tr class="cart__table--body__items">
                                                    <td class="cart__table--body__list">
                                                        <div class="cart__product d-flex align-items-center">
                                                            <button class="cart__remove--btn" aria-label="search button" type="button">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px">
                                                                    <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z" />
                                                                </svg>
                                                            </button>
                                                            <div class="cart__content">
                                                                <h4 class="cart__content--title"><a href="#"><?php echo $cartItem[2]; ?></a></h4>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="cart__table--body__list">
                                                        <span class="cart__price"><?php echo '$' . number_format($cartItem[3], 0); ?></span>
                                                    </td>
                                                    <td class="cart__table--body__list">
                                                        <div class="quantity__box">
                                                            <button type="button" class="quantity__value quickview__value--quantity decrease" aria-label="quantity value" data-action="decrease" value="Decrease Value">-</button>
                                                            <label>
                                                                <input type="number" class="quantity__number quickview__value--number" value="<?php echo (int)$cartItem[1]; ?>" data-counter data-product-id="<?php echo $cartItem[0]; ?>" />
                                                            </label>
                                                            <button type="button" class="quantity__value quickview__value--quantity increase" aria-label="quantity value" data-action="increase" value="Increase Value">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="cart__table--body__list">
                                                        <span class="cart__price end" id="total_<?php echo $cartItem[0]; ?>"><?php echo '$' . number_format((float)$cartItem[1] * (float)$cartItem[3], 0); ?></span>
                                                        <!-- <span class="cart__price end"><?php echo '$' . number_format((float)$cartItem[1] * (float)$cartItem[3], 0); ?></span> -->
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="cart__empty-message">Your cart is empty.</div>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="continue__shopping d-flex justify-content-between">
                                    <a class="continue__shopping--link" href="/cua-hang">Continue shopping</a>
                                </div>
                                <a href="/clear-cart" class="cart__clear-btn">Clear Cart</a>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="cart__summary border-radius-10">
                                <div class="cart__summary--total mb-20">
                                    <table class="cart__summary--total__table">
                                        <tbody>
                                            <tr class="cart__summary--total__list">
                                                <td class="cart__summary--total__title text-left">SUBTOTAL</td>
                                                <td class="cart__summary--amount text-right" id="subtotal" data-id="subtotal">$-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="cart__summary--footer">
                                    <ul class="d-flex justify-content-between">
                                        <?php
                                        if (isset($_SESSION['user'])) {
                                            echo '<li><a class="cart__summary--footer__btn primary__btn checkout" href="/checkout">Check Out</a></li>';
                                        } else {
                                            echo '<li><a class="cart__summary--footer__btn primary__btn" href="/tai-khoan">Login</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- cart section end -->
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const quantityInputs = document.querySelectorAll('.quantity__number');

        quantityInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                updateTotal(this);
            });
        });

        function updateTotal(input) {
            const productId = input.getAttribute('data-product-id');
            const quantity = input.value;
            const productPrice = <?php echo $cartItem[3]; ?>; // Thay thế bằng giá trị giá sản phẩm từ PHP

            // Tính toán tổng giá trị và cập nhật vào phần tử hiển thị
            const totalElement = document.getElementById('total_' + productId);
            const totalValue = (parseFloat(quantity) * parseFloat(productPrice)).toFixed(0);
            totalElement.textContent = '$' + number_format(totalValue, 0);
        }

        // Hàm định dạng số có dấu phẩy ngăn cách hàng nghìn
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    });
</script>