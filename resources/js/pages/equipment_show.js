console.log("loaded");

const rows = document.querySelectorAll('.text-center.border.border-dark');
const closes = document.querySelectorAll('.btn-close');

// ล้าง localStorage ที่ครั้งที่คลิ๊ก equipment row
rows.forEach(row => {
    row.addEventListener('click', function () {
        localStorage.clear();
        localStorage.setItem("url_to_back",window.location.href);
        // เก็บค่าลิงค์ปัจจุบันไวใน localStorage
    });
});

// โหลด
closes.forEach(close => {
    close.addEventListener('click', function () {
        location.reload();
    })
});