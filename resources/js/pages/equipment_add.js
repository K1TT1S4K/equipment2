//debug js load
console.log("Equipment add page js is loaded");

// รายชื่อ id ของ input ที่ต้องการจัดการ
const fields = ['number', 'name', 'equipment_unit_id', 'amount', 'price', 'status_found', 'status_not_found', 'status_disposal', 'status_broken', 'status_transfer', 'titleSelect', 'equipmentTypeSelect', 'user_id', 'location_id', 'description'];

// โหลดค่า input ที่เก็บไว้ใน localstorage เพื่อเวลารีเฟรชหน้าจะได้ไม่ต้องกรอกค่าใหม่
fields.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        if (localStorage.getItem(id) != null) {
            el.value = localStorage.getItem(id) || '';
        }
        //  console.log("el :", localStorage.getItem(id)); // แก้จุดนี้
        el.addEventListener('input', function () {
            localStorage.setItem(id, this.value);
        });
    }
});

function backToPage(event) {
     event.preventDefault(); // ป้องกันการกระโดดขึ้นบน
    window.location.href = localStorage.getItem("url_to_back");
}