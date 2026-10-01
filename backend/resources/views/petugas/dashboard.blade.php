@extends('layouts.app')

@section('title', 'Dashboard Petugas - Sistem Peminjaman')

@section('header-title', 'Ringkasan Aktivitas Petugas')

@section('content')

<!-- Alert Selamat Datang -->
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm">
    Selamat datang,
    <strong>{{ auth()->user()->name }}</strong>
    di Dashboard Petugas dengan hak akses
    <span class="uppercase font-bold text-emerald-900">
        {{ auth()->user()->role }}
    </span>.
</div>


<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

    <!-- Pengajuan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <p class="text-sm text-gray-500">
            Pengajuan Menunggu
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $pengajuan }}
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Menunggu persetujuan
        </p>
    </div>


    <!-- Sedang Dipinjam -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <p class="text-sm text-gray-500">
            Sedang Dipinjam
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $sedangDipinjam }}
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Alat sedang digunakan
        </p>
    </div>


    <!-- Terlambat -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <p class="text-sm text-gray-500">
            Terlambat
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $terlambat }}
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Perlu ditindaklanjuti
        </p>
    </div>


    <!-- Dikembalikan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <p class="text-sm text-gray-500">
            Dikembalikan
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $dikembalikan }}
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Peminjaman selesai
        </p>
    </div>

</div>


<!-- Statistik Tambahan -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

    <!-- Total Peminjaman -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <p class="text-sm text-gray-500">
            Total Seluruh Peminjaman
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $totalPeminjaman }}
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Semua data peminjaman
        </p>
    </div>


    <!-- Total Alat -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <p class="text-sm text-gray-500">
            Total Alat
        </p>

        <h2 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $totalAlat }}
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Jumlah alat dalam inventaris
        </p>
    </div>

</div>


<!-- Peminjaman Terbaru -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">

    <div class="p-5 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-bold text-gray-800">
            Peminjaman Terbaru
        </h3>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">

                    <th class="py-3 px-4 border-b">
                        ID
                    </th>

                    <th class="py-3 px-4 border-b">
                        Peminjam
                    </th>

                    <th class="py-3 px-4 border-b">
                        TGL Pinjam
                    </th>

                    <th class="py-3 px-4 border-b">
                        Status
                    </th>

                </tr>
            </thead>


            <tbody class="text-gray-700 text-sm">

                @forelse($peminjamanTerbaru as $peminjaman)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="py-3 px-4 border-b">
                            #{{ $peminjaman->id }}
                        </td>

                        <td class="py-3 px-4 border-b">
                            {{ $peminjaman->user->name ?? 'Tidak diketahui' }}
                        </td>

                        <td class="py-3 px-4 border-b">
                            {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d-m-Y') }}
                        </td>

                        <td class="py-3 px-4 border-b">

                            @if($peminjaman->status === 'diajukan')

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Diajukan
                                </span>

                            @elseif($peminjaman->status === 'dipinjam')

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Dipinjam
                                </span>

                            @elseif($peminjaman->status === 'telat')

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Telat
                                </span>

                            @elseif($peminjaman->status === 'dikembalikan')

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Dikembalikan
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $peminjaman->status }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">
                            Belum ada data peminjaman.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection
