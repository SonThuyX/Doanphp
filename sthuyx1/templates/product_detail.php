<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="../css/products_view.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="../js/custom.js" defer></script>
    <script>
        // Cập nhật số lượng sản phẩm trong giỏ hàng
        function updateCartCount() {
            fetch('../giohang/get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.querySelectorAll('.cart-count');
                    cartCount.forEach(element => {
                        element.textContent = data.cart_count || 0;
                    });
                })
                .catch(error => console.error('Lỗi:', error));
        }

        // Khởi tạo khi trang được tải
        window.onload = () => {
            updateCartCount();
            updatePrice();
            // Khởi tạo Owl Carousel cho slider hình ảnh
            $('#productCarousel-slider').owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true,
                autoplay: false
            });
            $('#productCarousel-thumb').owlCarousel({
                items: 3,
                loop: false,
                nav: true,
                dots: true,
                margin: 15,
                responsive: {
                    0: { items: 3 },
                    600: { items: 4 },
                    1000: { items: 5 }
                }
            });
            // Đồng bộ hình ảnh thumbnail với slider
            $('#productCarousel-thumb .product-thumb').on('click', function() {
                $('#productCarousel-slider').trigger('to.owl.carousel', $(this).index());
                $('#productCarousel-thumb .product-thumb').removeClass('current');
                $(this).addClass('current');
            });
        };
        setInterval(updateCartCount, 500);

        // Cập nhật giá khi thay đổi số lượng hoặc tùy chọn
        function updatePrice() {
            const quantity = document.getElementById('quantity').value;
            let basePrice = <?php echo $product ? $product['price'] : 0; ?>;
            document.querySelectorAll('.swatch-element input:checked').forEach(input => {
                basePrice += parseInt(input.getAttribute('data-price'));
            });
            const total = basePrice * quantity;
            document.querySelector('.pro-price').textContent = numberFormat(total) + ' VNĐ';
        }

        // Định dạng số tiền (thêm dấu phẩy)
        function numberFormat(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Giảm số lượng
        function minusQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (quantityInput.value > 1) {
                quantityInput.value--;
                updatePrice();
            }
        }

        // Tăng số lượng
        function plusQuantity() {
            const quantityInput = document.getElementById('quantity');
            const stock = <?php echo $product ? $product['stock'] : 0; ?>;
            if (quantityInput.value < stock) {
                quantityInput.value++;
                updatePrice();
            } else {
                alert('Số lượng vượt quá tồn kho! Tồn kho hiện tại: ' + stock);
            }
        }

        // Thêm sản phẩm vào giỏ hàng bằng AJAX
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            fetch(`../giohang/add_to_cart.php?product_id=${productId}&quantity=${quantity}`)
                .then(response => response.json())
                .then(data => {
                    const notification = document.getElementById('cart-notification');
                    const notificationText = document.getElementById('notification-text');
                    notificationText.textContent = data.message;
                    notification.style.display = 'block';
                    notification.style.backgroundColor = data.success ? '#2ecc71' : '#e74c3c';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                    if (data.success) {
                        updateCartCount();
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    const notification = document.getElementById('cart-notification');
                    const notificationText = document.getElementById('notification-text');
                    notificationText.textContent = 'Có lỗi xảy ra khi thêm vào giỏ hàng!';
                    notification.style.display = 'block';
                    notification.style.backgroundColor = '#e74c3c';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                });
        }
    </script>
    <style>
        /* Thêm CSS inline để đảm bảo màu sao hiển thị đúng */
        .rating-stars i {
            color: #ddd; /* Màu mặc định cho sao (xám) */
            font-size: 1.2rem;
            margin-right: 2px;
        }
        .rating-stars i.filled {
            color: #ff4500; /* Màu đỏ cho sao được chọn */
        }
    </style>
