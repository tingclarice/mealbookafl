@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="text-center text-white d-flex align-items-center justify-content-center flex-column"
                style="background: linear-gradient(rgba(249, 115, 82, 0.8), rgba(249, 115, 82, 0.8)), url('{{ asset('images/hero-bg.webp') }}') center/cover no-repeat; height: 50vh;">
        <h1 class="mb-3" style="font-family: 'Pacifico'; font-size: 3.5rem;">Tentang MealBook</h1>
        <p class="lead" style="font-size: 1.2rem; max-width: 600px;">Order dari kantin favorit kamu,<br>sekarang tinggal klik saja!</p>
    </section>

    {{-- Our Story Section --}}
    <section class="py-5" style="background-color: #fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="mb-4" style="font-family: 'Potta One'; color: #F97352;">Cerita Kami</h2>
                    <p class="mb-3" style="color: #1E293B; line-height: 1.8;">
                        MealBook lahir dari ide sederhana, yaitu memudahkan waktu makan untuk semua orang. Kami tahu betapa sibuknya waktu makan siang dengan antrian panjang, waktu yang terbatas, dan ribet saat memesan makanan.
                    </p>
                    <p class="mb-3" style="color: #1E293B; line-height: 1.8;">
                        Karena itu kami menciptakan MealBook, sebuah platform di mana kamu bisa melihat menu, memesan makanan dari jauh hari, dan tinggal ambil tanpa perlu antri. Lebih banyak waktu untuk hal yang penting, lebih banyak waktu bersama orang tersayang, dan tentu saja lebih banyak waktu menikmati makanan lezat!
                    </p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/nasi-goreng.png') }}" alt="Cerita Kami" class="img-fluid rounded shadow" style="border-radius: 20px !important;">
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    <section class="py-5" style="background-color: #FEF3F0;">
        <div class="container">
            <h2 class="text-center mb-5" style="font-family: 'Potta One'; color: #F97352;">Kenapa Pilih MealBook?</h2>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="mb-3" style="font-size: 3rem;">⚡</div>
                        <h4 class="mb-3" style="color: #1E293B;">Cepat & Mudah</h4>
                        <p style="color: #64748B;">Pesan makananmu dalam hitungan detik dan skip antrian. Cocok banget buat hari yang sibuk!</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="mb-3" style="font-size: 3rem;">😋</div>
                        <h4 class="mb-3" style="color: #1E293B;">Segar & Enak</h4>
                        <p style="color: #64748B;">Kami menyiapkan setiap makanan dengan penuh cinta menggunakan bahan segar setiap hari.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="mb-3" style="font-size: 3rem;">💰</div>
                        <h4 class="mb-3" style="color: #1E293B;">Harga Terjangkau</h4>
                        <p style="color: #64748B;">Harga yang ramah di kantong tanpa mengurangi kualitas. Makanan berkualitas untuk semua orang!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-5" style="background-color: #fff;">
        <div class="container text-center">
            <h2 class="mb-4" style="font-family: 'Potta One'; color: #F97352;">Hubungi Kami</h2>
            <p class="mb-4" style="color: #1E293B; font-size: 1.1rem;">Punya pertanyaan atau saran? Kami senang mendengar dari kamu!</p>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-4 mb-4">
                        <div>
                            <strong style="color: #F97352;">📍 Lokasi:</strong>
                            <p class="mb-0" style="color: #64748B;">jl. Serma Kawi no 4</p>
                        </div>
                        <div>
                            <strong style="color: #F97352;">⏰ Jam Buka:</strong>
                            <p class="mb-0" style="color: #64748B;">06:00-17:00</p>
                        </div>
                        <div>
                            <strong style="color: #F97352;">📞 Kontak:</strong>
                            <p class="mb-0" style="color: #64748B;">082247967548</p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('home') }}#menu" class="btn px-5 py-3 fw-bold mt-3"
                style="background-color: #F97352; color: white; border-radius: 30px; box-shadow: 0 4px #D65A3C; border: none;">
                Lihat Menu Kami
            </a>
        </div>
    </section>
@endsection