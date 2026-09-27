@extends('layouts.app')

@section('title', 'Kelola Pengembalian - Panel Admin')
@section('header-title', 'Kelola Pengembalian')

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 p-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-800">
                Riwayat Pengembalian Alat
            </h2>

            <div class="flex items-center gap-2">

                {{-- Form Pencarian --}}
                <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="flex items-center">
                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari peminjam / kondisi..."
                        class="w-52 px-3 py-2 border border-gray-300 rounded-l-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <button type="submit"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-r-lg text-sm font-semibold transition">
                        Cari
                    </button>
                </form>

                {{-- Tombol Proses Pengembalian --}}
                <a href="{{ route('admin.pengembalian.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    + Proses Pengembalian
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">

                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Peminjam</th>
                        <th class="px-4 py-3">Tgl Kembali</th>
                        <th class="px-4 py-3">Kondisi Alat</th>
                        <th class="px-4 py-3">Denda</th>
                        <th class="px-4 py-3">Petugas Verifikasi</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pengembalians as $pengembalian)
                        <tr class="border-b hover:bg-gray-50">

                            {{-- No --}}
                            <td class="px-4 py-3">
                                {{ $loop->iteration + ($pengembalians->currentPage() - 1) * $pengembalians->perPage() }}
                            </td>

                            {{-- Peminjam --}}
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $pengembalian->peminjaman->user->name ?? '-' }}
                            </td>

                            {{-- Tanggal Kembali --}}
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($pengembalian->tgl_kembali)->format('Y-m-d') }}
                            </td>

                            {{-- Kondisi Alat --}}
                            <td class="px-4 py-3">
                                @php
                                    $kondisi = strtolower($pengembalian->kondisi_kembali);
                                @endphp

                                @if($kondisi == 'baik')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Baik
                                    </span>
                                @elseif(str_contains($kondisi, 'rusak'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        {{ $pengembalian->kondisi_kembali }}
                                    </span>
                                @elseif(str_contains($kondisi, 'lecet'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        {{ $pengembalian->kondisi_kembali }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ $pengembalian->kondisi_kembali }}
                                    </span>
                                @endif
                            </td>

                            {{-- Denda --}}
                            <td class="px-4 py-3 font-semibold text-red-600">
                                Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                            </td>

                            {{-- Petugas Verifikasi --}}
                            <td class="px-4 py-3">
                                {{ $pengembalian->petugas->name ?? '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.pengembalian.destroy', $pengembalian->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition">
                                        Hapus
                                    </button>

                                </form>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data pengembalian.
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $pengembalians->links() }}
        </div>

    </div>
@endsection