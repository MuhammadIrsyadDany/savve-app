@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Total Event Aktif</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">0</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
        <p class="text-3xl font-bold text-green-600 mt-1">0</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Barang Belum Diambil</p>
        <p class="text-3xl font-bold text-orange-500 mt-1">0</p>
    </div>
</div>
@endsection