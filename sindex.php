<?php require_once 'config.php'; ?>
<!doctype html>
<html lang="en" ng-app="shopApp">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>ShopMini - Modern E-Commerce</title>

    <!-- AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
         :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #1f2937;
            --muted: #6b7280;
            --light-bg: #f9fafb;
            --card-bg: #ffffff;
            --border: #e5e7eb;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html,
        body {
            height: 100%;
            background: var(--light-bg);
            color: var(--dark);
            scroll-behavior: smooth;
            line-height: 1.6;
        }
        
        a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: var(--primary);
        }
        
        /* NAVBAR - Modern Design */
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 32px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-md);
            border-bottom: 1px solid var(--border);
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
        }
        
        .brand .logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            box-shadow: var(--shadow-md);
        }
        
        .brand-text h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
        }
        
        .brand-text small {
            font-size: 11px;
            color: var(--muted);
            font-weight: 400;
        }
        
        .nav .search {
            flex: 1;
            max-width: 550px;
            margin: 0 28px;
        }
        
        .search input {
            width: 100%;
            padding: 12px 20px;
            border-radius: 12px;
            border: 2px solid var(--border);
            background: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .nav .links {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .icon-btn {
            padding: 10px 18px;
            border-radius: 10px;
            border: 0;
            background: transparent;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            color: var(--dark);
        }
        
        .icon-btn:hover {
            background: var(--light-bg);
            color: var(--primary);
        }
        
        /* LAYOUT */
        .container {
            max-width: 1280px;
            margin: 32px auto;
            padding: 0 24px;
        }
        
        /* HERO SECTION */
        .hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            border-radius: 24px;
            color: white;
            margin-bottom: 40px;
            box-shadow: var(--shadow-xl);
        }
        
        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 24px;
        }
        
        /* CATEGORIES */
        .cats {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .cat {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .cat:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .cat.active {
            background: white;
            color: var(--primary);
            border-color: white;
        }
        
        /* CAROUSEL */
        .carousel {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            height: 280px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .carousel-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }
        
        .carousel-item {
            min-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        
        .carousel-item h2 {
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        .carousel-item p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 16px;
        }
        
        .carousel-controls {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            pointer-events: none;
        }
        
        .carousel-controls button {
            pointer-events: all;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            color: var(--dark);
        }
        
        .carousel-controls button:hover {
            background: white;
            transform: scale(1.1);
        }
        
        /* BUTTONS */
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-ghost {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        
        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }
        
        /* SECTION HEADERS */
        h2, h3 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 24px;
        }
        
        h2 {
            font-size: 32px;
        }
        
        h3 {
            font-size: 26px;
            margin-top: 48px;
        }
        
        /* PRODUCT GRID */
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 28px;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 18px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }
        
        .thumb {
            height: 200px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--muted);
            font-weight: 600;
        }
        
        .meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }
        
        .meta > div:first-child {
            font-weight: 600;
            font-size: 16px;
            color: var(--dark);
        }
        
        .price {
            font-weight: 700;
            color: var(--primary);
            font-size: 20px;
        }
        
        .card .btn {
            width: 100%;
            margin-top: 8px;
        }
        
        .card .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .card .btn-ghost {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        /* PRODUCT DETAIL */
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            margin-top: 32px;
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--shadow-md);
        }
        
        .product-detail .thumb {
            height: 400px;
        }
        
        .qty {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .qty input {
            width: 80px;
        }
        
        /* FILTERS SIDEBAR */
        .filter-sidebar {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }
        
        .filter-sidebar h4 {
            margin-bottom: 16px;
            color: var(--dark);
            font-weight: 600;
        }
        
        .filter-sidebar .cats .cat {
            background: var(--light-bg);
            border: 2px solid var(--border);
            color: var(--dark);
        }
        
        .filter-sidebar .cats .cat.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        /* CART */
        .cart-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .cart-item {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }
        
        .cart-item:hover {
            box-shadow: var(--shadow-md);
        }
        
        /* FORMS */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: white;
            padding: 32px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
        }
        
        input,
        textarea,
        select {
            padding: 14px 18px;
            border-radius: 12px;
            border: 2px solid var(--border);
            background: white;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        /* RECEIPT */
        .receipt-card {
            background: white;
            padding: 32px;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            border: 2px solid var(--secondary);
        }
        
        .receipt-card hr {
            border: none;
            border-top: 2px dashed var(--border);
            margin: 20px 0;
        }
        
        /* FOOTER */
        footer {
            margin: 60px 0 40px;
            padding: 40px 24px;
            background: white;
            border-radius: 20px 20px 0 0;
            text-align: center;
            color: var(--muted);
            box-shadow: var(--shadow-md);
        }
        
        /* UTILITY CLASSES */
        .badge {
            display: inline-block;
            padding: 6px 14px;
            background: var(--primary);
            color: white;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .success-badge {
            background: var(--secondary);
        }
        
        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .hero {
                grid-template-columns: 1fr;
            }
            
            .product-detail {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .nav {
                padding: 12px 16px;
            }
            
            .nav .search {
                display: none;
            }
            
            .hero {
                padding: 40px 24px;
            }
            
            .hero h1 {
                font-size: 32px;
            }
            
            .container {
                padding: 0 16px;
            }
            
            .products {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 16px;
            }
        }
    </style>
</head>

<body ng-controller="MainCtrl as MC">

    <!-- NAVBAR -->
    <header class="nav">
        <div class="brand" ng-click="MC.go('home')">
            <div class="logo">S</div>
            <div class="brand-text">
                <h1>ShopMini</h1>
                <small>Database Integrated</small>
            </div>
        </div>

        <div class="search">
            <input type="search" placeholder="🔍 Search products..." ng-model="MC.searchText" ng-change="MC.onSearch()" />
        </div>

        <div class="links">
            <a href="#/" class="icon-btn">🏠 Home</a>
            <a href="#/products" class="icon-btn">📦 Products</a>
            <a href="#/my-orders" class="icon-btn">📋 My Orders</a>
            <a href="#/cart" class="icon-btn">🛒 Cart ({{MC.cartTotalCount()}})</a>
        </div>
    </header>

    <main class="container">

        <!-- HOME PAGE -->
        <section ng-show="MC.view==='home'">
            <div class="hero">
                <div>
                    <h1>Welcome to ShopMini</h1>
                    <p>Discover amazing products at unbeatable prices. Your one-stop shop for everything you need!</p>

                    <div class="cats">
                        <div class="cat" ng-class="{active:MC.selectedCategory==null}" ng-click="MC.filterByCat(null)">All Products</div>
                        <div class="cat" ng-repeat="c in MC.categories" ng-click="MC.filterByCat(c)" ng-class="{active:MC.selectedCategory==c}">{{c}}</div>
                    </div>

                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
                        <button class="btn btn-primary" ng-click="MC.go('products')">Browse Products →</button>
                        <button class="btn btn-ghost" ng-click="MC.go('cart')">View Cart ({{MC.cartTotalCount()}})</button>
                    </div>
                </div>

                <div>
                    <div class="carousel" ng-init="MC.startCarousel()">
                        <div class="carousel-track" ng-style="{transform: 'translateX(-'+(MC.carouselIndex*100)+'%)'}">
                            <div class="carousel-item" ng-repeat="item in MC.featured">
                                <div style="text-align:center;max-width:480px">
                                    <h2>{{item.name}}</h2>
                                    <p>{{item.short}}</p>
                                    <div style="font-size:28px;font-weight:700">₹{{item.price}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-controls">
                            <button ng-click="MC.prevSlide()">◀</button>
                            <button ng-click="MC.nextSlide()">▶</button>
                        </div>
                    </div>
                </div>
            </div>

            <h3>✨ Featured Products</h3>
            <div class="products">
                <div class="card" ng-repeat="p in MC.products | limitTo:6">
                    <div class="thumb">📦 {{p.id}}</div>
                    <div class="meta">
                        <div>{{p.name}}</div>
                        <small style="color:var(--muted)">{{p.category}}</small>
                        <div class="price">₹{{p.price}}</div>
                    </div>
                    <button class="btn btn-ghost" ng-click="MC.viewProduct(p)">View Details</button>
                    <button class="btn btn-primary" ng-click="MC.addToCart(p)">Add to Cart</button>
                </div>
            </div>
        </section>

        <!-- PRODUCTS PAGE -->
        <section ng-show="MC.view==='products'">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
                <h2>📦 All Products</h2>
                <div style="display:flex;gap:12px;align-items:center">
                    <label style="color:var(--muted);font-weight:500">Sort by:</label>
                    <select ng-model="MC.sortKey">
                        <option value="">Default</option>
                        <option value="price">Price: Low to High</option>
                        <option value="-price">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:280px 1fr;gap:28px">
                <div class="filter-sidebar">
                    <h4>🔍 Filters</h4>
                    <input placeholder="Search products..." ng-model="MC.searchText" ng-change="MC.onSearch()" />
                    <h5 style="margin:20px 0 12px;font-weight:600;color:var(--dark)">Categories</h5>
                    <div class="cats" style="margin:0">
                        <div class="cat" ng-class="{active:MC.selectedCategory==null}" ng-click="MC.filterByCat(null)">All</div>
                        <div class="cat" ng-repeat="c in MC.categories" ng-click="MC.filterByCat(c)" ng-class="{active:MC.selectedCategory==c}">{{c}}</div>
                    </div>
                </div>

                <div>
                    <div class="products">
                        <div class="card" ng-repeat="p in MC.filtered = (MC.products | filter:MC.searchFilter | orderBy:MC.sortKey)">
                            <div class="thumb">📦 {{p.id}}</div>
                            <div class="meta">
                                <div>{{p.name}}</div>
                                <small style="color:var(--muted)">{{p.category}}</small>
                                <div class="price">₹{{p.price}}</div>
                            </div>
                            <button class="btn btn-ghost" ng-click="MC.viewProduct(p)">View</button>
                            <button class="btn btn-primary" ng-click="MC.addToCart(p)">Add to Cart</button>
                        </div>
                    </div>
                    <p style="margin-top:24px;color:var(--muted);font-weight:500">Showing {{MC.filtered.length}} products</p>
                </div>
            </div>
        </section>

        <!-- PRODUCT DETAIL -->
        <section ng-show="MC.view==='product'">
            <button class="btn" style="background:var(--light-bg);color:var(--dark);margin-bottom:20px" ng-click="MC.go('products')">← Back to Products</button>
            <div class="product-detail">
                <div>
                    <div class="thumb">📦 {{MC.currentProduct.id}}</div>
                    <div style="display:flex;gap:12px;margin-top:16px">
                        <span class="badge">{{MC.currentProduct.category}}</span>
                        <span class="badge" style="background:var(--muted)">SKU: {{MC.currentProduct.sku}}</span>
                    </div>
                </div>
                <div>
                    <h2 style="margin-top:0">{{MC.currentProduct.name}}</h2>
                    <p style="color:var(--muted);margin-bottom:16px">{{MC.currentProduct.short}}</p>
                    <div class="price" style="font-size:32px;margin-bottom:24px">₹{{MC.currentProduct.price}}</div>

                    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                        <div class="qty">
                            <label style="font-weight:500">Quantity:</label>
                            <input type="number" ng-model="MC.detailQty" min="1" />
                        </div>
                        <button class="btn btn-primary" ng-click="MC.addToCart(MC.currentProduct, MC.detailQty)">🛒 Add to Cart</button>
                        <button class="btn" style="background:var(--secondary);color:white" ng-click="MC.buyNow(MC.currentProduct, MC.detailQty)">⚡ Buy Now</button>
                    </div>

                    <div style="margin-top:32px">
                        <h4 style="margin-bottom:12px">📝 Description</h4>
                        <p style="color:var(--muted);line-height:1.8">{{MC.currentProduct.description}}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CART -->
        <section ng-show="MC.view==='cart'">
            <h2>🛒 Shopping Cart</h2>
            <div ng-if="MC.cart.length==0" style="background:white;padding:60px;text-align:center;border-radius:20px;box-shadow:var(--shadow-md)">
                <p style="font-size:18px;color:var(--muted);margin-bottom:20px">Your cart is empty</p>
                <a href="#/products" class="btn btn-primary">Start Shopping</a>
            </div>

            <div ng-if="MC.cart.length>0">
                <div class="cart-list">
                    <div class="cart-item" ng-repeat="item in MC.cart">
                        <div style="width:80px;height:80px;background:linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px">📦</div>
                        <div style="flex:1">
                            <div style="font-weight:600;font-size:16px;margin-bottom:4px">{{item.product.name}}</div>
                            <div style="color:var(--muted);font-size:13px">{{item.product.category}}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:12px">
                            <input type="number" ng-model="item.qty" min="1" ng-change="MC.updateCart()" style="width:90px" />
                            <div style="font-weight:700;font-size:18px;color:var(--primary)">₹{{item.product.price * item.qty}}</div>
                            <button class="btn" style="background:var(--light-bg);color:var(--dark);padding:8px 16px" ng-click="MC.removeFromCart(item)">🗑️ Remove</button>
                        </div>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:28px;gap:20px;align-items:center;background:white;padding:24px;border-radius:16px;box-shadow:var(--shadow-md)">
                    <div style="text-align:right">
                        <div style="color:var(--muted);margin-bottom:8px">Total Amount:</div>
                        <div style="font-weight:700;font-size:28px;color:var(--primary)">₹{{MC.cartSubtotal()}}</div>
                    </div>
                    <button class="btn btn-primary" style="padding:14px 32px;font-size:16px" ng-click="MC.go('checkout')">Proceed to Checkout →</button>
                </div>
            </div>
        </section>

        <!-- CHECKOUT -->
        <section ng-show="MC.view==='checkout'">
            <h2>💳 Checkout</h2>
            <form ng-submit="MC.placeOrder()">
                <h4 style="margin-bottom:16px">Shipping Information</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <input required placeholder="Full Name" ng-model="MC.order.name" />
                    <input required type="email" placeholder="Email Address" ng-model="MC.order.email" />
                    <input required placeholder="Mobile Number" ng-model="MC.order.phone" />
                    <input required placeholder="City" ng-model="MC.order.city" />
                </div>
                <textarea required placeholder="Complete Address" ng-model="MC.order.address"></textarea>

                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:2px solid var(--border)">
                    <div>
                        <div style="color:var(--muted);margin-bottom:4px">Total Amount:</div>
                        <div style="font-weight:700;font-size:24px;color:var(--primary)">₹{{MC.cartSubtotal()}}</div>
                    </div>
                    <button class="btn btn-primary" type="submit" style="padding:14px 32px;font-size:16px">Proceed to Payment</button>
                </div>
            </form>
        </section>

        <!-- PAYMENT -->
        <section ng-show="MC.view==='payment'">
            <h2>💳 Payment Details</h2>
            <form ng-submit="MC.pay()">
                <h4 style="margin-bottom:16px">Card Information</h4>
                <input required placeholder="Card Number (16 digits)" ng-model="MC.payment.card" maxlength="19" />
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <input required placeholder="MM/YY" ng-model="MC.payment.exp" />
                    <input required placeholder="CVV" ng-model="MC.payment.cvv" maxlength="4" />
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:2px solid var(--border)">
                    <div>
                        <div style="color:var(--muted);margin-bottom:4px">Amount to Pay:</div>
                        <div style="font-weight:700;font-size:24px;color:var(--primary)">₹{{MC.cartSubtotal()}}</div>
                    </div>
                    <button class="btn btn-primary" type="submit" style="padding:14px 32px;font-size:16px;background:var(--secondary)">Complete Payment</button>
                </div>
            </form>
        </section>

        <!-- RECEIPT -->
        <section ng-show="MC.view==='receipt'">
            <h2>✅ Order Confirmed</h2>
            <div class="receipt-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
                    <div>
                        <div style="color:var(--muted);margin-bottom:8px;font-size:14px">Order ID</div>
                        <div style="font-weight:700;font-size:28px;color:var(--dark)">{{MC.order.id}}</div>
                    </div>
                    <div style="text-align:right">
                        <div style="color:var(--muted);margin-bottom:8px;font-size:14px">Status</div>
                        <span class="badge success-badge" style="font-size:14px;padding:8px 16px">Payment Successful</span>
                    </div>
                </div>

                <hr />
                
                <h4 style="margin-bottom:16px">Order Items</h4>
                <div ng-repeat="it in MC.cartAtPurchase">
                    <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div style="font-weight:600">{{it.product.name}}</div>
                            <div style="color:var(--muted);font-size:13px">Quantity: {{it.qty}}</div>
                        </div>
                        <div style="font-weight:600;color:var(--primary)">₹{{it.product.price * it.qty}}</div>
                    </div>
                </div>
                
                <div style="display:flex;justify-content:space-between;padding:20px 0;font-weight:700;font-size:20px;border-top:2px solid var(--border);margin-top:12px">
                    <span>Total Amount</span>
                    <span style="color:var(--primary)">₹{{MC.cartSubtotal(MC.cartAtPurchase)}}</span>
                </div>

                <div style="background:var(--light-bg);padding:20px;border-radius:12px;margin-top:20px">
                    <p style="color:var(--muted);margin-bottom:12px">A confirmation email has been sent to <strong>{{MC.order.email}}</strong></p>
                    <p style="color:var(--muted);font-size:13px">Thank you for shopping with us!</p>
                </div>
                
                <div style="display:flex;gap:12px;margin-top:24px">
                    <button class="btn btn-primary" ng-click="MC.go('products')" style="flex:1;padding:14px">Continue Shopping</button>
                    <button class="btn" style="flex:1;padding:14px;background:var(--secondary);color:white" ng-click="MC.go('my-orders')">View My Orders</button>
                </div>
            </div>
        </section>

        <!-- MY ORDERS SECTION -->
        <section ng-show="MC.view==='my-orders'">
            <h2>📋 My Orders</h2>
            
            <!-- Email Input Section -->
            <div style="background:white;padding:24px;border-radius:16px;box-shadow:var(--shadow-md);margin-bottom:24px">
                <h4 style="margin-bottom:16px">Track Your Orders</h4>
                <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
                    <input type="email" placeholder="Enter your email to view orders" ng-model="MC.userEmail" ng-keypress="$event.keyCode == 13 && MC.loadUserOrders()" style="padding:14px;border-radius:12px;border:2px solid var(--border);flex:1;min-width:280px">
                    <button class="btn btn-primary" ng-click="MC.loadUserOrders()" style="padding:14px 32px">Load My Orders</button>
                </div>
                <p style="margin-top:12px;color:var(--muted);font-size:13px">Enter the email you used during checkout to view your order history</p>
            </div>

            <!-- Loading State -->
            <div ng-if="MC.loadingOrders" style="text-align:center;padding:40px">
                <div style="font-size:18px;color:var(--muted)">Loading your orders...</div>
            </div>

            <!-- No Orders Found -->
            <div ng-if="!MC.loadingOrders && MC.ordersLoaded && MC.userOrders.length === 0" style="background:white;padding:60px;text-align:center;border-radius:20px;box-shadow:var(--shadow-md)">
                <div style="font-size:48px;margin-bottom:16px">📦</div>
                <p style="font-size:18px;color:var(--muted);margin-bottom:8px">No orders found</p>
                <p style="color:var(--muted);font-size:14px;margin-bottom:24px">We couldn't find any orders for <strong>{{MC.userEmail}}</strong></p>
                <a href="#/products" class="btn btn-primary">Start Shopping</a>
            </div>

            <!-- Orders List -->
            <div ng-if="!MC.loadingOrders && MC.userOrders.length > 0">
                <div style="margin-bottom:20px;color:var(--muted)">
                    Found <strong style="color:var(--primary)">{{MC.userOrders.length}}</strong> order(s) for <strong>{{MC.userEmail}}</strong>
                </div>

                <div style="display:flex;flex-direction:column;gap:20px">
                    <div ng-repeat="order in MC.userOrders" class="cart-item" style="flex-direction:column;align-items:flex-start;cursor:pointer;transition:all 0.3s" ng-click="MC.viewOrderDetails(order.order_id)">
                        <div style="display:flex;justify-content:space-between;width:100%;margin-bottom:16px;flex-wrap:wrap;gap:12px">
                            <div>
                                <div style="font-weight:700;font-size:18px;margin-bottom:8px;color:var(--primary)">Order #{{order.order_id}}</div>
                                <div style="color:var(--muted);font-size:14px">
                                    <span>Placed on: {{order.order_date | date:'MMM dd, yyyy - hh:mm a'}}</span>
                                </div>
                            </div>
                            <div style="text-align:right">
                                <span class="badge" ng-class="order.payment_status === 'completed' ? 'success-badge' : (order.payment_status === 'pending' ? 'badge' : 'badge-danger')" style="display:inline-block;margin-bottom:8px;text-transform:uppercase">
                                    {{order.payment_status === 'completed' ? '✓ Completed' : (order.payment_status === 'pending' ? '⏳ Pending' : '✗ Failed')}}
                                </span>
                                <div style="font-weight:700;font-size:22px;color:var(--primary)">₹{{order.total_amount}}</div>
                            </div>
                        </div>

                        <div style="width:100%;background:var(--light-bg);padding:16px;border-radius:12px;margin-bottom:12px">
                            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px">
                                <div>
                                    <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Customer Name</strong>
                                    <div style="font-weight:500">{{order.customer_name}}</div>
                                </div>
                                <div>
                                    <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Phone</strong>
                                    <div style="font-weight:500">{{order.phone}}</div>
                                </div>
                                <div>
                                    <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">City</strong>
                                    <div style="font-weight:500">{{order.city}}</div>
                                </div>
                            </div>
                            <div style="margin-top:12px">
                                <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Delivery Address</strong>
                                <div style="font-weight:500">{{order.address}}</div>
                            </div>
                        </div>

                        <div style="display:flex;gap:12px;width:100%">
                            <button class="btn btn-primary" ng-click="MC.viewOrderDetails(order.order_id); $event.stopPropagation()" style="flex:1">
                                View Full Details →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ORDER DETAIL VIEW -->
        <section ng-show="MC.view==='order-detail'" ng-if="MC.currentOrder">
            <button class="btn" style="background:var(--light-bg);color:var(--dark);margin-bottom:20px" ng-click="MC.go('my-orders')">← Back to My Orders</button>
            
            <div class="receipt-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px">
                    <div>
                        <div style="color:var(--muted);margin-bottom:8px;font-size:14px">Order ID</div>
                        <div style="font-weight:700;font-size:28px;color:var(--dark)">{{MC.currentOrder.order_id}}</div>
                        <div style="color:var(--muted);font-size:14px;margin-top:4px">{{MC.currentOrder.date | date:'MMMM dd, yyyy - hh:mm a'}}</div>
                    </div>
                    <div style="text-align:right">
                        <div style="color:var(--muted);margin-bottom:8px;font-size:14px">Payment Status</div>
                        <span class="badge" ng-class="MC.currentOrder.status === 'completed' ? 'success-badge' : 'badge'" style="font-size:14px;padding:8px 16px">
                            {{MC.currentOrder.status === 'completed' ? '✓ Payment Successful' : '⏳ Payment Pending'}}
                        </span>
                    </div>
                </div>

                <!-- Customer Details -->
                <div style="background:var(--light-bg);padding:20px;border-radius:12px;margin-bottom:20px">
                    <h4 style="margin-bottom:16px">Customer & Delivery Information</h4>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px">
                        <div>
                            <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Name</strong>
                            <div>{{MC.currentOrder.name}}</div>
                        </div>
                        <div>
                            <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Email</strong>
                            <div>{{MC.currentOrder.email}}</div>
                        </div>
                        <div>
                            <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Phone</strong>
                            <div>{{MC.currentOrder.phone}}</div>
                        </div>
                        <div>
                            <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">City</strong>
                            <div>{{MC.currentOrder.city}}</div>
                        </div>
                    </div>
                    <div style="margin-top:16px">
                        <strong style="color:var(--muted);font-size:13px;display:block;margin-bottom:4px">Delivery Address</strong>
                        <div>{{MC.currentOrder.address}}</div>
                    </div>
                </div>

                <hr />
                
                <h4 style="margin:20px 0 16px">Order Items</h4>
                <div ng-repeat="item in MC.currentOrder.items">
                    <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
                        <div style="flex:1">
                            <div style="font-weight:600">{{item.name}}</div>
                            <div style="color:var(--muted);font-size:13px">Quantity: {{item.qty}} × ₹{{item.price}}</div>
                        </div>
                        <div style="font-weight:600;color:var(--primary)">₹{{item.subtotal}}</div>
                    </div>
                </div>
                
                <div style="display:flex;justify-content:space-between;padding:20px 0;font-weight:700;font-size:20px;border-top:2px solid var(--border);margin-top:12px">
                    <span>Total Amount</span>
                    <span style="color:var(--primary)">₹{{MC.currentOrder.total}}</span>
                </div>

                <div style="background:var(--light-bg);padding:20px;border-radius:12px;margin-top:20px">
                    <p style="color:var(--muted);font-size:14px">Thank you for your order! If you have any questions, please contact our support.</p>
                </div>
            </div>
        </section>

    </main>

    <footer>
        <div class="container">
            <p style="font-weight:600;color:var(--dark);margin-bottom:8px">ShopMini - Your Trusted Shopping Partner</p>
            <p>Made with care - Demo E-Commerce Store with Full Database Integration (PHP + MySQL)</p>
        </div>
    </footer>

    <script>
        angular.module('shopApp', [])
            .controller('MainCtrl', ['$scope', '$timeout', '$window', '$http', function($scope, $timeout, $window, $http) {
                const MC = this;

                MC.products = [];
                MC.loadProducts = function() {
                    $http.get('api.php?action=get_products')
                        .then(function(response) {
                            if (response.data.success) {
                                MC.products = response.data.products;
                                MC.categories = Array.from(new Set(MC.products.map(p => p.category)));
                                MC.featured = [MC.products[3], MC.products[7], MC.products[1]].filter(p => p);
                            }
                        })
                        .catch(function(error) {
                            console.error('Error loading products:', error);
                            alert('Error loading products from database');
                        });
                };
                
                MC.loadProducts();
                MC.categories = [];
                MC.featured = [];

                MC.view = 'home';
                MC.currentProduct = {};
                MC.detailQty = 1;

                MC.cart = JSON.parse(localStorage.getItem('mc_cart') || '[]');
                MC.cart = MC.cart.map(it => ({
                    product: MC.products.find(p => p.id === it.productId) || it.product,
                    qty: it.qty
                }));

                MC.saveCart = function() {
                    const toSave = MC.cart.map(it => ({
                        productId: it.product.id,
                        qty: it.qty
                    }));
                    localStorage.setItem('mc_cart', JSON.stringify(toSave));
                    
                    $http.post('api.php?action=save_cart', { cart: toSave })
                        .catch(function(error) {
                            console.error('Error saving cart:', error);
                        });
                }

                MC.cartTotalCount = function() {
                    return MC.cart.reduce((s, i) => s + i.qty, 0);
                }
                
                MC.cartSubtotal = function(cartList) {
                    cartList = cartList || MC.cart;
                    return cartList.reduce((s, i) => s + (i.product.price * i.qty), 0);
                }

                MC.addToCart = function(product, qty) {
                    qty = parseInt(qty) || 1;
                    const found = MC.cart.find(it => it.product.id === product.id);
                    if (found) {
                        found.qty += qty;
                    } else MC.cart.push({
                        product: product,
                        qty: qty
                    });
                    MC.saveCart();
                    alert(product.name + ' added to cart');
                }

                MC.removeFromCart = function(item) {
                    MC.cart = MC.cart.filter(i => i !== item);
                    MC.saveCart();
                }

                MC.updateCart = function() {
                    MC.saveCart();
                }

                MC.selectedCategory = null;
                MC.searchText = '';
                MC.sortKey = '';

                MC.filterByCat = function(c) {
                    MC.selectedCategory = c;
                }

                MC.onSearch = function() { }

                MC.searchFilter = function(p) {
                    if (MC.selectedCategory && p.category !== MC.selectedCategory) return false;
                    if (!MC.searchText) return true;
                    const t = MC.searchText.toLowerCase();
                    return p.name.toLowerCase().includes(t) || (p.category || '').toLowerCase().includes(t) || (p.description || '').toLowerCase().includes(t);
                }

                MC.viewProduct = function(p) {
                    MC.currentProduct = p;
                    MC.detailQty = 1;
                    MC.go('product');
                }

                MC.go = function(view) {
                    MC.view = view;
                    window.location.hash = '#/' + view;
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }

                function handleHash() {
                    const h = window.location.hash.replace('#/', '') || 'home';
                    if (h.startsWith('product-')) {
                        const id = parseInt(h.split('-')[1]);
                        const p = MC.products.find(x => x.id === id);
                        if (p) MC.viewProduct(p);
                        else MC.view = 'products';
                        $scope.$applyAsync();
                        return;
                    }
                    MC.view = (['home', 'products', 'cart', 'product', 'checkout', 'payment', 'receipt', 'my-orders', 'order-detail'].includes(h) ? h : 'home');
                    
                    // Load orders when viewing my-orders page
                    if (h === 'my-orders' && MC.userEmail) {
                        MC.loadUserOrders();
                    }
                    
                    $scope.$applyAsync();
                }
                window.addEventListener('hashchange', handleHash);
                handleHash();

                MC.buyNow = function(product, qty) {
                    MC.addToCart(product, qty);
                    MC.go('checkout');
                }

                MC.order = {};
                MC.payment = {};
                MC.cartAtPurchase = [];

                MC.placeOrder = function() {
                    if (MC.cart.length == 0) {
                        alert('Cart is empty');
                        MC.go('products');
                        return;
                    }
                    
                    MC.cartAtPurchase = MC.cart.map(i => ({
                        product: i.product,
                        qty: i.qty
                    }));

                    const orderData = {
                        order: MC.order,
                        items: MC.cart.map(item => ({
                            id: item.product.id,
                            name: item.product.name,
                            price: item.product.price,
                            qty: item.qty
                        }))
                    };

                    $http.post('api.php?action=place_order', orderData)
                        .then(function(response) {
                            if (response.data.success) {
                                MC.order.id = response.data.order_id;
                                MC.order.date = new Date().toISOString();
                                MC.go('payment');
                            } else {
                                alert('Error placing order: ' + response.data.message);
                            }
                        })
                        .catch(function(error) {
                            console.error('Error placing order:', error);
                            alert('Error placing order. Please try again.');
                        });
                }

                MC.pay = function() {
                    if (!MC.payment.card || !MC.payment.exp || !MC.payment.cvv) {
                        alert('Complete payment details');
                        return;
                    }

                    $http.post('api.php?action=complete_payment', { order_id: MC.order.id })
                        .then(function(response) {
                            if (response.data.success) {
                                localStorage.removeItem('mc_cart');
                                MC.cart = [];
                                MC.saveCart();
                                MC.go('receipt');
                            } else {
                                alert('Payment failed: ' + response.data.message);
                            }
                        })
                        .catch(function(error) {
                            console.error('Payment error:', error);
                            alert('Payment processing error');
                        });
                }

                MC.carouselIndex = 0;
                MC.startCarousel = function() {
                    function tick() {
                        if (MC.featured.length > 0) {
                            MC.carouselIndex = (MC.carouselIndex + 1) % MC.featured.length;
                            $scope.$applyAsync();
                        }
                        MC._carouselTimer = $timeout(tick, 4000);
                    }
                    if (MC._carouselTimer) $timeout.cancel(MC._carouselTimer);
                    MC._carouselTimer = $timeout(tick, 4000);
                }
                MC.nextSlide = function() {
                    if (MC.featured.length > 0) {
                        MC.carouselIndex = (MC.carouselIndex + 1) % MC.featured.length;
                    }
                }
                MC.prevSlide = function() {
                    if (MC.featured.length > 0) {
                        MC.carouselIndex = (MC.carouselIndex - 1 + MC.featured.length) % MC.featured.length;
                    }
                }

                MC.cartCount = function() {
                    return MC.cart.length;
                }

                window.MC = MC;

            }]);
    </script>
</body>
</html>