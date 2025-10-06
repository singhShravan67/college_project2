<?php
require_once 'config.php';

// Simple authentication (change these credentials)
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'admin123';

// Login check
if (isset($_POST['login'])) {
    if ($_POST['username'] === $ADMIN_USER && $_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Invalid credentials!';
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Check if logged in
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopMini Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1f2937;
            --muted: #6b7280;
            --light: #f9fafb;
            --border: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light);
            color: var(--dark);
        }

        /* LOGIN PAGE */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-box {
            background: white;
            padding: 48px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
        }

        .login-box h2 {
            margin-bottom: 32px;
            text-align: center;
            color: var(--dark);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }

        .error {
            background: #fee;
            color: var(--danger);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* ADMIN LAYOUT */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid var(--border);
            padding: 24px 0;
        }

        .sidebar-header {
            padding: 0 24px 24px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-header h2 {
            font-size: 22px;
            color: var(--primary);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin-bottom: 4px;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 24px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--light);
            color: var(--primary);
            border-right: 3px solid var(--primary);
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            background: white;
            padding: 20px 28px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .top-bar h1 {
            font-size: 28px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid var(--primary);
        }

        .stat-card h3 {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px 24px;
            text-align: left;
        }

        th {
            background: var(--light);
            font-weight: 600;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr {
            border-bottom: 1px solid var(--border);
        }

        tbody tr:hover {
            background: var(--light);
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-success {
            background: var(--secondary);
            color: white;
        }

        .btn-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 32px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            font-size: 22px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: var(--muted);
        }

        /* FORM */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
    <!-- LOGIN PAGE -->
    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
            <p style="margin-top:20px;text-align:center;color:var(--muted);font-size:13px">
                Default: admin / admin123
            </p>
        </div>
    </div>

<?php else: ?>
    <!-- ADMIN PANEL -->
    <div class="admin-layout">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>ShopMini Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="menu-link active" data-section="dashboard">Dashboard</a></li>
                <li><a href="#" class="menu-link" data-section="orders">Orders</a></li>
                <li><a href="#" class="menu-link" data-section="products">Products</a></li>
                <li><a href="#" class="menu-link" data-section="customers">Customers</a></li>
                <li><a href="?logout=1" style="color:var(--danger)">Logout</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="top-bar">
                <h1 id="page-title">Dashboard</h1>
                <div>Welcome, Admin</div>
            </div>

            <!-- DASHBOARD SECTION -->
            <div class="section active" id="dashboard">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Orders</h3>
                        <div class="value" id="total-orders">0</div>
                    </div>
                    <div class="stat-card" style="border-color: var(--secondary)">
                        <h3>Total Revenue</h3>
                        <div class="value" id="total-revenue">₹0</div>
                    </div>
                    <div class="stat-card" style="border-color: var(--warning)">
                        <h3>Total Products</h3>
                        <div class="value" id="total-products">0</div>
                    </div>
                    <div class="stat-card" style="border-color: var(--danger)">
                        <h3>Pending Orders</h3>
                        <div class="value" id="pending-orders">0</div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <h2>Recent Orders</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recent-orders">
                            <tr><td colspan="5" style="text-align:center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ORDERS SECTION -->
            <div class="section" id="orders">
                <div class="table-container">
                    <div class="table-header">
                        <h2>All Orders</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="all-orders">
                            <tr><td colspan="8" style="text-align:center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PRODUCTS SECTION -->
            <div class="section" id="products">
                <div class="table-container">
                    <div class="table-header">
                        <h2>All Products</h2>
                        <button class="btn-small btn-primary" onclick="openAddProduct()">Add Product</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>SKU</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="all-products">
                            <tr><td colspan="6" style="text-align:center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CUSTOMERS SECTION -->
            <div class="section" id="customers">
                <div class="table-container">
                    <div class="table-header">
                        <h2>All Customers</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Total Orders</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>
                        <tbody id="all-customers">
                            <tr><td colspan="6" style="text-align:center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- ORDER DETAIL MODAL -->
    <div class="modal" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Order Details</h3>
                <button class="close-modal" onclick="closeModal('orderModal')">&times;</button>
            </div>
            <div id="order-detail-content"></div>
        </div>
    </div>

    <!-- ADD/EDIT PRODUCT MODAL -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="product-modal-title">Add Product</h3>
                <button class="close-modal" onclick="closeModal('productModal')">&times;</button>
            </div>
            <form id="productForm">
                <input type="hidden" id="product-id">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" id="product-name" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" id="product-category" required>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" id="product-price" step="0.01" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" id="product-sku" required>
                </div>
                <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" id="product-short">
                </div>
                <div class="form-group">
                    <label>Full Description</label>
                    <textarea id="product-description"></textarea>
                </div>
                <button type="submit" class="btn">Save Product</button>
            </form>
        </div>
    </div>

    <script>
        // Navigation
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.dataset.section;
                
                document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                document.getElementById(section).classList.add('active');
                
                document.getElementById('page-title').textContent = 
                    section.charAt(0).toUpperCase() + section.slice(1);
                
                loadSectionData(section);
            });
        });

        // Load data
        function loadSectionData(section) {
            switch(section) {
                case 'dashboard':
                    loadDashboard();
                    break;
                case 'orders':
                    loadAllOrders();
                    break;
                case 'products':
                    loadProducts();
                    break;
                case 'customers':
                    loadCustomers();
                    break;
            }
        }

        // Dashboard
        function loadDashboard() {
            fetch('admin_api.php?action=dashboard_stats')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-orders').textContent = data.stats.total_orders;
                        document.getElementById('total-revenue').textContent = '₹' + data.stats.total_revenue;
                        document.getElementById('total-products').textContent = data.stats.total_products;
                        document.getElementById('pending-orders').textContent = data.stats.pending_orders;
                        
                        loadRecentOrders();
                    }
                });
        }

        function loadRecentOrders() {
            fetch('admin_api.php?action=recent_orders')
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('recent-orders');
                    if (data.success && data.orders.length > 0) {
                        tbody.innerHTML = data.orders.map(o => `
                            <tr>
                                <td><strong>${o.order_id}</strong></td>
                                <td>${o.customer_name}</td>
                                <td>₹${o.total_amount}</td>
                                <td><span class="badge badge-${o.payment_status === 'completed' ? 'success' : 'warning'}">${o.payment_status}</span></td>
                                <td>${new Date(o.order_date).toLocaleDateString()}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center">No orders found</td></tr>';
                    }
                });
        }

        // All Orders
        function loadAllOrders() {
            fetch('admin_api.php?action=all_orders')
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('all-orders');
                    if (data.success && data.orders.length > 0) {
                        tbody.innerHTML = data.orders.map(o => `
                            <tr>
                                <td><strong>${o.order_id}</strong></td>
                                <td>${o.customer_name}</td>
                                <td>${o.email}</td>
                                <td>${o.phone}</td>
                                <td>₹${o.total_amount}</td>
                                <td><span class="badge badge-${o.payment_status === 'completed' ? 'success' : 'warning'}">${o.payment_status}</span></td>
                                <td>${new Date(o.order_date).toLocaleDateString()}</td>
                                <td>
                                    <button class="btn-small btn-primary" onclick="viewOrder('${o.order_id}')">View</button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center">No orders found</td></tr>';
                    }
                });
        }

        function viewOrder(orderId) {
            fetch(`api.php?action=get_order&order_id=${orderId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const o = data.order;
                        document.getElementById('order-detail-content').innerHTML = `
                            <div style="margin-bottom:20px">
                                <strong>Order ID:</strong> ${o.order_id}<br>
                                <strong>Customer:</strong> ${o.name}<br>
                                <strong>Email:</strong> ${o.email}<br>
                                <strong>Phone:</strong> ${o.phone}<br>
                                <strong>Address:</strong> ${o.address}, ${o.city}<br>
                                <strong>Status:</strong> <span class="badge badge-${o.status === 'completed' ? 'success' : 'warning'}">${o.status}</span>
                            </div>
                            <h4>Order Items:</h4>
                            <table style="width:100%;margin-top:12px">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${o.items.map(item => `
                                        <tr>
                                            <td>${item.name}</td>
                                            <td>${item.qty}</td>
                                            <td>₹${item.price}</td>
                                            <td>₹${item.subtotal}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                            <div style="text-align:right;margin-top:20px;font-size:18px;font-weight:700">
                                Total: ₹${o.total}
                            </div>
                        `;
                        document.getElementById('orderModal').classList.add('active');
                    }
                });
        }

        // Products
        function loadProducts() {
            fetch('api.php?action=get_products')
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('all-products');
                    if (data.success && data.products.length > 0) {
                        tbody.innerHTML = data.products.map(p => `
                            <tr>
                                <td>${p.id}</td>
                                <td>${p.name}</td>
                                <td>${p.category}</td>
                                <td>₹${p.price}</td>
                                <td>${p.sku}</td>
                                <td>
                                    <button class="btn-small btn-primary" onclick="editProduct(${p.id})">Edit</button>
                                    <button class="btn-small btn-danger" onclick="deleteProduct(${p.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center">No products found</td></tr>';
                    }
                });
        }

        function openAddProduct() {
            document.getElementById('product-modal-title').textContent = 'Add Product';
            document.getElementById('productForm').reset();
            document.getElementById('product-id').value = '';
            document.getElementById('productModal').classList.add('active');
        }

        function editProduct(id) {
            fetch(`api.php?action=get_product&id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const p = data.product;
                        document.getElementById('product-modal-title').textContent = 'Edit Product';
                        document.getElementById('product-id').value = p.id;
                        document.getElementById('product-name').value = p.name;
                        document.getElementById('product-category').value = p.category;
                        document.getElementById('product-price').value = p.price;
                        document.getElementById('product-sku').value = p.sku;
                        document.getElementById('product-short').value = p.short || '';
                        document.getElementById('product-description').value = p.description || '';
                        document.getElementById('productModal').classList.add('active');
                    }
                });
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                fetch('admin_api.php?action=delete_product', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) loadProducts();
                });
            }
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = {
                id: document.getElementById('product-id').value,
                name: document.getElementById('product-name').value,
                category: document.getElementById('product-category').value,
                price: document.getElementById('product-price').value,
                sku: document.getElementById('product-sku').value,
                short: document.getElementById('product-short').value,
                description: document.getElementById('product-description').value
            };

            const action = formData.id ? 'update_product' : 'add_product';
            
            fetch(`admin_api.php?action=${action}`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(formData)
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    closeModal('productModal');
                    loadProducts();
                }
            });
        });

        // Customers
        function loadCustomers() {
            fetch('admin_api.php?action=customers')
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('all-customers');
                    if (data.success && data.customers.length > 0) {
                        tbody.innerHTML = data.customers.map(c => `
                            <tr>
                                <td>${c.customer_name}</td>
                                <td>${c.email}</td>
                                <td>${c.phone}</td>
                                <td>${c.city}</td>
                                <td>${c.order_count}</td>
                                <td>₹${c.total_spent}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center">No customers found</td></tr>';
                    }
                });
        }

        // Modal functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Initial load
        loadDashboard();
    </script>

<?php endif; ?>

</body>
</html>