FROM php:8.2-fpm

# ติดตั้งส่วนเสริมของ PHP และอื่นๆ ที่ Laravel ต้องใช้
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# กำหนด working directory
WORKDIR /var/www

# คัดลอกไฟล์ทั้งหมดไปที่ container
COPY . .

# ให้ PHP-FPM ทำงาน
CMD ["php-fpm"]
