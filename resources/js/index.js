document.addEventListener('DOMContentLoaded', function () {
    const sideMenu = document.querySelector('aside');
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const darkMode = document.querySelector('.dark-mode');

    // التحقق إذا كانت قيمة localStorage تحتوي على الوضع الداكن
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode-variables');
        darkMode.querySelector('span:nth-child(1)').classList.add('active');
        darkMode.querySelector('span:nth-child(2)').classList.add('active');
    }

    menuBtn.addEventListener('click', () => {
        sideMenu.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        sideMenu.style.display = 'none';
    });

    darkMode.addEventListener('click', () => {
        // التبديل بين الوضع الداكن والتقليدي
        document.body.classList.toggle('dark-mode-variables');
        darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
        darkMode.querySelector('span:nth-child(2)').classList.toggle('active');

        // حفظ حالة الوضع الداكن في localStorage
        if (document.body.classList.contains('dark-mode-variables')) {
            localStorage.setItem('darkMode', 'enabled');  // حفظ الوضع الداكن
        } else {
            localStorage.setItem('darkMode', 'disabled'); // حفظ الوضع العادي
        }
    });
});
