@extends('layouts.app')

@section('title', 'Riwayat Peminjaman - Peminjam')
@section('header-title', 'Riwayat Peminjaman')

@section('content')

{{-- Notifikasi sukses --}}
@if(session('success'))
    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
@endif

{{-- Notifikasi error --}}
@if(session('error'))
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- Header halaman --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">
        Riwayat Peminjaman
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        Lihat daftar pengajuan dan status peminjaman alat kamu.
    </p>
</div>

@forelse($peminjamans as $peminjaman)

    <div class="mb-5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Header peminjaman --}}
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                <div>
                    <h3 class="font-semibold text-slate-800">
                        Peminjaman #{{ $peminjaman->id }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-500">
                        Diajukan pada
                        {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y, H:i') }}
                    </p>
                </div>

                {{-- Status --}}
                @if($peminjaman->status === 'diajukan')

                    <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                        Diajukan
                    </span>

                @elseif($peminjaman->status === 'dipinjam')

                    <span class="inline-flex w-fit rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                        Dipinjam
                    </span>

                @elseif($peminjaman->status === 'dikembalikan')

                    <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                        Dikembalikan
                    </span>

                @elseif($peminjaman->status === 'telat')

                    <span class="inline-flex w-fit rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                        Terlambat
                    </span>

                @else

                    <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                        {{ ucfirst($peminjaman->status) }}
                    </span>

                @endif

            </div>

        </div>

        {{-- Informasi tanggal --}}
        <div class="grid grid-cols-1 gap-4 border-b border-slate-200 px-6 py-4 md:grid-cols-2">

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">
                    Tanggal Pinjam
                </p>

                <p class="mt-1 text-sm font-medium text-slate-700">
                    {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">
                    Rencana Kembali
                </p>

                <p class="mt-1 text-sm font-medium text-slate-700">
                    {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('d M Y') }}
                </p>
            </div>

        </div>

        {{-- Daftar alat --}}
        <div class="px-6 py-5">

            <h4 class="mb-3 text-sm font-semibold text-slate-700">
                Alat yang Dipinjam
            </h4>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="border-b border-slate-200 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-3 py-3">
                                Nama Alat
                            </th>

                            <th class="px-3 py-3">
                                Kategori
                            </th>

                            <th class="px-3 py-3 text-center">
                                Jumlah
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($peminjaman->detailPinjam as $detail)

                            <tr>
                                <td class="px-3 py-3 font-medium text-slate-700">
                                    {{ $detail->alat->nama_alat ?? '-' }}
                                </td>

                                <td class="px-3 py-3 text-slate-500">
                                    {{ $detail->alat->kategori->nama_kategori ?? '-' }}
                                </td>

                                <td class="px-3 py-3 text-center text-slate-700">
                                    {{ $detail->jumlah }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="3"
                                    class="px-3 py-5 text-center text-sm text-slate-400"
                                >
                                    Tidak ada detail alat.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        {{-- Aksi --}}
        @if($peminjaman->status === 'diajukan')

            <div class="flex justify-end border-t border-slate-200 px-6 py-4">

                <form
                    action="{{ route('peminjam.peminjaman.tolak', $peminjaman->id) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin membatalkan pengajuan peminjaman ini?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50"
                    >
                        Batalkan Pengajuan
                    </button>

                </form>

            </div>

        @endif

    </div>

@empty

    {{-- Jika belum punya riwayat --}}
    <div class="rounded-xl border border-slate-200 bg-white px-6 py-12 text-center shadow-sm">

        <div class="mx-auto max-w-md">

            <h3 class="text-lg font-semibold text-slate-700">
                Belum Ada Riwayat Peminjaman
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Kamu belum memiliki pengajuan peminjaman alat.
            </p>

            <a
                href="{{ route('peminjam.katalog') }}"
                class="mt-5 inline-flex rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
            >
                Lihat Katalog Alat
            </a>

        </div>

    </div>

@endforelse


@endsection
