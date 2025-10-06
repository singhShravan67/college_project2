<?php
/**
 * API Endpoints for E-Commerce Application
 * All AJAX requests from AngularJS will come here
 */

// Include database configuration
require_once 'config.php';

// Get HTTP request method (GET, POST, etc.)
$method = $_SERVER['REQUEST_METHOD'];

// Get action parameter from URL
$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';

// Get database connection
$conn = getDBConnection();

// Handle different API endpoints based on action
switch($action) {
    
    /**
     * USER REGISTRATION
     * URL: api.php?action=register
     * Method: POST
     * Body: {name, email, phone, password}
     */
    case 'register':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            $name = isset($data['name']) ? sanitize($data['name']) : '';
            $email = isset($data['email']) ? sanitize($data['email']) : '';
            $phone = isset($data['phone']) ? sanitize($data['phone']) : '';
            $password = isset($data['password']) ? $data['password'] : '';
            
            // Validate inputs
            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                sendJSON(['success' => false, 'message' => 'All fields are required']);
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                sendJSON(['success' => false, 'message' => 'Invalid email format']);
            }
            
            if (strlen($password) < 6) {
                sendJSON(['success' => false, 'message' => 'Password must be at least 6 characters']);
            }
            
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                sendJSON(['success' => false, 'message' => 'Email already registered']);
            }
            $stmt->close();
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $sql = "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);
            
            if ($stmt->execute()) {
                $userId = $conn->insert_id;
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                sendJSON([
                    'success' => true,
                    'message' => 'Registration successful',
                    'user' => [
                        'id' => $userId,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone
                    ]
                ]);
            } else {
                sendJSON(['success' => false, 'message' => 'Registration failed']);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * USER LOGIN
     * URL: api.php?action=login
     * Method: POST
     * Body: {email, password}
     */
    case 'login':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            $email = isset($data['email']) ? sanitize($data['email']) : '';
            $password = isset($data['password']) ? $data['password'] : '';
            
            if (empty($email) || empty($password)) {
                sendJSON(['success' => false, 'message' => 'Email and password are required']);
            }
            
            // Get user from database
            $sql = "SELECT id, name, email, phone, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                sendJSON(['success' => false, 'message' => 'Invalid email or password']);
            }
            
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                sendJSON([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'phone' => $user['phone']
                    ]
                ]);
            } else {
                sendJSON(['success' => false, 'message' => 'Invalid email or password']);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * USER LOGOUT
     * URL: api.php?action=logout
     * Method: POST
     */
    case 'logout':
        session_destroy();
        sendJSON(['success' => true, 'message' => 'Logged out successfully']);
        break;
    
    /**
     * GET CURRENT USER
     * URL: api.php?action=get_user
     * Method: GET
     */
    case 'get_user':
        if (isLoggedIn()) {
            $user = getCurrentUser();
            sendJSON(['success' => true, 'user' => $user]);
        } else {
            sendJSON(['success' => false, 'message' => 'Not logged in']);
        }
        break;
    
    /**
     * GET ALL PRODUCTS
     * URL: api.php?action=get_products
     * Method: GET
     */
    case 'get_products':
        try {
            $sql = "SELECT * FROM products ORDER BY id ASC";
            $result = $conn->query($sql);
            $products = [];
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $products[] = [
                        'id' => (int)$row['id'],
                        'name' => $row['name'],
                        'category' => $row['category'],
                        'price' => (float)$row['price'],
                        'sku' => $row['sku'],
                        'short' => $row['short_desc'],
                        'description' => $row['description']
                    ];
                }
            }
            
            sendJSON([
                'success' => true, 
                'products' => $products,
                'count' => count($products)
            ]);
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error fetching products: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * GET SINGLE PRODUCT
     * URL: api.php?action=get_product&id=1
     * Method: GET
     */
    case 'get_product':
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($id <= 0) {
                sendJSON(['success' => false, 'message' => 'Invalid product ID']);
            }
            
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $product = [
                    'id' => (int)$row['id'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'price' => (float)$row['price'],
                    'sku' => $row['sku'],
                    'short' => $row['short_desc'],
                    'description' => $row['description']
                ];
                sendJSON(['success' => true, 'product' => $product]);
            } else {
                sendJSON(['success' => false, 'message' => 'Product not found']);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * PLACE ORDER
     * URL: api.php?action=place_order
     * Method: POST
     * Body: {order: {...}, items: [...]}
     */
    case 'place_order':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method. Use POST.']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!isset($data['order']) || !isset($data['items'])) {
                sendJSON(['success' => false, 'message' => 'Invalid order data. Missing order or items.']);
            }
            
            $order = $data['order'];
            $items = $data['items'];
            
            $requiredFields = ['name', 'email', 'phone', 'city', 'address'];
            foreach ($requiredFields as $field) {
                if (empty($order[$field])) {
                    sendJSON(['success' => false, 'message' => "Field '$field' is required"]);
                }
            }
            
            if (empty($items) || !is_array($items)) {
                sendJSON(['success' => false, 'message' => 'Cart is empty']);
            }
            
            $order = sanitize($order);
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            
            $conn->begin_transaction();
            
            try {
                $orderId = 'ORD' . strtoupper(substr(uniqid(), -6));
                $totalAmount = 0;
                foreach ($items as $item) {
                    $totalAmount += floatval($item['price']) * intval($item['qty']);
                }
                
                $sql = "INSERT INTO orders (order_id, user_id, customer_name, email, phone, city, address, total_amount, payment_status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sisssssd", 
                    $orderId,
                    $userId,
                    $order['name'],
                    $order['email'],
                    $order['phone'],
                    $order['city'],
                    $order['address'],
                    $totalAmount
                );
                
                if (!$stmt->execute()) {
                    throw new Exception('Failed to insert order: ' . $stmt->error);
                }
                
                $orderDbId = $conn->insert_id;
                $stmt->close();
                
                $sql = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                foreach ($items as $item) {
                    $subtotal = floatval($item['price']) * intval($item['qty']);
                    $stmt->bind_param("iisdid",
                        $orderDbId,
                        $item['id'],
                        $item['name'],
                        $item['price'],
                        $item['qty'],
                        $subtotal
                    );
                    
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to insert order item: ' . $stmt->error);
                    }
                }
                
                $stmt->close();
                $conn->commit();
                
                sendJSON([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $orderId,
                    'total' => $totalAmount,
                    'order_db_id' => $orderDbId
                ]);
                
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            logError('Order placement error: ' . $e->getMessage());
            sendJSON(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * COMPLETE PAYMENT
     * URL: api.php?action=complete_payment
     * Method: POST
     * Body: {order_id: "ORD123456"}
     */
    case 'complete_payment':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            $orderId = isset($data['order_id']) ? sanitize($data['order_id']) : '';
            
            if (empty($orderId)) {
                sendJSON(['success' => false, 'message' => 'Order ID required']);
            }
            
            $sql = "UPDATE orders SET payment_status = 'completed' WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $orderId);
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                sendJSON([
                    'success' => true, 
                    'message' => 'Payment completed successfully',
                    'order_id' => $orderId
                ]);
            } else {
                sendJSON(['success' => false, 'message' => 'Payment update failed or order not found']);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Payment error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * GET ORDER DETAILS
     * URL: api.php?action=get_order&order_id=ORD123456
     * Method: GET
     */
    case 'get_order':
        try {
            $orderId = isset($_GET['order_id']) ? sanitize($_GET['order_id']) : '';
            
            if (empty($orderId)) {
                sendJSON(['success' => false, 'message' => 'Order ID required']);
            }
            
            $sql = "SELECT o.*, 
                    GROUP_CONCAT(
                        CONCAT(oi.product_name, ':', oi.quantity, ':', oi.price, ':', oi.subtotal) 
                        SEPARATOR '|'
                    ) as items
                    FROM orders o
                    LEFT JOIN order_items oi ON o.id = oi.order_id
                    WHERE o.order_id = ?
                    GROUP BY o.id";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                $items = [];
                if (!empty($row['items'])) {
                    $itemsArray = explode('|', $row['items']);
                    foreach ($itemsArray as $item) {
                        $parts = explode(':', $item);
                        if (count($parts) === 4) {
                            $items[] = [
                                'name' => $parts[0],
                                'qty' => (int)$parts[1],
                                'price' => (float)$parts[2],
                                'subtotal' => (float)$parts[3]
                            ];
                        }
                    }
                }
                
                $order = [
                    'order_id' => $row['order_id'],
                    'name' => $row['customer_name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'city' => $row['city'],
                    'address' => $row['address'],
                    'total' => (float)$row['total_amount'],
                    'status' => $row['payment_status'],
                    'date' => $row['order_date'],
                    'items' => $items
                ];
                
                sendJSON(['success' => true, 'order' => $order]);
            } else {
                sendJSON(['success' => false, 'message' => 'Order not found']);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * SAVE CART TO DATABASE
     * URL: api.php?action=save_cart
     * Method: POST
     * Body: {cart: [{productId: 1, qty: 2}, ...]}
     */
    case 'save_cart':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            $cart = isset($data['cart']) ? $data['cart'] : [];
            $sessionId = $_SESSION['cart_session_id'];
            
            $conn->begin_transaction();
            
            try {
                $sql = "DELETE FROM cart WHERE session_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $sessionId);
                $stmt->execute();
                $stmt->close();
                
                if (!empty($cart) && is_array($cart)) {
                    $sql = "INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    foreach ($cart as $item) {
                        if (isset($item['productId']) && isset($item['qty'])) {
                            $stmt->bind_param("sii", $sessionId, $item['productId'], $item['qty']);
                            $stmt->execute();
                        }
                    }
                    $stmt->close();
                }
                
                $conn->commit();
                sendJSON(['success' => true, 'message' => 'Cart saved successfully']);
                
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Cart save error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * GET CART FROM DATABASE
     * URL: api.php?action=get_cart
     * Method: GET
     */
    case 'get_cart':
        try {
            $sessionId = $_SESSION['cart_session_id'];
            
            $sql = "SELECT c.product_id, c.quantity 
                    FROM cart c 
                    WHERE c.session_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $cart = [];
            while ($row = $result->fetch_assoc()) {
                $cart[] = [
                    'productId' => (int)$row['product_id'],
                    'qty' => (int)$row['quantity']
                ];
            }
            
            sendJSON(['success' => true, 'cart' => $cart]);
            $stmt->close();
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * GET ALL ORDERS (Admin feature)
     * URL: api.php?action=get_all_orders
     * Method: GET
     */
    case 'get_all_orders':
        try {
            $sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 50";
            $result = $conn->query($sql);
            $orders = [];
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $orders[] = [
                        'order_id' => $row['order_id'],
                        'customer_name' => $row['customer_name'],
                        'email' => $row['email'],
                        'total' => (float)$row['total_amount'],
                        'status' => $row['payment_status'],
                        'date' => $row['order_date']
                    ];
                }
            }
            
            sendJSON(['success' => true, 'orders' => $orders]);
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
    
    /**
     * DEFAULT CASE - Invalid action
     */
    default:
        sendJSON([
            'success' => false, 
            'message' => 'Invalid action specified',
            'available_actions' => [
                'register',
                'login',
                'logout',
                'get_user',
                'get_products',
                'get_product',
                'place_order',
                'complete_payment',
                'get_order',
                'save_cart',
                'get_cart',
                'get_all_orders'
            ]
        ]);
}

$conn->close();
?>