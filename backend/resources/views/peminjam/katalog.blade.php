@extends('layouts.app')

@section('title', 'Katalog Alat - Peminjam')
@section('header-title', 'Katalog Alat')

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
        Katalog Alat
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        Pilih alat yang ingin kamu pinjam dan tentukan tanggal pengembaliannya.
    </p>
</div>

<form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST">
    @csrf

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Bagian atas --}}
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Alat Tersedia
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Centang alat yang ingin diajukan.
                    </p>
                </div>

                <div class="w-full md:w-64">
                    <label
                        for="tgl_kembali_plan"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Rencana Tanggal Kembali
                    </label>

                    <input
                        type="date"
                        id="tgl_kembali_plan"
                        name="tgl_kembali_plan"
                        value="{{ old('tgl_kembali_plan') }}"
                        min="{{ date('Y-m-d') }}"
                        required
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('tgl_kembali_plan')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">

                <thead class="bg-slate-50 text-xs uppercase text-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-center">
                            Pilih
                        </th>

                        <th class="px-6 py-4">
                            Nama Alat
                        </th>

                        <th class="px-6 py-4">
                            Kategori
                        </th>

                        <th class="px-6 py-4 text-center">
                            Stok
                        </th>

                        <th class="px-6 py-4 text-center">
                            Jumlah
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($alats as $alat)

                        <tr class="transition hover:bg-slate-50">

                            {{-- Checkbox --}}
                            <td class="px-6 py-4 text-center">
                                <input
                                    type="checkbox"
                                    name="alat_id[]"
                                    value="{{ $alat->id }}"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                >
                            </td>

                            {{-- Nama alat --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $alat->nama_alat }}
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-6 py-4">
                                {{ $alat->kategori->nama_kategori ?? '-' }}
                            </td>

                            {{-- Stok --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    {{ $alat->stok }}
                                </span>
                            </td>

                            {{-- Jumlah --}}
                            <td class="px-6 py-4">
                                <input
                                    type="number"
                                    name="jumlah[]"
                                    value="1"
                                    min="1"
                                    max="{{ $alat->stok }}"
                                    class="mx-auto block w-24 rounded-lg border border-slate-300 px-3 py-2 text-center text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-10 text-center"
                            >
                                <div class="text-slate-400">
                                    <p class="text-sm font-medium">
                                        Tidak ada alat yang tersedia.
                                    </p>

                                    <p class="mt-1 text-xs">
                                        Silakan cek kembali nanti.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        @if($alats->count() > 0)
            <div class="flex justify-end border-t border-slate-200 px-6 py-4">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Ajukan Peminjaman
                </button>

            </div>
        @endif

    </div>

</form>


@endsection
