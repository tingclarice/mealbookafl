@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="text-center text-white d-flex align-items-center justify-content-center flex-column"
                style="background: linear-gradient(rgba(249, 115, 82, 0.8), rgba(249, 115, 82, 0.8)), url('{{ asset('images/hero-bg.png') }}') center/cover no-repeat; height: 40vh;">
        <h1 class="mb-3" style="font-family: 'Pacifico'; font-size: 3.5rem;">Menu Kami</h1>
        <p class="lead" style="font-size: 1.2rem; max-width: 600px;">Pilih makanan favoritmu!</p>
    </section>

    {{-- Filter Section --}}
    <section class="py-4" style="background-color: #FEF3F0;">
        <div class="container">
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('menu') }}" class="btn px-4 py-2 fw-bold {{ !request('category') ? 'active' : '' }}"
                    style="background-color: {{ !request('category') ? '#F97352' : '#fff' }}; 
                            color: {{ !request('category') ? '#fff' : '#1E293B' }}; 
                            border-radius: 25px; 
                            border: 2px solid #F97352;">
                    Semua
                </a>
                <a href="{{ route('menu', ['category' => 'MEAL']) }}" class="btn px-4 py-2 fw-bold {{ request('category') == 'MEAL' ? 'active' : '' }}"
                    style="background-color: {{ request('category') == 'MEAL' ? '#F97352' : '#fff' }}; 
                            color: {{ request('category') == 'MEAL' ? '#fff' : '#1E293B' }}; 
                            border-radius: 25px; 
                            border: 2px solid #F97352;">
                    Makanan
                </a>
                <a href="{{ route('menu', ['category' => 'SNACK']) }}" class="btn px-4 py-2 fw-bold {{ request('category') == 'SNACK' ? 'active' : '' }}"
                    style="background-color: {{ request('category') == 'SNACK' ? '#F97352' : '#fff' }}; 
                            color: {{ request('category') == 'SNACK' ? '#fff' : '#1E293B' }}; 
                            border-radius: 25px; 
                            border: 2px solid #F97352;">
                    Snack
                </a>
                <a href="{{ route('menu', ['category' => 'DRINKS']) }}" class="btn px-4 py-2 fw-bold {{ request('category') == 'DRINKS' ? 'active' : '' }}"
                    style="background-color: {{ request('category') == 'DRINKS' ? '#F97352' : '#fff' }}; 
                            color: {{ request('category') == 'DRINKS' ? '#fff' : '#1E293B' }}; 
                            border-radius: 25px; 
                            border: 2px solid #F97352;">
                    Minuman
                </a>
            </div>
        </div>
    </section>

    {{-- Menu Grid Section --}}
    <section class="py-5" style="background-color: #FEF3F0; min-height: 60vh;">
        <div class="container">
            @if($meals->isEmpty())
                <div class="text-center py-5">
                    <h3 style="color: #64748B;">Tidak ada menu tersedia.</h3>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($meals as $meal)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card shadow-sm border-0 h-100" style="border-radius: 20px;">
                                <a href="{{ route('menu.show', $meal->id) }}" class="text-decoration-none">
                                    <img src="{{ asset($meal->image_url) }}" class="card-img-top" alt="{{ $meal->name }}" 
                                        style="border-top-left-radius: 20px; border-top-right-radius: 20px; height: 200px; object-fit: cover;">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <a href="{{ route('menu.show', $meal->id) }}" class="text-decoration-none">
                                        <h5 class="card-title mb-1" style="color: #1E293B;">{{ $meal->name }}</h5>
                                    </a>
                                    <p class="text-muted mb-2 flex-grow-1" style="font-size: 0.85rem;">{{ Str::limit($meal->description, 60) }}</p>
                                    <p class="text-muted mb-3 fw-bold">Rp. {{ number_format($meal->price, 0, ',', '.') }}</p>
                                    <a href="{{ route('menu.show', $meal->id) }}" class="btn w-100 fw-bold text-decoration-none" 
                                        style="background-color: #F97352; color: white; border-radius: 15px; border: none; padding: 8px 0;">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($meals->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        <nav>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($meals->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link" style="background-color: #fff; border: 2px solid #F97352; color: #64748B; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">‹ Sebelumnya</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $meals->previousPageUrl() }}" style="background-color: #F97352; border: 2px solid #F97352; color: white; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">‹ Sebelumnya</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($meals->getUrlRange(1, $meals->lastPage()) as $page => $url)
                                    @if ($page == $meals->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link" style="background-color: #F97352; border: 2px solid #F97352; color: white; border-radius: 10px; margin: 0 5px; min-width: 45px; text-align: center; padding: 10px;">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}" style="background-color: #fff; border: 2px solid #F97352; color: #1E293B; border-radius: 10px; margin: 0 5px; min-width: 45px; text-align: center; padding: 10px;">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($meals->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $meals->nextPageUrl() }}" style="background-color: #F97352; border: 2px solid #F97352; color: white; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">Selanjutnya ›</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link" style="background-color: #fff; border: 2px solid #F97352; color: #64748B; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">Selanjutnya ›</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection