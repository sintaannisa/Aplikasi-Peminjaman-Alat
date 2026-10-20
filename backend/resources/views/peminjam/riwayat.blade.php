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


@if($peminjamans->count() > 0)

    {{-- Tabel riwayat --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px] text-left text-sm">

                {{-- Header tabel --}}
                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr class="text-xs uppercase text-slate-500">

                        <th class="px-5 py-4">
                            ID
                        </th>

                        <th class="px-5 py-4">
                            Alat yang Dipinjam
                        </th>

                        <th class="px-5 py-4">
                            Kategori
                        </th>

                        <th class="px-5 py-4">
                            Diajukan
                        </th>

                        <th class="px-5 py-4">
                            Tanggal Pinjam
                        </th>

                        <th class="px-5 py-4">
                            Rencana Kembali
                        </th>

                        <th class="px-5 py-4 text-center">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- Isi tabel --}}
                <tbody class="divide-y divide-slate-100">

                    @foreach($peminjamans as $peminjaman)

                        <tr class="transition hover:bg-slate-50">

                            {{-- ID --}}
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-700">
                                #{{ $peminjaman->id }}
                            </td>


                            {{-- Alat --}}
                            <td class="px-5 py-4">

                                @forelse($peminjaman->detailPinjam as $detail)

                                    <div class="font-medium text-slate-700">
                                        {{ $detail->alat->nama_alat ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-400">
                                        Jumlah: {{ $detail->jumlah }}
                                    </div>

                                    @if(!$loop->last)
                                        <div class="my-2 border-b border-slate-100"></div>
                                    @endif

                                @empty

                                    <span class="text-slate-400">
                                        Tidak ada alat
                                    </span>

                                @endforelse

                            </td>


                            {{-- Kategori --}}
                            <td class="px-5 py-4 text-slate-500">

                                @forelse($peminjaman->detailPinjam as $detail)

                                    <div>
                                        {{ $detail->alat->kategori->nama_kategori ?? '-' }}
                                    </div>

                                    @if(!$loop->last)
                                        <div class="my-2 border-b border-slate-100"></div>
                                    @endif

                                @empty

                                    <span>
                                        -
                                    </span>

                                @endforelse

                            </td>


                            {{-- Diajukan --}}
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">

                                {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }}

                            </td>


                            {{-- Tanggal Pinjam --}}
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">

                                {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }}

                            </td>


                            {{-- Rencana Kembali --}}
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">

                                {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('d M Y') }}

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4 text-center">

                                @if($peminjaman->status === 'diajukan')

                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                        Diajukan
                                    </span>

                                @elseif($peminjaman->status === 'dipinjam')

                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                        Dipinjam
                                    </span>

                                @elseif($peminjaman->status === 'dikembalikan')

                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                        Dikembalikan
                                    </span>

                                @elseif($peminjaman->status === 'telat')

                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Terlambat
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-center">

                                {{-- Jika masih pengajuan --}}
                                @if($peminjaman->status === 'diajukan')

                                    <form
                                        action="{{ route('peminjam.peminjaman.tolak', $peminjaman->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin membatalkan pengajuan peminjaman ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="whitespace-nowrap rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-50"
                                        >
                                            Batalkan
                                        </button>

                                    </form>


                                {{-- Jika sedang dipinjam atau telat --}}
                                @elseif($peminjaman->status === 'dipinjam' || $peminjaman->status === 'telat')

                                    {{-- Sudah mengajukan pengembalian --}}
                                    @if($peminjaman->pengembalian_diajukan)

                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                            Menunggu Petugas
                                        </span>


                                    {{-- Belum mengajukan pengembalian --}}
                                    @else

                                        <form
                                            action="{{ route('peminjam.peminjaman.pengembalian', $peminjaman->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin mengajukan pengembalian alat ini kepada petugas?')"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="whitespace-nowrap rounded-lg bg-emerald-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-emerald-700"
                                            >
                                                Ajukan Pengembalian
                                            </button>

                                        </form>

                                    @endif


                                {{-- Sudah dikembalikan --}}
                                @elseif($peminjaman->status === 'dikembalikan')

                                    <span class="text-xs text-slate-400">
                                        Selesai
                                    </span>


                                {{-- Status lainnya --}}
                                @else

                                    <span class="text-xs text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


@else

    {{-- Belum ada riwayat --}}
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

@endif

@endsection