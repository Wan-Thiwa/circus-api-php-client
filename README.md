# circus-client-php

ไลบรารี PHP สำหรับอ่านและเขียนค่าผ่าน API ของ [Circus of Things](https://circusofthings.com)

## 🔧 การติดตั้ง

1. **Clone โปรเจกต์**

```bash
git clone https://github.com/Wan-Thiwa/circus-api-php-client.git
cd circus-api-php-client
```

2. **ติดตั้ง Composer dependencies (สำหรับโหลด .env)**

```bash
composer require vlucas/phpdotenv
```

3. **สร้างไฟล์ .env จากตัวอย่าง**

```bash
cp .env.example .env
```

4. **จากนั้นใส่ API token ของคุณลงใน .env**

```env
API_TOKEN=your_circus_token_here
```

5. **🚀 วิธีใช้งาน**

```php
<?php
require 'vendor/autoload.php';
require_once 'CircusClient.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new CircusClient();

// ส่งค่าไปยัง Circus of Things
$response = $client->writeValue("your_public_key", 12.34);
print_r($response);

// อ่านค่าจาก Circus of Things
$response = $client->readValues("your_public_key");
print_r($response);
```
