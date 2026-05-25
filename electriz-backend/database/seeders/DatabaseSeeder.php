<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Демо пользователь',
                'password' => Hash::make('password'),
                'phone' => '+7 900 000-00-00',
                'address' => 'Москва, ул. Тестовая, 1',
            ]
        );

        $categories = [
            ['name' => 'Смартфоны', 'slug' => 'smartphones', 'image' => '/images/categories/smartphones.jpg'],
            ['name' => 'Ноутбуки', 'slug' => 'laptops', 'image' => '/images/categories/laptops.jpg'],
            ['name' => 'Планшеты', 'slug' => 'tablets', 'image' => '/images/categories/tablets.jpg'],
            ['name' => 'Наушники', 'slug' => 'headphones', 'image' => '/images/categories/headphones.jpg'],
            ['name' => 'Аксессуары', 'slug' => 'accessories', 'image' => '/images/categories/accessories.jpg'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $categoryIds = Category::pluck('id', 'slug');

        $products = [
            ['name' => 'iPhone 15 Pro', 'price' => 129990, 'category_id' => $categoryIds['smartphones'], 'brand' => 'Apple', 'sku' => 'IPH15PRO', 'stock' => 15, 'image' => '/images/products/iphone-15-pro.jpg', 'description' => 'Флагманский смартфон с титановым корпусом, камерой 48 Мп и процессором A17 Pro.'],
            ['name' => 'Samsung Galaxy S24', 'price' => 89990, 'category_id' => $categoryIds['smartphones'], 'brand' => 'Samsung', 'sku' => 'SGS24', 'stock' => 20, 'image' => '/images/products/galaxy-s24.jpg', 'description' => 'Компактный Android-смартфон с AMOLED-экраном, мощным процессором и функциями Galaxy AI.'],
            ['name' => 'Xiaomi 14', 'price' => 69990, 'category_id' => $categoryIds['smartphones'], 'brand' => 'Xiaomi', 'sku' => 'XMI14', 'stock' => 18, 'image' => '/images/products/xiaomi-14.jpg', 'description' => 'Смартфон с камерой Leica, быстрым чипом Snapdragon и ярким дисплеем.'],
            ['name' => 'MacBook Pro 14', 'price' => 199990, 'category_id' => $categoryIds['laptops'], 'brand' => 'Apple', 'sku' => 'MBP14M3', 'stock' => 8, 'image' => '/images/products/macbook-pro-14.jpg', 'description' => 'Профессиональный ноутбук с чипом M3 Pro, Retina-дисплеем и большим запасом автономности.'],
            ['name' => 'ASUS ROG Zephyrus G14', 'price' => 149990, 'category_id' => $categoryIds['laptops'], 'brand' => 'ASUS', 'sku' => 'ROG-ZEPH-G14', 'stock' => 5, 'image' => '/images/products/asus-rog.jpg', 'description' => 'Игровой ноутбук с дискретной графикой RTX, быстрым экраном и производительной системой охлаждения.'],
            ['name' => 'Lenovo IdeaPad Slim 5', 'price' => 67990, 'category_id' => $categoryIds['laptops'], 'brand' => 'Lenovo', 'sku' => 'IDEA-SLIM5', 'stock' => 12, 'image' => '/images/products/lenovo-ideapad.jpg', 'description' => 'Универсальный ноутбук для учебы, работы и повседневных задач.'],
            ['name' => 'iPad Pro 12.9', 'price' => 109990, 'category_id' => $categoryIds['tablets'], 'brand' => 'Apple', 'sku' => 'IPADPRO129', 'stock' => 12, 'image' => '/images/products/ipad-pro.jpg', 'description' => 'Планшет с большим Liquid Retina XDR экраном, поддержкой Apple Pencil и высокой производительностью.'],
            ['name' => 'Samsung Galaxy Tab S9', 'price' => 79990, 'category_id' => $categoryIds['tablets'], 'brand' => 'Samsung', 'sku' => 'TABS9', 'stock' => 10, 'image' => '/images/products/galaxy-tab-s9.jpg', 'description' => 'Планшет с AMOLED-дисплеем, защитой от влаги и комплектным стилусом S Pen.'],
            ['name' => 'AirPods Pro 2', 'price' => 24990, 'category_id' => $categoryIds['headphones'], 'brand' => 'Apple', 'sku' => 'APP2', 'stock' => 30, 'image' => '/images/products/airpods-pro-2.jpg', 'description' => 'Беспроводные наушники с активным шумоподавлением и адаптивным аудио.'],
            ['name' => 'Sony WH-1000XM5', 'price' => 29990, 'category_id' => $categoryIds['headphones'], 'brand' => 'Sony', 'sku' => 'SONYWH5', 'stock' => 25, 'image' => '/images/products/sony-wh1000xm5.jpg', 'description' => 'Полноразмерные наушники с сильным шумоподавлением и временем работы до 30 часов.'],
            ['name' => 'MagSafe Charger', 'price' => 3990, 'category_id' => $categoryIds['accessories'], 'brand' => 'Apple', 'sku' => 'MAGSAFE', 'stock' => 50, 'image' => '/images/products/magsafe.jpg', 'description' => 'Компактное магнитное зарядное устройство для iPhone.'],
            ['name' => 'Anker PowerCore 20000', 'price' => 5990, 'category_id' => $categoryIds['accessories'], 'brand' => 'Anker', 'sku' => 'ANK-PC20', 'stock' => 35, 'image' => '/images/products/anker-powercore.jpg', 'description' => 'Внешний аккумулятор большой емкости для смартфонов, планшетов и наушников.'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['sku' => $product['sku']], $product);
        }

        $news = [
            ['title' => 'Новые смартфоны уже в каталоге', 'body' => 'В магазин добавлены актуальные модели Apple, Samsung и Xiaomi. Для первых заказов действует бесплатная доставка.', 'image' => '/images/news/new-smartphones.jpg', 'published_at' => now()],
            ['title' => 'Скидки на ноутбуки для учебы', 'body' => 'Подборка ноутбуков для студентов и разработчиков доступна в разделе специальных предложений.', 'image' => '/images/news/laptop-sale.jpg', 'published_at' => now()],
            ['title' => 'Бесплатная доставка от 10 000 рублей', 'body' => 'При заказе техники и аксессуаров на сумму от 10 000 рублей доставка по городу выполняется бесплатно.', 'image' => '/images/news/delivery.jpg', 'published_at' => now()],
        ];

        foreach ($news as $item) {
            News::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}
