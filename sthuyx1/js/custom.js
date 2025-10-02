document.addEventListener('DOMContentLoaded', () => {
    // Xử lý slider banner
    let slideIndex = 0;
    const slides = document.querySelectorAll('.banner-slide');
    const totalSlides = slides.length;

    function showSlides() {
        if (totalSlides === 0) return;
        slides.forEach(slide => {
            slide.style.display = 'none';
            slide.style.opacity = '0';
        });
        slideIndex = (slideIndex + 1) % totalSlides;
        slides[slideIndex].style.display = 'block';
        slides[slideIndex].style.opacity = '1';
        slides[slideIndex].style.transition = 'opacity 0.5s ease';
    }

    let slideInterval = setInterval(showSlides, 5000);

    const prevButton = document.querySelector('.banner-prev');
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            clearInterval(slideInterval);
            slideIndex = (slideIndex - 1 + totalSlides) % totalSlides;
            slides.forEach(slide => {
                slide.style.display = 'none';
                slide.style.opacity = '0';
            });
            slides[slideIndex].style.display = 'block';
            slides[slideIndex].style.opacity = '1';
            slides[slideIndex].style.transition = 'opacity 0.5s ease';
            slideInterval = setInterval(showSlides, 5000);
        });
    }

    const nextButton = document.querySelector('.banner-next');
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            clearInterval(slideInterval);
            slideIndex = (slideIndex + 1) % totalSlides;
            slides.forEach(slide => {
                slide.style.display = 'none';
                slide.style.opacity = '0';
            });
            slides[slideIndex].style.display = 'block';
            slides[slideIndex].style.opacity = '1';
            slides[slideIndex].style.transition = 'opacity 0.5s ease';
            slideInterval = setInterval(showSlides, 5000);
        });
    }

    if (totalSlides > 0) {
        slides[slideIndex].style.display = 'block';
        slides[slideIndex].style.opacity = '1';
    }

    // Xử lý thêm vào giỏ hàng
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.getAttribute('data-product-id');
        const quantityElement = document.getElementById('quantity') || { value: 1 };
        const quantity = parseInt(quantityElement.value) || 1;

        if (!productId || isNaN(productId)) {
            console.error('Product ID không hợp lệ:', productId);
            showCartNotification('Lỗi: ID sản phẩm không hợp lệ!', true);
            return;
        }

        const url = '/sthuyx1/giohang/add_to_cart.php';

        console.log('Gửi yêu cầu với productId:', productId, 'Quantity:', quantity, 'URL:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Phản hồi từ server:', data);
            if (data.success) {
                updateCartCount();
                showCartNotification(data.message || 'Thêm vào giỏ hàng thành công!'); // Sử dụng thông báo từ server, hoặc thông báo mặc định
            } else {
                showCartNotification(data.message, true);
            }
        })
        .catch(error => {
            console.error('Lỗi khi thêm vào giỏ:', error);
            showCartNotification('Đã xảy ra lỗi khi thêm vào giỏ! Chi tiết: ' + error.message, true);
        });
    });
});

// Xử lý xóa sản phẩm khỏi giỏ hàng
document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.getAttribute('data-product-id');
        const row = button.closest('tr');

        if (!productId || !row) {
            console.error('Dữ liệu không hợp lệ hoặc không tìm thấy dòng sản phẩm!', { productId, row });
            showCartNotification('Lỗi: Dữ liệu không hợp lệ!', true);
            return;
        }

        const url = '/sthuyx1/giohang/remove_from_cart.php';

        console.log('Click remove item with productId:', productId, 'URL:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id=${encodeURIComponent(productId)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response from server (remove):', data);
            if (data.success || data.message === 'Đã xóa!') { // Kiểm tra cả success và message
                row.remove();
                updateCartCount();
                let totalElement = document.querySelector('.cart-table tfoot td:nth-child(4) strong');
                if (totalElement) {
                    let total = 0;
                    document.querySelectorAll('.cart-table tbody tr').forEach(row => {
                        let price = parseFloat(row.cells[1].textContent.replace(/[^\d]/g, '')) || 0;
                        let qty = parseInt(row.cells[2].textContent) || 1;
                        total += price * qty;
                    });
                    totalElement.textContent = numberFormat(total) + " VNĐ";
                }
                if (document.querySelectorAll('.cart-table tbody tr').length === 0) {
                    document.querySelector('.cart-section').innerHTML = `
                        <p>Giỏ hàng của bạn trống.</p>
                        <a href="/sthuyx1/index.php" class="cart-button"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
                    `;
                }
                showCartNotification(data.message || 'Đã xóa!'); // Đảm bảo hiển thị "Đã xóa!"
            } else {
                showCartNotification(data.message || 'Lỗi khi xóa sản phẩm!', true);
            }
        })
        .catch(error => {
            console.error('Lỗi khi xóa sản phẩm:', error);
            showCartNotification('Đã xảy ra lỗi khi xóa sản phẩm! Chi tiết: ' + error.message, true);
        });
    });
});