</head>
<body>
    <header>
        <div id="cart-notification" style="display: none; position: fixed; top: 20px; right: 20px; padding: 10px 20px; color: white; border-radius: 5px; z-index: 1000;">
            <span id="notification-text"></span>
        </div>
    </header>
    <main>
        <div class="container">
            <aside class="sidebar">
                <h3>DANH MỤC SẢN PHẨM</h3>
                <ul>
                    <li><a href="../sanpham/products.php?category_id=1">CPU</a></li>
                    <li><a href="../sanpham/products.php?category_id=2">VGA</a></li>
                    <li><a href="../sanpham/products.php?category_id=3">SSD</a></li>
                    <li><a href="../sanpham/products.php?category_id=4">HDD</a></li>
                    <li><a href="../sanpham/products.php?category_id=5">Case</a></li>
                    <li><a href="../sanpham/products.php?category_id=6">Màn hình</a></li>
                    <li><a href="../sanpham/products.php?category_id=7">Ram</a></li>
                    <li><a href="../sanpham/products.php?category_id=8">Mainboard</a></li>
                    <li class="more"><a href="#">+ Xem thêm</a></li>
                </ul>
            </aside>

            <div class="main-content">
                <section class="productDetail-information productDetail_style__01">
                    <div class="container container-pd0">
                        <div class="productDetail--main">
                            <?php if ($product): ?>
                                <div class="productDetail--gallery">
                                    <div class="product-container-gallery">
                                        <div class="wrapbox-gallery">
                                            <div class="wrapbox-image">
                                                <div class="productGallery_slider">
                                                    <ul class="productList-slider productCarousel-slider owl-carousel" id="productCarousel-slider">
                                                        <?php foreach ($additional_images as $index => $image): ?>
                                                            <li class="product-gallery" data-image="../images/<?php echo htmlspecialchars($image); ?>">
                                                                <a class="product-gallery__item" data-fancybox="gallery" href="../images/<?php echo htmlspecialchars($image); ?>">
                                                                    <img src="../images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <div class="productGallery_thumb">
                                                    <ul class="productList-thumb productCarousel-thumb owl-carousel" id="productCarousel-thumb">
                                                        <?php foreach ($additional_images as $index => $image): ?>
                                                            <li class="product-thumb <?php echo $index === 0 ? 'current' : ''; ?>" data-image="../images/<?php echo htmlspecialchars($image); ?>">
                                                                <a class="product-thumb__item" href="javascript:void(0);">
                                                                    <img src="../images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-percent">
                                                <span class="pro-sale">-29%<br> OFF</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Phần mô tả sản phẩm từ cơ sở dữ liệu -->
                                    <div class="productDetail--box box-detail-description">
                                        <div class="product-description mg-top">
                                            <div class="box-title"><h2>Mô tả sản phẩm</h2></div>
                                            <div class="description-content expandable-toggle opened">
                                                <div class="description-productdetail" style="height: 220px; overflow: hidden;">
                                                    <?php echo nl2br(htmlspecialchars($product['description'] ?? 'Không có mô tả.')); ?>
                                                </div>
                                                <div class="description-btn">
                                                    <button class="expandable-content_toggle js_expandable_content">
                                                        <span class="expandable-content_toggle-icon"></span>
                                                        <span class="expandable-content_toggle-text">Xem thêm nội dung</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Phần đánh giá sản phẩm -->
                                    <div class="productDetail--box box-detail-reviews">
                                        <div class="product-reviews mg-top">
                                            <div class="box-title"><h2>Đánh giá sản phẩm</h2></div>
                                            <?php if (!empty($reviews)): ?>
                                                <?php foreach ($reviews as $review): ?>
                                                    <div class="review-item">
                                                        <div class="rating-stars">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <p><strong><?php echo htmlspecialchars($review['username']); ?></strong> - <?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></p>
                                                        <p><?php echo htmlspecialchars($review['comment'] ?: 'Không có bình luận.'); ?></p>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="productDetail--content">
                                    <div class="wrapbox-inner">
                                        <div class="wrapbox-detail">
                                            <div class="product-heading">
                                                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                                                <span class="pro-soldold">Tình trạng: <strong><?php echo $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?></strong></span>
                                                <span class="pro-stock">Tồn kho: <strong><?php echo $product['stock']; ?> sản phẩm</strong></span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <div class="col-lg-8 col-md-12 col-12 p-0 wrapbox-left">
                                                <div class="wrapbox-detail">
                                                    <div class="product-price" id="price-preview">
                                                        <span class="pro-title">Giá: </span>
                                                        <span class="pro-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</span>
                                                        <del><?php echo number_format($original_price, 0, ',', '.'); ?> VNĐ</del>
                                                        <span class="pro-percent">-29%</span>
                                                    </div>
                                                    <div class="product-variants">
                                                        <form id="add-item-form" action="/cart/add" method="post" class="variants clearfix">
                                                            <div class="select-swatch clearfix">
                                                                <?php foreach ($config_options as $option_name => $options): ?>
                                                                    <div id="variant-swatch-<?php echo strtolower($option_name); ?>" class="swatch clearfix" data-option="<?php echo strtolower($option_name); ?>">
                                                                        <div class="title-swap header"><?php echo $option_name; ?>: </div>
                                                                        <div class="select-swap">
                                                                            <?php foreach ($options as $index => $option): ?>
                                                                                <div data-value="<?php echo htmlspecialchars($option['name']); ?>" class="n-sd swatch-element <?php echo strtolower(str_replace(' ', '-', $option['name'])); ?>">
                                                                                    <input class="variant-<?php echo strtolower($option_name); ?>" 
                                                                                           id="swatch-<?php echo strtolower($option_name); ?>-<?php echo strtolower(str_replace(' ', '-', $option['name'])); ?>" 
                                                                                           type="radio" 
                                                                                           name="<?php echo strtolower($option_name); ?>" 
                                                                                           value="<?php echo htmlspecialchars($option['name']); ?>" 
                                                                                           data-price="<?php echo $option['additional_price']; ?>" 
                                                                                           <?php echo $index === 0 ? 'checked' : ''; ?>>
                                                                                    <label for="swatch-<?php echo strtolower($option_name); ?>-<?php echo strtolower(str_replace(' ', '-', $option['name'])); ?>">
                                                                                        <span><?php echo htmlspecialchars($option['name']); ?></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="product-actions">
                                                        <div class="select-actions d-none d-lg-block clearfix">
                                                            <div class="quantity-area">
                                                                <div class="quantity-title">Số lượng: </div>
                                                                <button type="button" onclick="minusQuantity()" class="qty-btn">
                                                                    <svg focusable="false" class="icon icon--minus" viewBox="0 0 10 2" role="presentation">
                                                                        <path d="M10 0v2H0V0z"></path>
                                                                    </svg>
                                                                </button>
                                                                <input type="text" id="quantity" name="quantity" value="1" min="1" class="quantity-input">
                                                                <button type="button" onclick="plusQuantity()" class="qty-btn">
                                                                    <svg focusable="false" class="icon icon--plus" viewBox="0 0 10 10" role="presentation">
                                                                        <path d="M6 4h4v2H6v4H4V6H0V4h4V0h2v4z"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="addcart-area">
                                                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="add-to-cartProduct button dark btn-addtocart addtocart-modal" id="add-to-cart" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                                                    <span>Thêm vào giỏ</span>
                                                                </button>
                                                                <a href="../giohang/checkout.php?product_id=<?php echo $product['id']; ?>&quantity=1" class="button dark btn-buynow btnred addtocart-modal" id="buy-now" <?php echo $product['stock'] <= 0 ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                                                    Mua ngay
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-toshare">
                                                        <span>Chia sẻ: </span>
                                                        <a href="https://m.me/108520260558644" target="_blank" rel="noreferrer" aria-label="messenger" class="share-messenger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 28 28">
                                                                <g fill="none" fill-rule="evenodd">
                                                                    <g><g><g><g><g><g transform="translate(-293.000000, -708.000000) translate(180.000000, 144.000000) translate(16.000000, 16.000000) translate(0.000000, 548.000000) translate(61.000000, 0.000000) translate(36.000000, 0.000000)">
                                                                        <circle cx="14" cy="14" r="14" fill="#0084FF"></circle>
                                                                        <path fill="#FFF" d="M14.848 15.928l-1.771-1.9-3.457 1.9 3.802-4.061 1.815 1.9 3.414-1.9-3.803 4.061zM14.157 7.2c-3.842 0-6.957 2.902-6.957 6.481 0 2.04 1.012 3.86 2.593 5.048V21.2l2.368-1.308c.632.176 1.302.271 1.996.271 3.842 0 6.957-2.902 6.957-6.482S17.999 7.2 14.157 7.2z"></path>
                                                                    </g></g></g></g></g></g>
                                                                </g>
                                                            </svg>
                                                            <span class="ico-tooltip">Messenger</span>
                                                        </a>
                                                        <a class="share-link" onclick="navigator.clipboard.writeText(window.location.href); alert('Đã sao chép URL!');">
                                                            <i class="fa fa-link" aria-hidden="true"></i>
                                                            <span class="ico-tooltip">Sao chép url</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-12 p-0 wrapbox-right">
                                                <div class="wrapbox-detail">
                                                    <div class="d-flex flex-wrap product-deliverly">
                                                        <div class="col-lg-12 col-md-6 col-12 deliverly-inner">
                                                            <div class="title-deliverly">
                                                                <span>Chính sách bán hàng</span>
                                                            </div>
                                                            <div class="infoList-deliverly">
                                                                <div class="deliverly-item">
                                                                    <span><i class="fas fa-check-circle icon"></i></span>
                                                                    Cam kết 100% chính hãng
                                                                </div>
                                                                <div class="deliverly-item">
                                                                    <span><i class="fas fa-phone-alt icon"></i></span>
                                                                    Hỗ trợ 24/7
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-6 col-12 deliverly-inner">
                                                            <div class="title-deliverly">
                                                                <span>Thông tin thêm</span>
                                                            </div>
                                                            <div class="infoList-deliverly">
                                                                <div class="deliverly-item">
                                                                    <span><i class="fas fa-shield-alt icon"></i></span>
                                                                    Hoàn tiền 111% nếu hàng giả
                                                                </div>
                                                                <div class="deliverly-item">
                                                                    <span><i class="fas fa-box-open icon"></i></span>
                                                                    Mở hộp kiểm tra nhận hàng
                                                                </div>
                                                                <div class="deliverly-item">
                                                                    <span><i class="fas fa-undo-alt icon"></i></span>
                                                                    Đổi trả trong 7 ngày
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Sản phẩm không tồn tại hoặc không tìm thấy!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <script>
        // Cập nhật giá khi chọn cấu hình
        document.querySelectorAll('.swatch-element input').forEach(input => {
            input.addEventListener('change', function() {
                updatePrice();
            });
        });
        // Xử lý nút "Xem thêm nội dung"
        document.querySelector('.expandable-content_toggle').addEventListener('click', function() {
            const content = document.querySelector('.description-productdetail');
            const buttonText = this.querySelector('.expandable-content_toggle-text');
            if (content.style.height === '220px') {
                content.style.height = 'auto';
                buttonText.textContent = 'Thu gọn nội dung';
            } else {
                content.style.height = '220px';
                buttonText.textContent = 'Xem thêm nội dung';
            }
        });
    </script>
</body>
</html>