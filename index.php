<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$products = [
    1 => ['name' => 'Product A', 'price' => 100],
    2 => ['name' => 'Product B', 'price' => 150],
    3 => ['name' => 'Product C', 'price' => 200],
];

if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$productId] = [
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => 1
        ];
    }
}

if (isset($_POST['remove_item'])) {
    $removeId = $_POST['remove_id'];
    unset($_SESSION['cart'][$removeId]);
}

if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Add to Cart Web App</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .product-list { display: flex; gap: 20px; flex-wrap: wrap; }
        .product { border: 1px solid #ddd; padding: 20px; width: 200px; border-radius: 10px; }
        .cart { margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { margin-top: 10px; padding: 6px 12px; background: #28a745; color: white; border: none; cursor: pointer; }
        .remove-btn { background-color: red; color: white; border: none; padding: 4px 8px; cursor: pointer; }
        .cart-total { font-weight: bold; text-align: right; padding: 10px; }
        .copy-btn { margin-top: 10px; padding: 6px 12px; background: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>PHP Add to Cart Web App</h1>

    <div class="product-list">
        <?php foreach ($products as $id => $product): ?>
            <div class="product">
                <h3><?= $product['name'] ?></h3>
                <p>Price: ₹<?= $product['price'] ?></p>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <button class="btn" type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="cart">
        <h2>Shopping Cart</h2>
        <form method="POST">
            <button class="btn" type="submit" name="clear_cart">Clear Cart</button>
        </form>
        <button class="copy-btn" onclick="copyCartDetails()">Copy Cart Details to Clipboard</button>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $cartTotal = 0; ?>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                    <?php $cartTotal += $itemTotal; ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td>₹<?= $item['price'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₹<?= $itemTotal ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="remove_id" value="<?= $id ?>">
                                <button class="remove-btn" type="submit" name="remove_item">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-total">
            Cart Total: ₹<?= $cartTotal ?>
        </div>
    </div>

    <script>
        function copyCartDetails() {
            let cartDetails = '';
            <?php foreach ($_SESSION['cart'] as $item): ?>
                cartDetails += '<?= $item['quantity'] ?> <?= $item['name'] ?>\n';
            <?php endforeach; ?>
            navigator.clipboard.writeText(cartDetails).then(function() {
                alert('Cart details copied to clipboard!');
            }, function(err) {
                alert('Failed to copy cart details.');
            });
        }
    </script>
</body>
</html>
