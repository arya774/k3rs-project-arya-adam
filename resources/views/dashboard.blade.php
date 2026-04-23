<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Inspeksi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- STATUS -->
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                DASHBOARD AKTIF ✔ DATA TERLOAD
            </div>

            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <div class="bg-white shadow rounded p-4 text-center">
                    <div class="text-gray-600">Total</div>
                    <div class="text-2xl font-bold">{{ $total ?? 0 }}</div>
                </div>

                <div class="bg-green-500 text-white shadow rounded p-4 text-center">
                    <div>YA</div>
                    <div class="text-2xl font-bold">{{ $ya ?? 0 }}</div>
                </div>

                <div class="bg-red-500 text-white shadow rounded p-4 text-center">
                    <div>TIDAK</div>
                    <div class="text-2xl font-bold">{{ $tidak ?? 0 }}</div>
                </div>

                <div class="bg-yellow-400 shadow rounded p-4 text-center">
                    <div>%</div>
                    <div class="text-2xl font-bold">
                        {{ number_format($persentase ?? 0, 1) }}%
                    </div>
                </div>

            </div>

            <!-- BUTTON ACTION -->
            <div class="flex gap-3 mb-6">

                <a href="{{ route('inspeksi.cetak', $inspeksi->id ?? 0) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded">
                    Cetak PDF
                </a>

                <a href="{{ route('inspeksi.export.excel') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded">
                    Cetak Excel
                </a>

            </div>

            <!-- TABLE DATA INSPEKSI -->
            <div class="bg-white shadow rounded p-4">

                <h3 class="font-bold mb-4">Data Inspeksi</h3>

                <table class="w-full border">

                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">ID</th>
                            <th class="p-2 border">Ruangan</th>
                            <th class="p-2 border">Tanggal</th>
                            <th class="p-2 border">Petugas</th>
                            <th class="p-2 border">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($listInspeksi ?? [] as $data)

                            <tr>
                                <td class="border p-2">{{ $data->id }}</td>
                                <td class="border p-2">{{ $data->ruangan }}</td>
                                <td class="border p-2">{{ $data->tanggal }}</td>
                                <td class="border p-2">{{ $data->nama_petugas_k3rs }}</td>

                                <td class="border p-2 flex gap-2">

                                    <!-- EDIT -->
                                    <a href="{{ route('inspeksi.edit', $data->id) }}"
                                       class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                        Edit
                                    </a>

                                    <!-- DELETE -->
                                    <form action="{{ route('inspeksi.delete', $data->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus data?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                            Hapus
                                        </button>

                                    </form>

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center p-4">
                                    Tidak ada data inspeksi
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-app-layout>