<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container">
    <h2>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($query ?? ''); ?>"</h2>
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php elseif ($result && $result->num_rows > 0): ?>
        <div class="product-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <a href="../admin/manage_products.php?id=<?php echo $row['id']; ?>">
                        <img src="../images/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="200">
                    </a>
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="product-price"><?php echo number_format($row['price'], 0, ',', '.') . ' VNĐ'; ?></p>
                    <a href="manage_products_view.php?id=<?php echo $row['id']; ?>" class="add-to-cart">Xem chi tiết</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm nào.</p>
    <?php endif; ?>
</div>

<?php include '../templates/footer.php'; ?>