<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث عن التصاميم - Qaads</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --brand-yellow: #ffc107;
            --brand-yellow-light: #ffde9f;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #4a3535;
        }

        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .design-search-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 900;
            color: var(--brand-brown);
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .search-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            padding: 1rem 1.5rem;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 1.1rem;
            min-width: 300px;
        }

        .search-input:focus {
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }

        .search-btn {
            background: var(--brand-yellow);
            color: var(--brand-brown);
            border: none;
            padding: 1rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--brand-yellow-light);
            transform: translateY(-2px);
        }

        .designs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }

        .design-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .design-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .design-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .design-info {
            padding: 1.5rem;
        }

        .design-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--brand-brown);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .design-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .design-actions {
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-cart {
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }

        .btn-cart:hover {
            background: var(--brand-yellow-light);
            transform: translateY(-2px);
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

        .btn-edit {
            background: var(--brand-brown);
            color: white;
        }

        .btn-edit:hover {
            background: var(--brand-brown-light);
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .loading-state {
            display: none;
            text-align: center;
            padding: 4rem 2rem;
            color: var(--brand-brown);
        }

        .loading-state.active {
            display: block;
        }

        .navbar {
            background: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--brand-brown);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--brand-brown);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--brand-yellow);
        }

        @media (max-width: 768px) {
            .designs-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1rem;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-input {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">Qaads</div>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-home me-1"></i>الرئيسية
                </a>
                <a href="{{ route('cart.index') }}" class="nav-link">
                    <i class="fas fa-shopping-cart me-1"></i>السلة
                </a>
                <a href="{{ route('design.studio') }}" class="nav-link">
                    <i class="fas fa-paint-brush me-1"></i>استوديو التصميم
                </a>
            </div>
        </div>
    </nav>

    <div class="design-search-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-search me-3"></i>
                البحث عن التصاميم
            </h1>
            <p class="page-subtitle">اكتشف آلاف التصاميم المميزة من Freepik</p>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form class="search-form" method="GET" action="{{ route('design.search') }}">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="ابحث عن التصاميم... (مثال: قميص، شعار، تصميم)"
                    value="{{ $search }}"
                >
                <button type="submit" class="search-btn">
                    <i class="fas fa-search me-2"></i>بحث
                </button>
            </form>
        </div>

        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <i class="fas fa-spinner fa-spin"></i>
            <h3>جاري البحث...</h3>
            <p>يرجى الانتظار بينما نحضر لك أفضل التصاميم</p>
        </div>

        <!-- Designs Grid -->
        @if(count($designs) > 0)
            <div class="designs-grid">
                @foreach($designs as $design)
                    <div class="design-card" data-design-id="{{ $design['id'] }}">
                        <img 
                            src="{{ $design['thumbnail_url'] ?? $design['image_url'] }}" 
                            alt="{{ $design['title'] }}"
                            class="design-image"
                            loading="lazy"
                            onerror="this.style.display='none'"
                        >
                        <div class="design-info">
                            <h3 class="design-title">{{ Str::limit($design['title'], 60) }}</h3>
                            <div class="design-meta">
                                <span><i class="fas fa-tag me-1"></i>{{ $design['category'] ?? 'تصميم' }}</span>
                                <span><i class="fas fa-download me-1"></i>{{ $design['downloads'] ?? 0 }}</span>
                            </div>
                            <div class="design-actions">
                                <button 
                                    class="action-btn btn-cart"
                                    onclick="saveToCart('{{ $design['id'] }}', '{{ addslashes($design['title']) }}', '{{ $design['image_url'] }}')"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                    حفظ في السلة
                                </button>
                                @auth
                                    <button 
                                        class="action-btn btn-favorite"
                                        onclick="addToFavorites('{{ $design['id'] }}', '{{ addslashes($design['title']) }}', '{{ $design['image_url'] }}')"
                                    >
                                        <i class="fas fa-heart"></i>
                                        مفضلة
                                    </button>
                                @endauth
                                <button 
                                    class="action-btn btn-edit"
                                    onclick="editDesign('{{ $design['id'] }}', '{{ $design['image_url'] }}')"
                                >
                                    <i class="fas fa-edit"></i>
                                    تحرير
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($search || $category)
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>لم يتم العثور على تصاميم</h3>
                <p>جرب البحث بكلمات مختلفة أو تصفح جميع التصاميم</p>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-palette"></i>
                <h3>ابدأ البحث عن التصاميم</h3>
                <p>استخدم شريط البحث أعلاه للعثور على التصاميم المثالية لمشروعك</p>
            </div>
        @endif
    </div>

    <script>
        // CSRF token setup
        window.axios = window.axios || {};
        window.axios.defaults = window.axios.defaults || {};
        window.axios.defaults.headers = window.axios.defaults.headers || {};
        window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function saveToCart(designId, title, imageUrl) {
            fetch('{{ route("design.save-to-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    design_id: designId,
                    title: title,
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

        function addToFavorites(designId, title, imageUrl) {
            fetch('{{ route("design.add-to-favorites") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    design_id: designId,
                    title: title,
                    image_url: imageUrl
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message || 'حدث خطأ أثناء الإضافة للمفضلة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الإضافة للمفضلة');
            });
        }

        function editDesign(designId, imageUrl) {
            // Redirect to design studio with selected design
            window.location.href = `{{ route('design.studio') }}?design_id=${designId}&image_url=${encodeURIComponent(imageUrl)}`;
        }

        // Show loading state on form submit
        document.querySelector('.search-form').addEventListener('submit', function() {
            document.getElementById('loadingState').classList.add('active');
        });
    </script>
</body>
</html>
