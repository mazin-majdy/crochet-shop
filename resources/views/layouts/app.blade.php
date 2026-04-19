<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#8B7355"> <!-- لون الشريط في الموبايل -->
    <title>لوحة تحكم {{ setting('site.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
</head>

<body>

    <div class="overlay" id="sidebarOverlay"></div>

    <div class="container-fluid p-0">
        <div class="d-flex">
            <nav class="sidebar shadow" id="sidebar">
                <div class="brand-section">
                    <h4>{{ setting('site.name') }}</h4>
                    <p class="small text-white-50">نصنع الجمال يدوياً</p>
                </div>
                <div class="mt-3">
                    <a href="#" class="active"><i class="bi bi-grid-1x2-fill"></i> الرئيسية</a>
                    <a href="#"><i class="bi bi-flower1"></i> التطريز</a>
                    <a href="#"><i class="bi bi-scissors"></i> أشغال يدوية</a>
                    <a href="#"><i class="bi bi-basket2"></i> أعمال الصوف</a>
                    <a href="#"><i class="bi bi-bag-check"></i> الطلبات</a>
                    <hr class="mx-3 opacity-25">
                    <a href="#" class="text-warning"><i class="bi bi-box-arrow-right"></i> خروج</a>
                </div>
            </nav>

            <div class="flex-grow-1 main-content">
                <header class="top-navbar d-flex justify-content-between align-items-center shadow-sm">
                    <div class="d-flex align-items-center">
                        <button class="btn d-md-none nav-icon-btn" id="sidebarCollapse">
                            <i class="bi bi-list"></i>
                        </button>
                        <h5 class="m-0 fw-bold d-none d-md-block text-brown">لوحة الإدارة</h5>
                    </div>
                    <div class="d-flex align-items-center">

                        <div class="dropdown me-2">
                            <a href="#" class="nav-icon-btn" id="msgDropdown" data-bs-toggle="dropdown"
                                data-bs-boundary="viewport" data-bs-display="static" aria-expanded="false">
                                <i class="bi bi-chat-dots"></i>
                                <span class="badge-notify">3</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="msgDropdown">
                                <div class="dropdown-header">
                                    <span>الرسائل الواردة</span>
                                    <a href="#" class="small text-decoration-none text-primary">تحديد كقروء</a>
                                </div>

                                <a class="dropdown-item d-flex align-items-start" href="#">
                                    <div class="bg-light rounded-circle me-3"
                                        style="width: 45px; height: 45px; background-image: url('https://i.pravatar.cc/150?u=1'); background-size: cover;">
                                    </div>
                                    <div class="item-content flex-grow-1">
                                        <h6>سارة أحمد</h6>
                                        <p>هل يمكنني طلب تطريز مخصص لثوب زفاف؟</p>
                                        <span class="time-ago">منذ 5 دقائق</span>
                                    </div>
                                </a>

                                <a class="dropdown-item d-flex align-items-start" href="#">
                                    <div class="bg-light rounded-circle me-3"
                                        style="width: 45px; height: 45px; background-image: url('https://i.pravatar.cc/150?u=2'); background-size: cover;">
                                    </div>
                                    <div class="item-content flex-grow-1">
                                        <h6>خالد محمد</h6>
                                        <p>تم استلام طلب شال الصوف، جودته رائعة جداً!</p>
                                        <span class="time-ago">منذ ساعتين</span>
                                    </div>
                                </a>

                                <div class="text-center mt-2">
                                    <a href="#" class="btn btn-sm text-brown fw-600 w-100">عرض جميع الرسائل</a>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown me-2">
                            <a href="#" class="nav-icon-btn" id="notifyDropdown" data-bs-toggle="dropdown"
                                data-bs-boundary="viewport" data-bs-display="static" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span class="badge-notify">2</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifyDropdown">
                                <div class="dropdown-header">
                                    <span>الإشعارات</span>
                                </div>

                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="notify-icon bg-success bg-opacity-10 text-success me-3">
                                        <i class="bi bi-bag-plus"></i>
                                    </div>
                                    <div class="item-content">
                                        <h6>طلب جديد #5502</h6>
                                        <p>قام زبون بشراء "منديل مطرز"</p>
                                        <span class="time-ago">منذ 10 دقائق</span>
                                    </div>
                                </a>

                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="notify-icon bg-warning bg-opacity-10 text-warning me-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <div class="item-content">
                                        <h6>تنبيه المخزون</h6>
                                        <p>خيوط الحرير الأحمر أوشكت على النفاد</p>
                                        <span class="time-ago">منذ ساعة</span>
                                    </div>
                                </a>

                                <div class="text-center mt-2">
                                    <a href="#" class="btn btn-sm text-brown fw-600 w-100">مشاهدة الكل</a>
                                </div>
                            </div>
                        </div>

                        <div class="ms-3 border-start ps-3 d-none d-sm-block">
                            <span class="small fw-bold">مازن المجدي</span>
                        </div>
                    </div>
                </header>

                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const collapseBtn = document.getElementById('sidebarCollapse');

        collapseBtn?.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        overlay?.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
