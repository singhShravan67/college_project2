<?php
/**
 * Admin API Endpoints
 * Backend for admin panel operations
 */

require_once 'config.php';

// Check if admin is logged in (simple check)
// session_start() already called in config.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    sendJSON(['success' => false, 'message' => 'Unauthorized access']);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$conn = getDBConnection();

switch($action) {
    
    /**
     * DASHBOARD STATISTICS
     */
    case 'dashboard_stats':
        try {
            $stats = [];
            
            // Total orders
            $result = $conn->query("SELECT COUNT(*) as count FROM orders");
            $stats['total_orders'] = $result->fetch_assoc()['count'];
            
            // Total revenue
            $result = $conn->query("SELECT SUM(total_amount) as revenue FROM orders WHERE payment_status = 'completed'");
            $stats['total_revenue'] = number_format($result->fetch_assoc()['revenue'] ?? 0, 2);
            
            // Total products
            $result = $conn->query("SELECT COUNT(*) as count FROM products");
            $stats['total_products'] = $result->fetch_assoc()['count'];
            
            // Pending orders
            $result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE payment_status = 'pending'");
            $stats['pending_orders'] = $result->fetch_assoc()['count'];
            
            sendJSON(['success' => true, 'stats' => $stats]);
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * RECENT ORDERS (Last 10)
     */
    case 'recent_orders':
        try {
            $sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 10";
            $result = $conn->query($sql);
            $orders = [];
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
            }
            
            sendJSON(['success' => true, 'orders' => $orders]);
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * ALL ORDERS
     */
    case 'all_orders':
        try {
            $sql = "SELECT * FROM orders ORDER BY order_date DESC";
            $result = $conn->query($sql);
            $orders = [];
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
            }
            
            sendJSON(['success' => true, 'orders' => $orders]);
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * ADD PRODUCT
     */
    case 'add_product':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            // Validate required fields
            if (empty($data['name']) || empty($data['category']) || empty($data['price']) || empty($data['sku'])) {
                sendJSON(['success' => false, 'message' => 'All required fields must be filled']);
            }
            
            // Check if SKU already exists
            $stmt = $conn->prepare("SELECT id FROM products WHERE sku = ?");
            $stmt->bind_param("s", $data['sku']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                sendJSON(['success' => false, 'message' => 'SKU already exists']);
            }
            
            // Insert product
            $sql = "INSERT INTO products (name, category, price, sku, short_desc, description) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsss", 
                $data['name'],
                $data['category'],
                $data['price'],
                $data['sku'],
                $data['short'],
                $data['description']
            );
            
            if ($stmt->execute()) {
                sendJSON(['success' => true, 'message' => 'Product added successfully', 'id' => $conn->insert_id]);
            } else {
                sendJSON(['success' => false, 'message' => 'Failed to add product']);
            }
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * UPDATE PRODUCT
     */
    case 'update_product':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (empty($data['id'])) {
                sendJSON(['success' => false, 'message' => 'Product ID required']);
            }
            
            // Check if SKU exists for other products
            $stmt = $conn->prepare("SELECT id FROM products WHERE sku = ? AND id != ?");
            $stmt->bind_param("si", $data['sku'], $data['id']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                sendJSON(['success' => false, 'message' => 'SKU already exists for another product']);
            }
            
            // Update product
            $sql = "UPDATE products SET name = ?, category = ?, price = ?, sku = ?, short_desc = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsssi", 
                $data['name'],
                $data['category'],
                $data['price'],
                $data['sku'],
                $data['short'],
                $data['description'],
                $data['id']
            );
            
            if ($stmt->execute()) {
                sendJSON(['success' => true, 'message' => 'Product updated successfully']);
            } else {
                sendJSON(['success' => false, 'message' => 'Failed to update product']);
            }
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * DELETE PRODUCT
     */
    case 'delete_product':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (empty($data['id'])) {
                sendJSON(['success' => false, 'message' => 'Product ID required']);
            }
            
            // Check if product exists in any orders
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM order_items WHERE product_id = ?");
            $stmt->bind_param("i", $data['id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result['count'] > 0) {
                sendJSON(['success' => false, 'message' => 'Cannot delete product. It exists in orders.']);
            }
            
            // Delete product
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $data['id']);
            
            if ($stmt->execute()) {
                sendJSON(['success' => true, 'message' => 'Product deleted successfully']);
            } else {
                sendJSON(['success' => false, 'message' => 'Failed to delete product']);
            }
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * GET CUSTOMERS WITH STATS
     */
    case 'customers':
        try {
            $sql = "SELECT 
                        o.customer_name,
                        o.email,
                        o.phone,
                        o.city,
                        COUNT(o.id) as order_count,
                        SUM(o.total_amount) as total_spent
                    FROM orders o
                    GROUP BY o.email
                    ORDER BY total_spent DESC";
            
            $result = $conn->query($sql);
            $customers = [];
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $customers[] = [
                        'customer_name' => $row['customer_name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'city' => $row['city'],
                        'order_count' => $row['order_count'],
                        'total_spent' => number_format($row['total_spent'], 2)
                    ];
                }
            }
            
            sendJSON(['success' => true, 'customers' => $customers]);
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * UPDATE ORDER STATUS
     */
    case 'update_order_status':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (empty($data['order_id']) || empty($data['status'])) {
                sendJSON(['success' => false, 'message' => 'Order ID and status required']);
            }
            
            $validStatuses = ['pending', 'completed', 'failed'];
            if (!in_array($data['status'], $validStatuses)) {
                sendJSON(['success' => false, 'message' => 'Invalid status']);
            }
            
            $stmt = $conn->prepare("UPDATE orders SET payment_status = ? WHERE order_id = ?");
            $stmt->bind_param("ss", $data['status'], $data['order_id']);
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                sendJSON(['success' => true, 'message' => 'Order status updated']);
            } else {
                sendJSON(['success' => false, 'message' => 'Failed to update status or order not found']);
            }
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * DELETE ORDER
     */
    case 'delete_order':
        if ($method !== 'POST') {
            sendJSON(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (empty($data['order_id'])) {
                sendJSON(['success' => false, 'message' => 'Order ID required']);
            }
            
            // Get order database ID
            $stmt = $conn->prepare("SELECT id FROM orders WHERE order_id = ?");
            $stmt->bind_param("s", $data['order_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                sendJSON(['success' => false, 'message' => 'Order not found']);
            }
            
            $orderDbId = $result->fetch_assoc()['id'];
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Delete order items first (foreign key constraint)
                $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
                $stmt->bind_param("i", $orderDbId);
                $stmt->execute();
                
                // Delete order
                $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
                $stmt->bind_param("i", $orderDbId);
                $stmt->execute();
                
                $conn->commit();
                sendJSON(['success' => true, 'message' => 'Order deleted successfully']);
                
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    /**
     * SALES REPORT (Optional - Advanced feature)
     */
    case 'sales_report':
        try {
            $period = isset($_GET['period']) ? $_GET['period'] : 'month';
            
            $dateFilter = match($period) {
                'today' => "DATE(order_date) = CURDATE()",
                'week' => "order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)",
                'month' => "order_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
                'year' => "order_date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)",
                default => "1=1"
            };
            
            $sql = "SELECT 
                        DATE(order_date) as date,
                        COUNT(*) as order_count,
                        SUM(total_amount) as revenue
                    FROM orders
                    WHERE $dateFilter AND payment_status = 'completed'
                    GROUP BY DATE(order_date)
                    ORDER BY date DESC";
            
            $result = $conn->query($sql);
            $report = [];
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $report[] = [
                        'date' => $row['date'],
                        'order_count' => $row['order_count'],
                        'revenue' => number_format($row['revenue'], 2)
                    ];
                }
            }
            
            sendJSON(['success' => true, 'report' => $report]);
        } catch (Exception $e) {
            sendJSON(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    
    default:
        sendJSON([
            'success' => false, 
            'message' => 'Invalid action',
            'available_actions' => [
                'dashboard_stats',
                'recent_orders',
                'all_orders',
                'add_product',
                'update_product',
                'delete_product',
                'customers',
                'update_order_status',
                'delete_order',
                'sales_report'
            ]
        ]);
}

$conn->close();
?>