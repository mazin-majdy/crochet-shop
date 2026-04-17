// ─── Auto-dismiss alerts ───────────────────────────────────────────────────
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-8px)';
        setTimeout(() => el.remove(), 500);
    }, 4500);
});
document.addEventListener('DOMContentLoaded', () => {

    const imageInput = document.getElementById('imageInput');
    const dropZone = document.getElementById('dropZone'); // ✅ استخدم ID بدلاً من selector معقد
    const previewImg = document.getElementById('previewImg');
    const imgPreview = document.getElementById('imgPreview');

    // ─── معاينة الصورة (مركزي) ─────────────────────────────
    function previewFile(file) {
        if (!file || !file.type?.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            if (previewImg && imgPreview) {
                previewImg.src = e.target.result;
                imgPreview.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }

    if (imageInput) {
        imageInput.addEventListener('change', function () {
            previewFile(this.files?.[0]);
        });
    }

    // ─── Drag & Drop ───────────────────────────────────────
    if (dropZone && imageInput) {
        ['dragenter', 'dragover'].forEach(evt =>
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation(); // ✅ مهم جداً
                dropZone.style.borderColor = '#7b1113';
                dropZone.style.background = 'rgba(123,17,19,0.03)';
            })
        );

        ['dragleave', 'drop'].forEach(evt =>
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation(); // ✅ مهم جداً
                dropZone.style.borderColor = '#e8ddd4';
                dropZone.style.background = 'var(--cream)';
            })
        );

        dropZone.addEventListener('drop', e => {
            const file = e.dataTransfer?.files?.[0];
            if (file && file.type.startsWith('image/')) {
                // ✅ الطريقة الصحيحة لتعيين الملف
                const dt = new DataTransfer();
                dt.items.add(file);
                imageInput.files = dt.files;

                // ✅ شغّل المعاينة مباشرة
                previewFile(file);

                // ✅ شغّل حدث change مع bubbling لضمان معالجة الـ Form له
                imageInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }

    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');

    toggle?.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
    });
    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Custom dropdowns
    ['msgBtn', 'notifBtn'].forEach(id => {
        const btn = document.getElementById(id);
        const menu = document.getElementById(id.replace('Btn', 'DropdownMenu').replace('msg', 'msg').replace(
            'notif', 'notif'));
        if (!btn || !menu) return;
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = menu.classList.contains('show');
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
            if (!open) menu.classList.add('show');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
    });
});
