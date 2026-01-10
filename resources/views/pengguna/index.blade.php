@extends('layouts.main')
@section('title', 'KopsisApp - Pengguna')
@section('content')
    <div class="px-8 py-6">
        <div class="flex flex-col space-y-4">
            <div class="flex items-center text-sm text-gray-500">
                <span>Manajemen Pengguna</span>
                <svg class="h-4 w-4 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>Pengguna</span>
            </div>
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-900 m-0">Pengguna</h2>
                <button
                    class="hidden md:flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    onclick="window.location.href='{{ route('pengguna.create') }}'">
                    Tambah Pengguna
                </button>
            </div>
        </div>

        <!-- FAB Container -->
        <div class="md:hidden fab fixed bottom-6 right-6 flex flex-col items-end gap-3">
            <div class="fab-items flex flex-col-reverse items-end gap-3">
                <div class="fab-item-wrapper hidden-space" data-idx="0">
                    <span class="fab-label" onclick="window.location.href='{{ route('pengguna.create') }}'">Tambah
                        Pengguna</span>
                    <button
                        class="fab-item w-14 h-14 rounded-full flex items-center justify-center text-white font-semibold shadow-lg bg-gradient-to-br from-purple-500 to-pink-500"
                        onclick="window.location.href='{{ route('pengguna.create') }}'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 640 640" class="w-6 h-6">
                            <path
                                d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- FAB Button-->
            <button id="fabMain"
                class="fab-main w-14 h-14 rounded-full btn-lg shadow-lg flex items-center justify-center transition-all bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white"
                aria-expanded="false" aria-label="Open FAB" title="Open FAB">
                <span id="iconX" class="fab-icon text-xl visible" role="img" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 640 640" class="w-6 h-6">
                        <path
                            d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z" />
                    </svg>
                </span>
            </button>
        </div>

        <hr class="my-6 border-gray-200">
        <x-table :data-table="[
        'Nama' => 'name', 
        'Email' => 'email', 
        'Diverifikasi' => 'email_verified_at', 
        'Dibuat Pada' => 'created_at',    
        ]" data-url="{{ route('api.pengguna.index') }}">
        </x-table>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/fab.js') }}"></script>
@endsection