// Cập nhật số lượng giỏ hàng theo thời gian thực
function updateCartCount() {
    const url = '/sthuyx1/giohang/get_cart_count.php';

    fetch(url, {
        method: 'GET'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = data.cart_count || 0;
        });
    })
    .catch(error => {
        console.error('Lỗi khi cập nhật cart count:', error);
        // Đặt số lượng về 0 nếu có lỗi
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = 0;
        });
    });
}

    // Cập nhật số lượng mỗi 2 giây
    setInterval(updateCartCount, 2000);

    // Hàm định dạng số
    function numberFormat(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Hiển thị thông báo
    function showCartNotification(message, isError = false) {
        const notification = document.getElementById('cart-notification');
        const notificationText = document.getElementById('notification-text');
        if (notification && notificationText) {
            notificationText.textContent = message;
            notification.style.backgroundColor = isError ? '#ff4444' : '#2ecc71';
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 2000);
        } else {
            console.warn('Không tìm thấy phần tử thông báo!');
        }
    }

    // Xử lý gửi đánh giá qua AJAX
    const reviewForm = document.querySelector('form[action="review.php"]'); // Sửa selector cho chính xác
    if (reviewForm) {
        reviewForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const productId = new URLSearchParams(window.location.search).get('id'); // Sửa từ product_id thành id
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const comment = document.querySelector('textarea[name="comment"]')?.value || '';

            if (!rating || !comment) {
                showCartNotification('Vui lòng chọn đánh giá và nhập bình luận!', true);
                return;
            }

            const url = '/sthuyx1/review.php';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${encodeURIComponent(productId)}&rating=${encodeURIComponent(rating)}&comment=${encodeURIComponent(comment)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showCartNotification(data.message);
                    setTimeout(() => {
                        window.location.href = `/sthuyx1/sanpham/product_detail.php?id=${productId}`;
                    }, 2000);
                } else {
                    showCartNotification(data.message, true);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi đánh giá:', error);
                showCartNotification('Đã xảy ra lỗi khi gửi đánh giá! Chi tiết: ' + error.message, true);
            });
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const ratingInputs = document.querySelectorAll('.rating-group input');
    const ratingLabels = document.querySelectorAll('.rating-group label i');

    ratingInputs.forEach(input => {
        input.addEventListener('change', function () {
            const value = parseInt(this.value); // Giá trị sao được chọn (1-5)

            // Reset màu của tất cả các sao
            ratingLabels.forEach(label => {
                label.style.color = '#ccc'; // Màu xám mặc định
            });

            // Tô màu các sao từ 1 đến sao được chọn
            for (let i = 0; i < value; i++) {
                ratingLabels[i].style.color = '#ff4500'; // Màu đỏ
            }
        });
    });

    // Hiệu ứng hover
    ratingLabels.forEach((label, index) => {
        label.addEventListener('mouseover', function () {
            for (let i = 0; i <= index; i++) {
                ratingLabels[i].style.color = '#ff4500'; // Tô màu khi hover
            }
        });

        label.addEventListener('mouseout', function () {
            const checkedInput = document.querySelector('.rating-group input:checked');
            const value = checkedInput ? parseInt(checkedInput.value) : 0;

            // Reset màu
            ratingLabels.forEach(label => {
                label.style.color = '#ccc';
            });

            // Tô lại màu cho các sao đã được chọn
            for (let i = 0; i < value; i++) {
                ratingLabels[i].style.color = '#ff4500';
            }
        });
    });
});