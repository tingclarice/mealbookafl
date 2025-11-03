@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="css/menuIndex.css">
@endsection


@section('content')
    {{-- Hero Section --}}
    <section class="text-center text-white d-flex align-items-center justify-content-center flex-column"
                style="background: linear-gradient(rgba(249, 115, 82, 0.8), rgba(249, 115, 82, 0.8)), url('{{ asset('images/hero-bg.webp') }}') center/cover no-repeat; height: 40vh;">
        <h1 class="mb-3" style="font-family: 'Pacifico'; font-size: 3.5rem;">Menu Kami</h1>
        <p class="lead" style="font-size: 1.2rem; max-width: 600px;">Pilih makanan favoritmu!</p>
    </section>

    {{-- Search Section --}}
    <section class="py-4" style="background-color: #FEF3F0;">
        <div class="container">
            <div class="d-flex justify-content-center">
                <form method="GET" action="{{ route('menu') }}" class="d-flex gap-2" style="max-width: 600px; width: 100%;">
                    {{-- Preserve category filter --}}
                    @if($currentCategory)
                        <input type="hidden" name="category" value="{{ $currentCategory }}">
                    @endif
                    
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Cari menu..." 
                        value="{{ $searchQuery ?? '' }}"
                        style="border: 2px solid #F97352; border-radius: 10px; padding: 12px 20px;"
                    >
                    <button 
                        type="submit" 
                        class="btn"
                        style="background-color: #F97352; color: white; border-radius: 10px; padding: 12px 24px; border: none; white-space: nowrap;"
                    >
                        <i class="bi bi-search"></i> Cari
                    </button>
                    
                    {{-- Clear button if search is active --}}
                    @if($searchQuery)
                        <a 
                            href="{{ route('menu', ['category' => $currentCategory]) }}" 
                            class="btn"
                            style="background-color: #64748B; color: white; border-radius: 10px; padding: 12px 24px; border: none;"
                        >
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </section>

    {{-- Filter Section --}}
    <section class="py-4" style="background-color: #FEF3F0;">
        <div class="container">
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                {{-- Loop Categories from Controller --}}
                <x-filter-button label="Semua" :isActive="!$currentCategory" />
                @foreach($categories as $key => $label)
                    <x-filter-button 
                        :label="$label" 
                        :category="$key" 
                        :isActive="$currentCategory == $key" 
                    />
                @endforeach
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
                <div class="d-flex flex-wrap gap-4 justify-content-center">
                    @foreach ($meals as $meal)
                        <div class="meal-card-wrapper">
                            <x-meal-card :meal="$meal" />
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
                                        <span class="page-link" style="background-color: #fff; border: 2px solid #F97352; color: #64748B; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">
                                            {{-- Teks diganti dengan ikon --}}
                                            <i class="bi bi-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $meals->previousPageUrl() }}" style="background-color: #F97352; border: 2px solid #F97352; color: white; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">
                                            {{-- Teks diganti dengan ikon --}}
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
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
                                        <a class="page-link" href="{{ $meals->nextPageUrl() }}" style="background-color: #F97352; border: 2px solid #F97352; color: white; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">
                                            {{-- Teks diganti dengan ikon --}}
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link" style="background-color: #fff; border: 2px solid #F97352; color: #64748B; border-radius: 10px; margin: 0 5px; padding: 10px 20px;">
                                            {{-- Teks diganti dengan ikon --}}
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
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