<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../trangchu/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="../js/custom.js"></script>

<div class="container">
    <div class="sidebar">
                <h3>Danh mục sản phẩm</h3>
                <ul class="category-list">
                    <?php
                    $sql_categories = "SELECT * FROM categories";
                    $categories = $conn->query($sql_categories);
                    while ($category = $categories->fetch_assoc()) {
                        echo '<li><a href="/sthuyx1/sanpham/products.php?category_id=' . $category['id'] . '" ' . ($category_id == $category['id'] ? 'style="color: #ff4500; font-weight: bold;"' : '') . '>' . htmlspecialchars($category['name']) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
    <div class="main-content">
        <div class="main-section">
            <h2>Danh sách sản phẩm</h2>
            <div class="product-list">
                <?php
                $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $category_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item">';
                        echo '<a href="/sthuyx1/sanpham/product_detail.php?id=' . $row['id'] . '">';
                        echo '<img src="/sthuyx1/images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width: 100%; height: auto; max-height: 200px; object-fit: cover;">';
                        echo '</a>';
                        echo '<h3><a href="/sthuyx1/sanpham/product_detail.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a></h3>';
                        echo '<p class="product-price">' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
                        echo '<a href="/sthuyx1/giohang/add_to_cart.php?product_id=' . $row['id'] . '&quantity=1" class="add-to-cart" data-product-id="' . $row['id'] . '">Thêm vào giỏ</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Không có sản phẩm nào trong danh mục này.</p>';
                }
                $stmt->close();
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>