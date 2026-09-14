<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Vip',
                'image' => 'assets/images/banners/vip.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 1,
                'active' => 1,
            ],
            [
                'title' => 'Agente',
                'image' => 'assets/images/banners/agente.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 2,
                'active' => 1,
            ],
            [
                'title' => 'Coleta',
                'image' => 'assets/images/banners/coleta.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 3,
                'active' => 1,
            ],
            [
                'title' => 'Cashback',
                'image' => 'assets/images/banners/cashback.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 4,
                'active' => 1,
            ],
            [
                'title' => 'Nivel',
                'image' => 'assets/images/banners/nivel.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 5,
                'active' => 1,
            ],
            [
                'title' => 'Magnata',
                'image' => 'assets/images/banners/magnata.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 6,
                'active' => 1,
            ],
            [
                'title' => 'Ofertas',
                'image' => 'assets/images/banners/ofertas.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 7,
                'active' => 1,
            ],
            [
                'title' => 'Troca',
                'image' => 'assets/images/banners/troca.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 8,
                'active' => 1,
            ],
            [
                'title' => 'Semana',
                'image' => 'assets/images/banners/semana.jpg',
                'type' => 'home',
                'link' => '/',
                'sort_order' => 9,
                'active' => 1,
            ],
        ];

        foreach ($banners as $banner) {
            DB::table('banners')->insert($banner);
        }
    }
}
