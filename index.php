<?php
require_once __DIR__ . '/includes/auth.php';

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

$allowed_pages = [
    'home', 'products', 'product-detail', 'cart', 'checkout', 
    'orders', 'wishlist', 'profile', 'categories',
    'login', 'register', 'logout'
];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

$page_file = __DIR__ . '/pages/' . $page . '.php';

if (!file_exists($page_file)) {
    $page = 'home';
    $page_file = __DIR__ . '/pages/home.php';
}

if ($action) {
    handleAction($action);
}

function handleAction($action) {
    global $db;
    
    switch ($action) {
        case 'add_to_cart':
            addToCart();
            break;
        case 'update_cart':
            updateCart();
            break;
        case 'remove_from_cart':
            removeFromCart();
            break;
        case 'toggle_wishlist':
            toggleWishlist();
            break;
        case 'submit_review':
            submitReview();
            break;
        case 'place_order':
            placeOrder();
            break;
    }
}

function addToCart() {
    global $db;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];
    
    $product = getProductById($product_id);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    if ($product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
        exit;
    }
    
    $existing = $db->fetchOne("SELECT * FROM cart WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);
    
    if ($existing) {
        $new_qty = $existing['quantity'] + $quantity;
        if ($new_qty > $product['stock']) {
            $new_qty = $product['stock'];
        }
        $db->update("cart", ['quantity' => $new_qty], "id = ?", [$existing['id']]);
    } else {
        $db->insert("cart", [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product['price']
        ]);
    }
    
    $cart_count = $db->fetchOne("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?", [$user_id]);
    
    echo json_encode(['success' => true, 'cart_count' => $cart_count['total'] ?? 0]);
    exit;
}

function updateCart() {
    global $db;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false]);
        exit;
    }
    
    $cart_id = (int)$_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];
    
    if ($quantity < 1) {
        $db->delete("cart", "id = ? AND user_id = ?", [$cart_id, $user_id]);
    } else {
        $db->update("cart", ['quantity' => $quantity], "id = ? AND user_id = ?", [$cart_id, $user_id]);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

function removeFromCart() {
    global $db;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false]);
        exit;
    }
    
    $cart_id = (int)$_GET['cart_id'];
    $user_id = $_SESSION['user_id'];
    
    $db->delete("cart", "id = ? AND user_id = ?", [$cart_id, $user_id]);
    
    echo json_encode(['success' => true]);
    exit;
}

function toggleWishlist() {
    global $db;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }
    
    $product_id = (int)$_GET['product_id'];
    $user_id = $_SESSION['user_id'];
    
    $existing = $db->fetchOne("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);
    
    if ($existing) {
        $db->delete("wishlists", "user_id = ? AND product_id = ?", [$user_id, $product_id]);
        echo json_encode(['success' => true, 'added' => false]);
    } else {
        $db->insert("wishlists", [
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
        echo json_encode(['success' => true, 'added' => true]);
    }
    exit;
}

function submitReview() {
    global $db;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }
    
    $product_id = (int)$_POST['product_id'];
    $rating = (int)$_POST['rating'];
    $comment = sanitize($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating']);
        exit;
    }
    
    $existing = $db->fetchOne("SELECT id FROM reviews WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);
    
    if ($existing) {
        $db->update("reviews", [
            'rating' => $rating,
            'comment' => $comment,
            'status' => 'pending'
        ], "id = ?", [$existing['id']]);
    } else {
        $db->insert("reviews", [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'rating' => $rating,
            'comment' => $comment,
            'status' => 'pending'
        ]);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

function placeOrder() {
    global $db;
    
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    $cart_items = $db->fetchAll("SELECT c.*, p.name, p.stock, p.price as current_price,
                                  (SELECT image FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order LIMIT 1) as image
                                  FROM cart c 
                                  JOIN products p ON c.product_id = p.id 
                                  WHERE c.user_id = ?", [$user_id]);
    
    if (empty($cart_items)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }
    
    foreach ($cart_items as $item) {
        if ($item['quantity'] > $item['stock']) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock for ' . $item['name']]);
            exit;
        }
    }
    
    $subtotal = 0;
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $shipping_fee = getSetting('shipping_fee', 150);
    $total = $subtotal + $shipping_fee;
    
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $zip = sanitize($_POST['zip']);
    $payment_method = sanitize($_POST['payment_method']);
    
    $db->beginTransaction();
    
    try {
        $order_number = generateOrderNumber();
        
        $order_id = $db->insert("orders", [
            'user_id' => $user_id,
            'order_number' => $order_number,
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'total' => $total,
            'shipping_name' => $name,
            'shipping_phone' => $phone,
            'shipping_address' => $address,
            'shipping_city' => $city,
            'shipping_zip' => $zip,
            'payment_method' => $payment_method,
            'order_status' => 'pending',
            'payment_status' => 'pending'
        ]);
        
        foreach ($cart_items as $item) {
            $item_subtotal = $item['price'] * $item['quantity'];
            
            $db->insert("order_items", [
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'product_image' => $item['image'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item_subtotal
            ]);
            
            $db->query("UPDATE products SET stock = stock - ? WHERE id = ?", [$item['quantity'], $item['product_id']]);
        }
        
        if ($payment_method == 'gcash' && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['name']) {
            $upload = uploadImage($_FILES['payment_proof'], PAYMENT_PATH, 'payment_');
            
            if (is_array($upload) && isset($upload['error'])) {
                throw new Exception($upload['error']);
            }
            
            $db->update("orders", ['payment_proof' => $upload], "id = ?", [$order_id]);
        }
        
        $db->query("DELETE FROM cart WHERE user_id = ?", [$user_id]);
        
        $db->commit();
        
        echo json_encode(['success' => true, 'order_id' => $order_id, 'order_number' => $order_number]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

function getCartCount() {
    global $db;
    
    if (!isLoggedIn()) return 0;
    
    $result = $db->fetchOne("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?", [$_SESSION['user_id']]);
    return $result['total'] ?? 0;
}

function getWishlistCount() {
    global $db;
    
    if (!isLoggedIn()) return 0;
    
    $result = $db->fetchOne("SELECT COUNT(*) as total FROM wishlists WHERE user_id = ?", [$_SESSION['user_id']]);
    return $result['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <meta name="description" content="Premium second-hand motor parts for sale. Quality engine parts, tires, electrical components and more.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/components/header.php'; ?>
    
    <main>
        <?php include $page_file; ?>
    </main>
    
    <?php include __DIR__ . '/components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
