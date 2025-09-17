<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استوديو التصميم - Qaads</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --brand-yellow: #ffc107;
            --brand-yellow-light: #ffde9f;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #4a3535;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: #f8f9fa;
            height: 100vh;
            overflow: hidden;
        }

        .studio-container {
            display: grid;
            grid-template-columns: 250px 1fr 250px;
            grid-template-rows: 60px 1fr 60px;
            height: 100vh;
            gap: 1px;
            background: #dee2e6;
        }

        /* Header */
        .studio-header {
            grid-column: 1 / -1;
            background: var(--brand-brown);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .header-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .header-btn.primary {
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }

        .header-btn.primary:hover {
            background: var(--brand-yellow-light);
        }

        /* Left Sidebar - Tools */
        .left-sidebar {
            background: white;
            padding: 1rem;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--brand-yellow);
        }

        .tools-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .tool-btn {
            background: white;
            border: 2px solid #e9ecef;
            padding: 1rem 0.5rem;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .tool-btn:hover {
            border-color: var(--brand-yellow);
            background: var(--brand-yellow-light);
        }

        .tool-btn.active {
            border-color: var(--brand-yellow);
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }

        .tool-btn i {
            font-size: 1.2rem;
        }

        .tool-btn span {
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Main Canvas Area */
        .canvas-area {
            background: white;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .design-canvas {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }

        .canvas-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .design-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .design-element {
            position: absolute;
            cursor: move;
            pointer-events: auto;
        }

        .design-element.selected {
            border: 2px dashed var(--brand-yellow);
        }

        .text-element {
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            color: #000;
            user-select: none;
        }

        .logo-element {
            max-width: 100px;
            max-height: 100px;
        }

        .symbol-element {
            font-size: 2rem;
            color: var(--brand-yellow);
        }

        /* Right Sidebar - Properties */
        .right-sidebar {
            background: white;
            padding: 1rem;
            overflow-y: auto;
        }

        .properties-section {
            margin-bottom: 1.5rem;
        }

        .property-group {
            margin-bottom: 1rem;
        }

        .property-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--brand-brown);
            margin-bottom: 0.5rem;
        }

        .property-input {
            width: 100%;
            padding: 0.5rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .property-input:focus {
            border-color: var(--brand-yellow);
        }

        .color-palette {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }

        .color-item {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .symbols-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }

        .symbol-item {
            padding: 0.5rem;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .symbol-item:hover {
            border-color: var(--brand-yellow);
            background: var(--brand-yellow-light);
        }

        /* Footer */
        .studio-footer {
            grid-column: 1 / -1;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            padding: 0 1rem;
            border-top: 1px solid #e9ecef;
        }

        .footer-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-favorite {
            background: #f8f9fa;
            color: var(--brand-brown);
            border: 2px solid #e9ecef;
        }

        .btn-favorite:hover {
            background: #e9ecef;
            border-color: var(--brand-yellow);
        }

        .btn-cart {
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }

        .btn-cart:hover {
            background: var(--brand-yellow-light);
            transform: translateY(-2px);
        }

        /* Hidden File Input */
        .logo-input {
            display: none;
        }

        /* Mobile Responsive Design */
        @media (max-width: 768px) {
            .studio-container {
                grid-template-columns: 1fr;
                grid-template-rows: 60px 1fr 80px;
                height: 100vh;
            }
            
            /* Header adjustments */
            .studio-header {
                padding: 0 0.5rem;
            }
            
            .studio-header h1 {
                font-size: 1.2rem;
            }
            
            .header-right {
                gap: 0.5rem;
            }
            
            .header-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .header-btn i {
                font-size: 0.8rem;
            }
            
            /* Show mobile header toggles */
            .mobile-header-toggle {
                display: inline-flex !important;
            }
            
            /* Sidebar mobile behavior */
            .left-sidebar,
            .right-sidebar {
                position: fixed;
                top: 60px;
                width: 100%;
                height: calc(100vh - 140px);
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                background: white;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                overflow-y: auto;
            }
            
            .right-sidebar {
                transform: translateX(100%);
            }
            
            .left-sidebar.open,
            .right-sidebar.open {
                transform: translateX(0);
            }
            
            /* Mobile sidebar toggles */
            .mobile-sidebar-toggle {
                display: block !important;
                position: fixed;
                top: 70px;
                z-index: 1001;
                background: var(--brand-yellow);
                color: var(--brand-brown);
                border: none;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .mobile-sidebar-toggle:hover {
                background: var(--brand-yellow-light);
                transform: scale(1.1);
            }
            
            .mobile-sidebar-toggle.left {
                left: 10px;
            }
            
            .mobile-sidebar-toggle.right {
                right: 10px;
            }
            
            .mobile-sidebar-toggle.active {
                background: var(--brand-brown);
                color: white;
            }
            
            /* Canvas area mobile */
            .canvas-area {
                padding: 0.5rem;
            }
            
            .design-canvas {
                max-width: 100%;
                max-height: 100%;
                border-radius: 8px;
            }
            
            /* Tools grid mobile */
            .tools-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .tool-btn {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }
            
            .tool-btn i {
                font-size: 1rem;
            }
            
            /* Properties mobile */
            .properties-section {
                margin-bottom: 1rem;
            }
            
            .property-group {
                margin-bottom: 0.75rem;
            }
            
            .color-palette {
                grid-template-columns: repeat(6, 1fr);
                gap: 0.4rem;
            }
            
            .color-item {
                width: 25px;
                height: 25px;
            }
            
            .symbols-grid {
                grid-template-columns: repeat(6, 1fr);
                gap: 0.4rem;
            }
            
            .symbol-item {
                padding: 0.4rem;
                font-size: 1rem;
            }
            
            /* Footer mobile */
            .studio-footer {
                padding: 0 0.5rem;
                gap: 0.5rem;
            }
            
            .footer-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
                flex: 1;
            }
            
            /* Design elements mobile */
            .design-element {
                min-width: 30px;
                min-height: 30px;
            }
            
            .text-element {
                font-size: 16px !important;
                min-width: 50px;
            }
            
            .logo-element {
                max-width: 80px;
                max-height: 80px;
            }
            
            .symbol-element {
                font-size: 1.5rem;
            }
        }
        
        /* Mobile sidebar toggles - hidden by default */
        .mobile-sidebar-toggle {
            display: none;
        }
        
        /* Mobile bottom toolbar */
        .mobile-toolbar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 2px solid var(--brand-yellow);
            padding: 1rem;
            z-index: 1000;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
        }
        
        /* Mobile navigation system */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 2px solid var(--brand-yellow);
            z-index: 1000;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
        }
        
        .mobile-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .mobile-nav-title {
            font-weight: 600;
            color: var(--brand-brown);
            font-size: 1.1rem;
        }
        
        .mobile-nav-back {
            background: var(--brand-yellow);
            color: var(--brand-brown);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        
        .mobile-nav-content {
            padding: 1rem;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .mobile-toolbar-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .mobile-toolbar-row:last-child {
            margin-bottom: 0;
        }
        
        .mobile-tool-btn {
            flex: 1;
            margin: 0 0.25rem;
            padding: 0.75rem 0.5rem;
            background: var(--brand-yellow);
            color: var(--brand-brown);
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }
        
        .mobile-tool-btn:hover {
            background: var(--brand-yellow-light);
            transform: translateY(-2px);
        }
        
        .mobile-tool-btn.active {
            background: var(--brand-brown);
            color: white;
        }
        
        .mobile-tool-btn i {
            font-size: 1.2rem;
        }
        
        .mobile-property-panel {
            display: none;
            position: fixed;
            bottom: 80px;
            left: 0;
            right: 0;
            background: white;
            border-top: 2px solid var(--brand-brown);
            padding: 1rem;
            z-index: 999;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
            max-height: 200px;
            overflow-y: auto;
        }
        
        .mobile-property-panel.show {
            display: block;
        }
        
        .mobile-property-group {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .mobile-property-group label {
            font-weight: 600;
            color: var(--brand-brown);
            font-size: 0.9rem;
        }
        
        .mobile-input {
            padding: 0.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.9rem;
            background: white;
        }
        
        .mobile-input:focus {
            border-color: var(--brand-yellow);
            outline: none;
        }
        
        .mobile-property-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .mobile-btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .mobile-btn-primary {
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }
        
        .mobile-btn-secondary {
            background: #f0f0f0;
            color: #666;
        }
        
        .mobile-btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .mobile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .mobile-settings-group {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .mobile-settings-group:last-child {
            border-bottom: none;
        }
        
        .mobile-settings-group h4 {
            color: var(--brand-brown);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .mobile-settings-group p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .mobile-settings-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .mobile-element-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }
        
        .mobile-element-item span {
            font-size: 0.9rem;
            color: var(--brand-brown);
        }
        
        /* Ensure mobile toggles are visible on mobile */
        @media (max-width: 768px) {
            .mobile-sidebar-toggle {
                display: block !important;
            }
            
            .mobile-toolbar {
                display: block;
            }
            
            .left-sidebar,
            .right-sidebar {
                display: none !important;
            }
            
            .studio-container {
                grid-template-columns: 1fr;
                padding-bottom: 120px;
            }
        }
        
        /* Touch-friendly interactions */
        @media (hover: none) and (pointer: coarse) {
            .tool-btn:hover,
            .header-btn:hover,
            .footer-btn:hover {
                transform: none;
            }
            
            .tool-btn:active,
            .header-btn:active,
            .footer-btn:active {
                transform: scale(0.95);
            }
            
            .design-element {
                touch-action: none;
            }
        }
        
        /* Very small screens */
        @media (max-width: 480px) {
            .studio-header h1 {
                font-size: 1rem;
            }
            
            .header-btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }
            
            .tools-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .tool-btn {
                padding: 0.6rem;
                justify-content: flex-start;
                gap: 0.5rem;
            }
            
            .color-palette {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .symbols-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .footer-btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="studio-container">
        <!-- Header -->
        <div class="studio-header">
            <div class="header-left">
                <h1><i class="fas fa-paint-brush me-2"></i>استوديو التصميم</h1>
            </div>
            <div class="header-right">
                <!-- Mobile sidebar toggles in header -->
                <button class="header-btn mobile-header-toggle" onclick="toggleLeftSidebar()" style="display: none;">
                    <i class="fas fa-tools"></i>
                </button>
                <button class="header-btn mobile-header-toggle" onclick="toggleRightSidebar()" style="display: none;">
                    <i class="fas fa-cog"></i>
                </button>
                
                <button class="header-btn" onclick="downloadDesign()">
                    <i class="fas fa-download me-1"></i>تحميل
                </button>
                <button class="header-btn" onclick="window.location.href='{{ route('design.search') }}'">
                    <i class="fas fa-arrow-right me-1"></i>العودة للبحث
                </button>
                <button class="header-btn primary" onclick="saveDesign()">
                    <i class="fas fa-save me-1"></i>حفظ
                </button>
            </div>
        </div>

        <!-- Left Sidebar - Tools -->
        <div class="left-sidebar">
            <h3 class="sidebar-title">
                <i class="fas fa-tools me-2"></i>الأدوات
            </h3>
            <div class="tools-grid">
                <div class="tool-btn active" data-tool="text" onclick="selectTool('text')">
                    <i class="fas fa-font"></i>
                    <span>نص</span>
                </div>
                <div class="tool-btn" data-tool="logo" onclick="selectTool('logo')">
                    <i class="fas fa-image"></i>
                    <span>شعار</span>
                </div>
                <div class="tool-btn" data-tool="symbols" onclick="selectTool('symbols')">
                    <i class="fas fa-icons"></i>
                    <span>رموز</span>
                </div>
                <div class="tool-btn" data-tool="select" onclick="selectTool('select')">
                    <i class="fas fa-mouse-pointer"></i>
                    <span>تحديد</span>
                </div>
            </div>
            
            <!-- Logo Upload -->
            <input type="file" class="logo-input" id="logoInput" accept="image/*" onchange="addLogo(event)">
        </div>

        <!-- Main Canvas Area -->
        <div class="canvas-area">
            <!-- Mobile Sidebar Toggle Buttons -->
            <button class="mobile-sidebar-toggle left" onclick="toggleLeftSidebar()">
                <i class="fas fa-tools"></i>
            </button>
            <button class="mobile-sidebar-toggle right" onclick="toggleRightSidebar()">
                <i class="fas fa-cog"></i>
            </button>
            
            <div class="design-canvas" id="designCanvas" onclick="handleCanvasClick(event)">
                @if($imageUrl)
                    <img src="{{ route('image.proxy', ['url' => base64_encode($imageUrl)]) }}" alt="Design" class="canvas-image" id="canvasImage">
                @else
                    <div style="width: 500px; height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                        <div style="text-align: center;">
                            <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <p>لم يتم تحديد تصميم أساسي</p>
                        </div>
                    </div>
                @endif
                <div class="design-overlay" id="designOverlay"></div>
            </div>
        </div>

        <!-- Right Sidebar - Properties -->
        <div class="right-sidebar">
            <h3 class="sidebar-title">
                <i class="fas fa-cog me-2"></i>الخصائص
            </h3>
            
            <!-- Text Properties -->
            <div class="properties-section" id="textProperties" style="display: none;">
                <div class="property-group">
                    <label class="property-label">النص</label>
                    <input type="text" class="property-input" id="textContent" placeholder="اكتب النص هنا..." onchange="updateSelectedElement()">
                </div>
                <div class="property-group">
                    <label class="property-label">حجم الخط</label>
                    <input type="range" class="property-input" id="fontSize" min="12" max="72" value="24" onchange="updateSelectedElement()">
                </div>
                <div class="property-group">
                    <label class="property-label">الألوان</label>
                    <div class="color-palette">
                        <div class="color-item" style="background: #000000;" onclick="setTextColor('#000000')"></div>
                        <div class="color-item" style="background: #ffffff; border: 2px solid #ddd;" onclick="setTextColor('#ffffff')"></div>
                        <div class="color-item" style="background: var(--brand-yellow);" onclick="setTextColor('#ffc107')"></div>
                        <div class="color-item" style="background: var(--brand-brown);" onclick="setTextColor('#2a1e1e')"></div>
                        <div class="color-item" style="background: #dc3545;" onclick="setTextColor('#dc3545')"></div>
                        <div class="color-item" style="background: #28a745;" onclick="setTextColor('#28a745')"></div>
                        <div class="color-item" style="background: #007bff;" onclick="setTextColor('#007bff')"></div>
                        <div class="color-item" style="background: #6f42c1;" onclick="setTextColor('#6f42c1')"></div>
                    </div>
                </div>
            </div>

            <!-- Symbols Properties -->
            <div class="properties-section" id="symbolsProperties" style="display: none;">
                <div class="property-group">
                    <label class="property-label">الرموز</label>
                    <div class="symbols-grid">
                        <div class="symbol-item" onclick="addSymbol('★')">★</div>
                        <div class="symbol-item" onclick="addSymbol('♥')">♥</div>
                        <div class="symbol-item" onclick="addSymbol('♦')">♦</div>
                        <div class="symbol-item" onclick="addSymbol('♠')">♠</div>
                        <div class="symbol-item" onclick="addSymbol('☀')">☀</div>
                        <div class="symbol-item" onclick="addSymbol('☁')">☁</div>
                        <div class="symbol-item" onclick="addSymbol('⚡')">⚡</div>
                        <div class="symbol-item" onclick="addSymbol('✓')">✓</div>
                        <div class="symbol-item" onclick="addSymbol('✗')">✗</div>
                        <div class="symbol-item" onclick="addSymbol('♪')">♪</div>
                        <div class="symbol-item" onclick="addSymbol('☮')">☮</div>
                        <div class="symbol-item" onclick="addSymbol('♻')">♻</div>
                    </div>
                </div>
            </div>

            <!-- Logo Properties -->
            <div class="properties-section" id="logoProperties" style="display: none;">
                <div class="property-group">
                    <button class="property-input" onclick="document.getElementById('logoInput').click()" style="cursor: pointer; text-align: center;">
                        <i class="fas fa-upload me-2"></i>رفع شعار
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="studio-footer">
            @auth
                <button class="footer-btn btn-favorite" onclick="addToFavorites()">
                    <i class="fas fa-heart"></i>
                    إضافة للمفضلة
                </button>
            @endauth
            <button class="footer-btn btn-cart" onclick="saveToCart()">
                <i class="fas fa-shopping-cart"></i>
                حفظ في السلة
            </button>
        </div>

        <!-- Mobile Bottom Toolbar -->
        <div class="mobile-toolbar">
            <div class="mobile-toolbar-row">
                <button class="mobile-tool-btn" onclick="showMobileToolSettings('text')" data-tool="text">
                    <i class="fas fa-font"></i>
                    <span>نص</span>
                </button>
                <button class="mobile-tool-btn" onclick="showMobileToolSettings('logo')" data-tool="logo">
                    <i class="fas fa-image"></i>
                    <span>شعار</span>
                </button>
                <button class="mobile-tool-btn" onclick="showMobileToolSettings('symbol')" data-tool="symbol">
                    <i class="fas fa-star"></i>
                    <span>رمز</span>
                </button>
                <button class="mobile-tool-btn" onclick="showMobileToolSettings('select')" data-tool="select">
                    <i class="fas fa-mouse-pointer"></i>
                    <span>تحديد</span>
                </button>
            </div>
            <div class="mobile-toolbar-row">
                <button class="mobile-tool-btn" onclick="showMobileProperties()">
                    <i class="fas fa-cog"></i>
                    <span>خصائص</span>
                </button>
                <button class="mobile-tool-btn" onclick="downloadDesign()">
                    <i class="fas fa-download"></i>
                    <span>تحميل</span>
                </button>
                <button class="mobile-tool-btn" onclick="saveDesign()">
                    <i class="fas fa-save"></i>
                    <span>حفظ</span>
                </button>
                <button class="mobile-tool-btn" onclick="window.location.href='{{ route('design.search') }}'">
                    <i class="fas fa-arrow-right"></i>
                    <span>رجوع</span>
                </button>
            </div>
        </div>

        <!-- Mobile Property Panel -->
        <div class="mobile-property-panel" id="mobilePropertyPanel">
            <div id="mobilePropertyContent">
                <!-- Properties will be dynamically loaded here -->
            </div>
        </div>

        <!-- Mobile Navigation System -->
        <div class="mobile-nav" id="mobileNav">
            <div class="mobile-nav-header">
                <div class="mobile-nav-title" id="mobileNavTitle">الأدوات</div>
                <button class="mobile-nav-back" onclick="closeMobileNav()">رجوع</button>
            </div>
            <div class="mobile-nav-content" id="mobileNavContent">
                <!-- Content will be dynamically loaded here -->
            </div>
        </div>
    </div>

    <script>
        let currentTool = 'text';
        let selectedElement = null;
        let elements = [];
        let elementCounter = 0;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            selectTool('text');
            selectMobileTool('text');
            
            // Don't add test elements automatically
        });

        // Mobile tool selection
        function selectMobileTool(tool) {
            currentTool = tool;
            
            // Update mobile tool buttons
            document.querySelectorAll('.mobile-tool-btn[data-tool]').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.mobile-tool-btn[data-tool="${tool}"]`).classList.add('active');
            
            // Update desktop tool buttons
            document.querySelectorAll('.tool-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.tool-btn[data-tool="${tool}"]`).classList.add('active');
            
            // Handle tool-specific actions
            switch(tool) {
                case 'text':
                    // Don't auto-add text, just set tool
                    break;
                case 'logo':
                    document.getElementById('logoInput').click();
                    break;
                case 'symbol':
                    // Don't auto-add symbol, just set tool
                    break;
                case 'select':
                    // Selection mode - no immediate action
                    break;
            }
        }

        // Show mobile tool settings
        function showMobileToolSettings(tool) {
            const nav = document.getElementById('mobileNav');
            const title = document.getElementById('mobileNavTitle');
            const content = document.getElementById('mobileNavContent');
            
            // Set tool as current
            currentTool = tool;
            
            // Update active button
            document.querySelectorAll('.mobile-tool-btn[data-tool]').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.mobile-tool-btn[data-tool="${tool}"]`).classList.add('active');
            
            // Show appropriate settings
            switch(tool) {
                case 'text':
                    title.textContent = 'إعدادات النص';
                    content.innerHTML = generateTextSettingsHTML();
                    break;
                case 'logo':
                    title.textContent = 'إعدادات الشعار';
                    content.innerHTML = generateLogoSettingsHTML();
                    break;
                case 'symbol':
                    title.textContent = 'إعدادات الرموز';
                    content.innerHTML = generateSymbolSettingsHTML();
                    break;
                case 'select':
                    title.textContent = 'وضع التحديد';
                    content.innerHTML = generateSelectSettingsHTML();
                    break;
            }
            
            nav.style.display = 'block';
        }

        // Close mobile navigation
        function closeMobileNav() {
            const nav = document.getElementById('mobileNav');
            nav.style.display = 'none';
        }

        // Generate text settings HTML
        function generateTextSettingsHTML() {
            return `
                <div class="mobile-settings-group">
                    <h4>إضافة نص جديد</h4>
                    <p>اضغط على الشاشة في أي مكان لإضافة نص جديد</p>
                    <div class="mobile-settings-actions">
                        <button class="mobile-btn mobile-btn-primary" onclick="selectMobileTool('text'); closeMobileNav();">
                            <i class="fas fa-plus"></i> إضافة نص
                        </button>
                    </div>
                </div>
                <div class="mobile-settings-group">
                    <h4>النصوص المضافة</h4>
                    <div id="textElementsList">
                        ${generateElementsList('text')}
                    </div>
                </div>
            `;
        }

        // Generate logo settings HTML
        function generateLogoSettingsHTML() {
            return `
                <div class="mobile-settings-group">
                    <h4>رفع شعار جديد</h4>
                    <p>اضغط لاختيار صورة الشعار من جهازك</p>
                    <div class="mobile-settings-actions">
                        <button class="mobile-btn mobile-btn-primary" onclick="document.getElementById('logoInput').click(); closeMobileNav();">
                            <i class="fas fa-upload"></i> رفع شعار
                        </button>
                    </div>
                </div>
                <div class="mobile-settings-group">
                    <h4>الشعارات المضافة</h4>
                    <div id="logoElementsList">
                        ${generateElementsList('logo')}
                    </div>
                </div>
            `;
        }

        // Generate symbol settings HTML
        function generateSymbolSettingsHTML() {
            return `
                <div class="mobile-settings-group">
                    <h4>إضافة رمز جديد</h4>
                    <p>اضغط على الشاشة في أي مكان لإضافة رمز جديد</p>
                    <div class="mobile-settings-actions">
                        <button class="mobile-btn mobile-btn-primary" onclick="selectMobileTool('symbol'); closeMobileNav();">
                            <i class="fas fa-plus"></i> إضافة رمز
                        </button>
                    </div>
                </div>
                <div class="mobile-settings-group">
                    <h4>الرموز المضافة</h4>
                    <div id="symbolElementsList">
                        ${generateElementsList('symbol')}
                    </div>
                </div>
            `;
        }

        // Generate select settings HTML
        function generateSelectSettingsHTML() {
            return `
                <div class="mobile-settings-group">
                    <h4>وضع التحديد</h4>
                    <p>اضغط على العناصر لتحديدها وتعديلها</p>
                    <div class="mobile-settings-actions">
                        <button class="mobile-btn mobile-btn-primary" onclick="selectMobileTool('select'); closeMobileNav();">
                            <i class="fas fa-mouse-pointer"></i> تفعيل التحديد
                        </button>
                    </div>
                </div>
                <div class="mobile-settings-group">
                    <h4>جميع العناصر</h4>
                    <div id="allElementsList">
                        ${generateAllElementsList()}
                    </div>
                </div>
            `;
        }

        // Generate elements list for specific type
        function generateElementsList(type) {
            const typeElements = elements.filter(el => el.type === type);
            if (typeElements.length === 0) {
                return '<p>لا توجد عناصر من هذا النوع</p>';
            }
            
            return typeElements.map(element => `
                <div class="mobile-element-item">
                    <span>${element.content || 'عنصر'}</span>
                    <button class="mobile-btn mobile-btn-danger" onclick="deleteElement('${element.id}'); closeMobileNav();">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).join('');
        }

        // Generate all elements list
        function generateAllElementsList() {
            if (elements.length === 0) {
                return '<p>لا توجد عناصر</p>';
            }
            
            return elements.map(element => `
                <div class="mobile-element-item">
                    <span>${element.type === 'text' ? 'نص' : element.type === 'symbol' ? 'رمز' : 'شعار'}: ${element.content || 'عنصر'}</span>
                    <button class="mobile-btn mobile-btn-danger" onclick="deleteElement('${element.id}'); closeMobileNav();">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).join('');
        }

        // Show mobile properties panel
        function showMobileProperties() {
            const panel = document.getElementById('mobilePropertyPanel');
            const content = document.getElementById('mobilePropertyContent');
            
            if (selectedElement) {
                // Show properties for selected element
                content.innerHTML = generateMobilePropertiesHTML(selectedElement);
            } else {
                // Show general properties
                content.innerHTML = generateGeneralMobilePropertiesHTML();
            }
            
            panel.classList.toggle('show');
        }

        // Generate mobile properties HTML for selected element
        function generateMobilePropertiesHTML(element) {
            let html = `<h4>خصائص العنصر</h4>`;
            
            switch(element.type) {
                case 'text':
                    html += `
                        <div class="mobile-property-group">
                            <label>النص:</label>
                            <input type="text" value="${element.content}" onchange="updateElementProperty('content', this.value)" class="mobile-input">
                        </div>
                        <div class="mobile-property-group">
                            <label>اللون:</label>
                            <input type="color" value="${element.color}" onchange="updateElementProperty('color', this.value)" class="mobile-input">
                        </div>
                        <div class="mobile-property-group">
                            <label>حجم الخط:</label>
                            <input type="range" min="12" max="72" value="${element.fontSize}" onchange="updateElementProperty('fontSize', this.value)" class="mobile-input">
                            <span>${element.fontSize}px</span>
                        </div>
                    `;
                    break;
                case 'symbol':
                    html += `
                        <div class="mobile-property-group">
                            <label>الرمز:</label>
                            <select onchange="updateElementProperty('content', this.value)" class="mobile-input">
                                <option value="★" ${element.content === '★' ? 'selected' : ''}>★</option>
                                <option value="❤" ${element.content === '❤' ? 'selected' : ''}>❤</option>
                                <option value="🔥" ${element.content === '🔥' ? 'selected' : ''}>🔥</option>
                                <option value="⭐" ${element.content === '⭐' ? 'selected' : ''}>⭐</option>
                            </select>
                        </div>
                        <div class="mobile-property-group">
                            <label>اللون:</label>
                            <input type="color" value="${element.color}" onchange="updateElementProperty('color', this.value)" class="mobile-input">
                        </div>
                        <div class="mobile-property-group">
                            <label>الحجم:</label>
                            <input type="range" min="20" max="100" value="${element.fontSize}" onchange="updateElementProperty('fontSize', this.value)" class="mobile-input">
                            <span>${element.fontSize}px</span>
                        </div>
                    `;
                    break;
                case 'logo':
                    html += `
                        <div class="mobile-property-group">
                            <label>العرض:</label>
                            <input type="range" min="50" max="300" value="${element.width}" onchange="updateElementProperty('width', this.value)" class="mobile-input">
                            <span>${element.width}px</span>
                        </div>
                        <div class="mobile-property-group">
                            <label>الارتفاع:</label>
                            <input type="range" min="50" max="300" value="${element.height}" onchange="updateElementProperty('height', this.value)" class="mobile-input">
                            <span>${element.height}px</span>
                        </div>
                    `;
                    break;
            }
            
            html += `
                <div class="mobile-property-group">
                    <label>الشفافية:</label>
                    <input type="range" min="0" max="1" step="0.1" value="${element.opacity || 1}" onchange="updateElementProperty('opacity', this.value)" class="mobile-input">
                    <span>${Math.round((element.opacity || 1) * 100)}%</span>
                </div>
                <div class="mobile-property-actions">
                    <button class="mobile-btn mobile-btn-danger" onclick="deleteElement('${element.id}')">حذف</button>
                    <button class="mobile-btn mobile-btn-secondary" onclick="document.getElementById('mobilePropertyPanel').classList.remove('show')">إغلاق</button>
                </div>
            `;
            
            return html;
        }

        // Generate general mobile properties HTML
        function generateGeneralMobilePropertiesHTML() {
            return `
                <h4>الخصائص العامة</h4>
                <div class="mobile-property-group">
                    <label>عدد العناصر:</label>
                    <span>${elements.length}</span>
                </div>
                <div class="mobile-property-actions">
                    <button class="mobile-btn mobile-btn-primary" onclick="clearAllElements()">مسح الكل</button>
                    <button class="mobile-btn mobile-btn-secondary" onclick="document.getElementById('mobilePropertyPanel').classList.remove('show')">إغلاق</button>
                </div>
            `;
        }

        // Update element property
        function updateElementProperty(property, value) {
            if (selectedElement) {
                selectedElement[property] = value;
                updateElementDisplay(selectedElement);
            }
        }

        // Delete element
        function deleteElement(elementId) {
            elements = elements.filter(el => el.id !== elementId);
            const elementEl = document.getElementById(elementId);
            if (elementEl) {
                elementEl.remove();
            }
            selectedElement = null;
            document.getElementById('mobilePropertyPanel').classList.remove('show');
        }

        // Clear all elements
        function clearAllElements() {
            elements = [];
            document.getElementById('designOverlay').innerHTML = '';
            selectedElement = null;
            document.getElementById('mobilePropertyPanel').classList.remove('show');
        }

        // Handle canvas click to add elements
        function handleCanvasClick(event) {
            console.log('Canvas clicked, current tool:', currentTool);
            
            if (currentTool === 'select') {
                // In select mode, don't add elements
                return;
            }
            
            const canvas = document.getElementById('designCanvas');
            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            
            console.log('Click position:', x, y);
            
            switch(currentTool) {
                case 'text':
                    console.log('Adding text at position:', x, y);
                    addTextAtPosition(x, y);
                    break;
                case 'symbol':
                    console.log('Adding symbol at position:', x, y);
                    addSymbolAtPosition(x, y);
                    break;
                case 'logo':
                    // Logo is handled by file input
                    break;
            }
        }

        // Add text at specific position
        function addTextAtPosition(x, y) {
            const text = prompt('أدخل النص:', 'نص جديد');
            if (text && text.trim()) {
                addTextElement(text.trim(), x, y);
            }
        }

        // Add symbol at specific position
        function addSymbolAtPosition(x, y) {
            const symbols = ['★', '❤', '🔥', '⭐', '💎', '🎯', '🚀', '✨'];
            const symbol = prompt('اختر رمز (أو أدخل رمز مخصص):', symbols[0]);
            if (symbol && symbol.trim()) {
                addSymbolElement(symbol.trim(), x, y);
            }
        }

        // Add text element
        function addTextElement(content, x, y) {
            const element = {
                id: 'element_' + (++elementCounter),
                type: 'text',
                content: content,
                x: x,
                y: y,
                fontSize: 24,
                color: '#000000',
                opacity: 1,
                rotation: 0
            };
            
            elements.push(element);
            createElementElement(element);
            console.log('Text element added:', element);
        }

        // Add symbol element
        function addSymbolElement(content, x, y) {
            const element = {
                id: 'element_' + (++elementCounter),
                type: 'symbol',
                content: content,
                x: x,
                y: y,
                fontSize: 48,
                color: '#ffc107',
                opacity: 1,
                rotation: 0
            };
            
            elements.push(element);
            createElementElement(element);
        }

        // Mobile sidebar toggle functions
        function toggleLeftSidebar() {
            const leftSidebar = document.querySelector('.left-sidebar');
            const rightSidebar = document.querySelector('.right-sidebar');
            const leftToggle = document.querySelector('.mobile-sidebar-toggle.left');
            const rightToggle = document.querySelector('.mobile-sidebar-toggle.right');
            
            if (leftSidebar.classList.contains('open')) {
                leftSidebar.classList.remove('open');
                leftToggle.classList.remove('active');
            } else {
                leftSidebar.classList.add('open');
                rightSidebar.classList.remove('open');
                leftToggle.classList.add('active');
                rightToggle.classList.remove('active');
            }
        }

        function toggleRightSidebar() {
            const leftSidebar = document.querySelector('.left-sidebar');
            const rightSidebar = document.querySelector('.right-sidebar');
            const leftToggle = document.querySelector('.mobile-sidebar-toggle.left');
            const rightToggle = document.querySelector('.mobile-sidebar-toggle.right');
            
            if (rightSidebar.classList.contains('open')) {
                rightSidebar.classList.remove('open');
                rightToggle.classList.remove('active');
            } else {
                rightSidebar.classList.add('open');
                leftSidebar.classList.remove('open');
                rightToggle.classList.add('active');
                leftToggle.classList.remove('active');
            }
        }

        // Close sidebars when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const leftSidebar = document.querySelector('.left-sidebar');
                const rightSidebar = document.querySelector('.right-sidebar');
                const leftToggle = document.querySelector('.mobile-sidebar-toggle.left');
                const rightToggle = document.querySelector('.mobile-sidebar-toggle.right');
                
                // Check if click is outside left sidebar
                if (leftSidebar && leftSidebar.classList.contains('open')) {
                    if (!leftSidebar.contains(e.target) && !leftToggle.contains(e.target)) {
                        leftSidebar.classList.remove('open');
                        leftToggle.classList.remove('active');
                    }
                }
                
                // Check if click is outside right sidebar
                if (rightSidebar && rightSidebar.classList.contains('open')) {
                    if (!rightSidebar.contains(e.target) && !rightToggle.contains(e.target)) {
                        rightSidebar.classList.remove('open');
                        rightToggle.classList.remove('active');
                    }
                }
            }
        });

        function selectTool(tool) {
            currentTool = tool;
            
            // Update tool buttons
            document.querySelectorAll('.tool-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-tool="${tool}"]`).classList.add('active');

            // Show/hide properties
            document.querySelectorAll('.properties-section').forEach(section => {
                section.style.display = 'none';
            });
            
            if (tool === 'text') {
                document.getElementById('textProperties').style.display = 'block';
            } else if (tool === 'symbols') {
                document.getElementById('symbolsProperties').style.display = 'block';
            } else if (tool === 'logo') {
                document.getElementById('logoProperties').style.display = 'block';
            }
        }

        function addText() {
            const text = document.getElementById('textContent').value || 'نص جديد';
            const fontSize = document.getElementById('fontSize').value || 24;
            
            const textElement = document.createElement('div');
            textElement.className = 'design-element text-element';
            textElement.id = 'element_' + (++elementCounter);
            textElement.style.left = '50px';
            textElement.style.top = '50px';
            textElement.style.fontSize = fontSize + 'px';
            textElement.style.color = '#000000';
            textElement.textContent = text;
            textElement.onclick = () => selectElement(textElement);
            
            document.getElementById('designOverlay').appendChild(textElement);
            
            elements.push({
                id: textElement.id,
                type: 'text',
                content: text,
                fontSize: fontSize,
                color: '#000000',
                x: 50,
                y: 50
            });
            
            selectElement(textElement);
        }

        function addSymbol(symbol) {
            const symbolElement = document.createElement('div');
            symbolElement.className = 'design-element symbol-element';
            symbolElement.id = 'element_' + (++elementCounter);
            symbolElement.style.left = '50px';
            symbolElement.style.top = '50px';
            symbolElement.textContent = symbol;
            symbolElement.onclick = () => selectElement(symbolElement);
            
            document.getElementById('designOverlay').appendChild(symbolElement);
            
            elements.push({
                id: symbolElement.id,
                type: 'symbol',
                content: symbol,
                x: 50,
                y: 50
            });
            
            selectElement(symbolElement);
        }

        function addLogo(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const logoElement = document.createElement('img');
                logoElement.className = 'design-element logo-element';
                logoElement.id = 'element_' + (++elementCounter);
                logoElement.style.left = '50px';
                logoElement.style.top = '50px';
                logoElement.src = e.target.result;
                logoElement.onclick = () => selectElement(logoElement);
                
                document.getElementById('designOverlay').appendChild(logoElement);
                
                elements.push({
                    id: logoElement.id,
                    type: 'logo',
                    src: e.target.result,
                    x: 50,
                    y: 50
                });
                
                selectElement(logoElement);
            };
            reader.readAsDataURL(file);
        }

        function selectElement(element) {
            // Remove previous selection
            document.querySelectorAll('.design-element').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Select new element
            element.classList.add('selected');
            selectedElement = element;
            
            // Update properties based on element type
            if (element.classList.contains('text-element')) {
                selectTool('text');
                document.getElementById('textContent').value = element.textContent;
                document.getElementById('fontSize').value = parseInt(element.style.fontSize) || 24;
            }
        }

        function updateSelectedElement() {
            if (!selectedElement) return;
            
            if (selectedElement.classList.contains('text-element')) {
                const text = document.getElementById('textContent').value;
                const fontSize = document.getElementById('fontSize').value;
                
                selectedElement.textContent = text;
                selectedElement.style.fontSize = fontSize + 'px';
                
                // Update in elements array
                const elementData = elements.find(el => el.id === selectedElement.id);
                if (elementData) {
                    elementData.content = text;
                    elementData.fontSize = fontSize;
                }
            }
        }

        function setTextColor(color) {
            if (!selectedElement || !selectedElement.classList.contains('text-element')) return;
            
            selectedElement.style.color = color;
            
            // Update in elements array
            const elementData = elements.find(el => el.id === selectedElement.id);
            if (elementData) {
                elementData.color = color;
            }
        }

        // Add text on canvas click when text tool is selected
        document.getElementById('designOverlay').addEventListener('click', function(e) {
            if (currentTool === 'text' && e.target === this) {
                addText();
            }
        });

        // Make elements draggable (mouse and touch)
        let isDragging = false;
        let dragStart = { x: 0, y: 0 };

        function startDrag(e, target) {
            isDragging = true;
            selectedElement = target;
            selectElement(selectedElement);
            
            const rect = selectedElement.getBoundingClientRect();
            const clientX = e.clientX || (e.touches && e.touches[0].clientX);
            const clientY = e.clientY || (e.touches && e.touches[0].clientY);
            
            dragStart.x = clientX - rect.left;
            dragStart.y = clientY - rect.top;
            
            e.preventDefault();
        }

        function dragMove(e) {
            if (!isDragging || !selectedElement) return;
            
            const canvasRect = document.getElementById('designOverlay').getBoundingClientRect();
            const clientX = e.clientX || (e.touches && e.touches[0].clientX);
            const clientY = e.clientY || (e.touches && e.touches[0].clientY);
            
            const x = clientX - canvasRect.left - dragStart.x;
            const y = clientY - canvasRect.top - dragStart.y;
            
            selectedElement.style.left = Math.max(0, x) + 'px';
            selectedElement.style.top = Math.max(0, y) + 'px';
            
            // Update in elements array
            const elementData = elements.find(el => el.id === selectedElement.id);
            if (elementData) {
                elementData.x = x;
                elementData.y = y;
            }
            
            e.preventDefault();
        }

        function endDrag() {
            isDragging = false;
        }

        // Mouse events
        document.addEventListener('mousedown', function(e) {
            if (e.target.classList.contains('design-element')) {
                startDrag(e, e.target);
            }
        });

        document.addEventListener('mousemove', dragMove);
        document.addEventListener('mouseup', endDrag);

        // Touch events for mobile
        document.addEventListener('touchstart', function(e) {
            if (e.target.classList.contains('design-element')) {
                startDrag(e, e.target);
            }
        }, { passive: false });

        document.addEventListener('touchmove', dragMove, { passive: false });
        document.addEventListener('touchend', endDrag);

        function saveDesign() {
            const title = prompt('اسم التصميم:') || 'تصميمي';
            const imageUrl = generateDesignImage();
            
            fetch('{{ route("design.studio.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    design_data: {
                        elements: elements,
                        background_image: '{{ $imageUrl ?? "" }}'
                    },
                    image_url: imageUrl
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message || 'حدث خطأ أثناء الحفظ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الحفظ');
            });
        }

        function saveToCart() {
            const title = prompt('اسم التصميم:') || 'تصميمي';
            const imageUrl = generateDesignImage();
            
            fetch('{{ route("design.studio.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    design_data: {
                        elements: elements,
                        background_image: '{{ $imageUrl ?? "" }}'
                    },
                    image_url: imageUrl
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message || 'حدث خطأ أثناء الحفظ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الحفظ');
            });
        }

        @auth
        function addToFavorites() {
            const title = prompt('اسم التصميم:') || 'تصميمي';
            const imageUrl = generateDesignImage();
            
            fetch('{{ route("design.studio.add-to-favorites") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    design_data: {
                        elements: elements,
                        background_image: '{{ $imageUrl ?? "" }}'
                    },
                    image_url: imageUrl
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message || 'حدث خطأ أثناء إضافة للمفضلة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة للمفضلة');
            });
        }
        @endauth

        function generateDesignImage() {
            // This is a simplified version - in production you'd render the canvas to image
            return '{{ $imageUrl ?? "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" }}';
        }

        function downloadDesign() {
            try {
                // Create a canvas to render the final design
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Set canvas size to match the actual display size exactly
                const designCanvas = document.getElementById('designCanvas');
                const canvasRect = designCanvas.getBoundingClientRect();
                const canvasImage = document.getElementById('canvasImage');
                
                // Use the actual image dimensions if available, otherwise use display size
                if (canvasImage && canvasImage.naturalWidth > 0) {
                    canvas.width = canvasImage.naturalWidth;
                    canvas.height = canvasImage.naturalHeight;
                } else {
                    canvas.width = Math.max(800, canvasRect.width);
                    canvas.height = Math.max(600, canvasRect.height);
                }
                
                // canvasImage already declared above
                
                // Ensure image is loaded
                if (canvasImage && !canvasImage.complete) {
                    canvasImage.onload = function() {
                        processDownload(canvas, ctx, canvasImage);
                    };
                    canvasImage.onerror = function() {
                        processDownload(canvas, ctx, null);
                    };
                } else {
                    processDownload(canvas, ctx, canvasImage);
                }
                
            } catch (error) {
                console.error('Download error:', error);
                alert('حدث خطأ أثناء تحميل التصميم: ' + error.message);
            }
        }

        function processDownload(canvas, ctx, canvasImage) {
            try {
                // Get the actual canvas dimensions from the display
                const designCanvas = document.getElementById('designCanvas');
                const canvasRect = designCanvas.getBoundingClientRect();
                const canvasImage = document.getElementById('canvasImage');
                
                // Calculate scale factors based on actual image vs display
                let scaleX, scaleY;
                if (canvasImage && canvasImage.naturalWidth > 0) {
                    scaleX = canvasImage.naturalWidth / canvasRect.width;
                    scaleY = canvasImage.naturalHeight / canvasRect.height;
                } else {
                    scaleX = canvas.width / canvasRect.width;
                    scaleY = canvas.height / canvasRect.height;
                }
                
                if (canvasImage && canvasImage.src && canvasImage.complete && canvasImage.naturalWidth > 0) {
                    // Use data URL approach to avoid security warnings
                    try {
                        // Create a temporary canvas to convert image to data URL
                        const tempCanvas = document.createElement('canvas');
                        const tempCtx = tempCanvas.getContext('2d');
                        tempCanvas.width = canvasImage.naturalWidth;
                        tempCanvas.height = canvasImage.naturalHeight;
                        
                        // Draw image to temp canvas
                        tempCtx.drawImage(canvasImage, 0, 0);
                        
                        // Convert to data URL (this makes it completely secure)
                        const dataURL = tempCanvas.toDataURL('image/png');
                        
                        // Create image from data URL
                        const img = new Image();
                        img.onload = function() {
                            // Draw background image
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                            
                            // Draw all design elements with proper scaling
                            drawElementsScaled(ctx, scaleX, scaleY);
                            
                            // Download the final canvas
                            downloadCanvas(canvas);
                        };
                        img.onerror = function() {
                            console.log('Failed to load processed image, using gradient');
                            drawGradientBackground(ctx);
                            drawElementsScaled(ctx, scaleX, scaleY);
                            downloadCanvas(canvas);
                        };
                        img.src = dataURL;
                        
                    } catch (e) {
                        console.log('Could not process background image, using gradient:', e);
                        // Fallback to gradient background
                        drawGradientBackground(ctx);
                        drawElementsScaled(ctx, scaleX, scaleY);
                        downloadCanvas(canvas);
                    }
                } else {
                    console.log('No valid background image, using gradient');
                    // No background image, use gradient
                    drawGradientBackground(ctx);
                    drawElementsScaled(ctx, scaleX, scaleY);
                    downloadCanvas(canvas);
                }
            } catch (error) {
                console.error('Process download error:', error);
                alert('حدث خطأ أثناء تحميل التصميم: ' + error.message);
            }
        }

        function drawGradientBackground(ctx) {
            // Fill with white background
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, 800, 600);
            
            // Add a subtle gradient
            const gradient = ctx.createLinearGradient(0, 0, 800, 600);
            gradient.addColorStop(0, '#f8f9fa');
            gradient.addColorStop(1, '#e9ecef');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, 800, 600);
        }

        function drawElements(ctx) {
            // Draw all design elements
            elements.forEach(element => {
                ctx.save();
                ctx.globalAlpha = element.opacity || 1;
                ctx.translate(element.x, element.y);
                
                if (element.rotation) {
                    ctx.rotate(element.rotation * Math.PI / 180);
                }
                
                switch (element.type) {
                    case 'text':
                        ctx.fillStyle = element.color || '#000000';
                        ctx.font = `${element.fontSize || 24}px Arial, sans-serif`;
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'top';
                        ctx.fillText(element.content, 0, 0);
                        break;
                        
                    case 'symbol':
                        ctx.fillStyle = element.color || '#ffc107';
                        ctx.font = `${element.fontSize || 48}px Arial, sans-serif`;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(element.content, 25, 25);
                        break;
                        
                    case 'logo':
                        // Draw a styled placeholder for logos
                        const logoWidth = element.width || 100;
                        const logoHeight = element.height || 100;
                        
                        // Logo background
                        ctx.fillStyle = '#f0f0f0';
                        ctx.fillRect(0, 0, logoWidth, logoHeight);
                        
                        // Logo border
                        ctx.strokeStyle = '#ddd';
                        ctx.lineWidth = 2;
                        ctx.strokeRect(0, 0, logoWidth, logoHeight);
                        
                        // Logo text
                        ctx.fillStyle = '#666';
                        ctx.font = '12px Arial';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('LOGO', logoWidth / 2, logoHeight / 2);
                        break;
                }
                
                ctx.restore();
            });
            
            // No border to avoid weird lines
        }

        function drawElementsScaled(ctx, scaleX, scaleY) {
            // Draw all design elements with proper scaling
            elements.forEach(element => {
                ctx.save();
                ctx.globalAlpha = element.opacity || 1;
                
                // Scale the position and size
                const scaledX = element.x * scaleX;
                const scaledY = element.y * scaleY;
                ctx.translate(scaledX, scaledY);
                
                if (element.rotation) {
                    ctx.rotate(element.rotation * Math.PI / 180);
                }
                
                switch (element.type) {
                    case 'text':
                        ctx.fillStyle = element.color || '#000000';
                        const scaledFontSize = (element.fontSize || 24) * Math.min(scaleX, scaleY);
                        ctx.font = `${scaledFontSize}px Arial, sans-serif`;
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'top';
                        ctx.fillText(element.content, 0, 0);
                        break;
                        
                    case 'symbol':
                        ctx.fillStyle = element.color || '#ffc107';
                        const scaledSymbolSize = (element.fontSize || 48) * Math.min(scaleX, scaleY);
                        ctx.font = `${scaledSymbolSize}px Arial, sans-serif`;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(element.content, 25 * scaleX, 25 * scaleY);
                        break;
                        
                    case 'logo':
                        // Draw a styled placeholder for logos
                        const scaledLogoWidth = (element.width || 100) * scaleX;
                        const scaledLogoHeight = (element.height || 100) * scaleY;
                        
                        // Logo background
                        ctx.fillStyle = '#f0f0f0';
                        ctx.fillRect(0, 0, scaledLogoWidth, scaledLogoHeight);
                        
                        // Logo border
                        ctx.strokeStyle = '#ddd';
                        ctx.lineWidth = 2 * Math.min(scaleX, scaleY);
                        ctx.strokeRect(0, 0, scaledLogoWidth, scaledLogoHeight);
                        
                        // Logo text
                        ctx.fillStyle = '#666';
                        ctx.font = `${12 * Math.min(scaleX, scaleY)}px Arial`;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('LOGO', scaledLogoWidth / 2, scaledLogoHeight / 2);
                        break;
                }
                
                ctx.restore();
            });
            
            // No border to avoid weird lines
        }

        function downloadCanvas(canvas) {
            // Convert to blob and download
            canvas.toBlob(function(blob) {
                if (blob) {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.download = `design-${Date.now()}.png`;
                    link.href = url;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                    
                    // Show success message
                    alert('تم تحميل التصميم بنجاح!');
                } else {
                    alert('حدث خطأ أثناء تحميل التصميم');
                }
            }, 'image/png', 0.9);
        }
    </script>
</body>
</html>
