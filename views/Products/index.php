    <h1>Products</h1>
    <?php foreach ($products as $product) : ?>
        <p>
            <a href="<?= WEB_ROOT ?>products/<?= htmlspecialchars($product['id']) ?>/show">
                <?= htmlspecialchars($product["name"]) ?>
            </a>
        </p>
    <?php endforeach; ?>

</body>
</html>