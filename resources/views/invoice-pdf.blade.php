<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoiceNumber }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans p-4">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <div class="logo">
                <img src="data:image/png;base64,{{ $base64Logo }}" alt="Logo" width="100">
            </div>
            <div class="text-right">
                <p class="text-xl font-black uppercase">Wawan Service</p>
                <div class="text-sm font-thin">
                    <p>Jl. Kenanga No.33, Dadaprejo, Kec. Junrejo, Kota Batu, Jawa Timur 65233</p>
                    <p>085210355172</p>
                    <p>wawanservice.id@gmail.com</p>
                    <p>NPWP: 12.938.870.8-628.000</p>
                </div>
            </div>
        </div>

        <hr class="my-4 border-t-4 border-black">

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">FAKTUR</h2>
        </div>

        <div class="flex justify-between mb-6">
            <div>
                <p class="font-bold">Tagihan kepada:</p>
                <p class="font-thin">{{ $receiver }}</p>
            </div>
            <div class="text-right text-sm">
                <p class="font-bold text-2xl">{{ $invoiceNumber }}</p>
                <p>{{ date($date) }}</p>
            </div>
        </div>

        <table class="min-w-full table-auto border-collapse border text-sm">
            <thead>
                <tr class="bg-red-400 text-white">
                    <th class="px-2 py-1 border text-left">Nomor</th>
                    <th class="px-2 py-1 border text-left">Nama Produk</th>
                    <th class="px-2 py-1 border text-right">Qty</th>
                    <th class="px-2 py-1 border text-right">Harga</th>
                    <th class="px-2 py-1 border text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $totalJumlah = 0; $nomor = 1; @endphp
                @foreach ($invoiceData as $item)
                    <tr class="font-semibold">
                        <td class="px-2 py-1 border">{{ $nomor++ }}</td>
                        <td class="px-2 py-1 border">{{ $item['product_name'] }}</td>
                        <td class="px-2 py-1 border text-right">{{ $item['qty'] }}<br><span class="italic font-thin text-sm">( {{ $item['unit']}} )</span></td>
                        <td class="px-2 py-1 border text-right">{{ number_format($item['price'], 2) }}</td>
                        <td class="px-2 py-1 border text-right">{{ number_format($item['qty'] * $item['price'], 2) }}</td>
                    </tr>
                    @php
                        $totalPerItem = $item['qty'] * $item['price'];
                        $totalJumlah += $totalPerItem;
                    @endphp
                @endforeach
            </tbody>
        </table>

        @php
            $totalKeseluruhan = $totalJumlah;
        @endphp

        <div class="flex mt-4 w-full gap-5 text-sm">
            <div class="border-t border-b border-red-400 w-full">
                <div class="px-2">
                    Keterangan: <br>{!! $note !!}
                </div>
            </div>
            <div class="border-t border-b border-red-400 w-full">
                <div class="flex justify-between px-2">
                    <p class="text-left">Total:</p>
                    <p class="text-right">IDR {{ number_format($totalJumlah, 2) }}</p>
                </div>
                <div class="flex justify-between px-2 bg-red-500 text-white">
                    <p class="text-left">Total keseluruhan:</p>
                    <p class="text-right">IDR {{ number_format($totalKeseluruhan, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 text-sm w-full">
            <div class="flex items-end">
                <div>
                    <p class="font-bold">Tanda Tangan</p>
                    <img src="data:image/png;base64,{{ $base64Signature }}" alt="Logo" width="100" class="py-5">
                    <p>Wawan Sofyan</p>
                </div>
            </div>

            <div class="w-full mt-10 flex border px-2 py-2 bg-blue-500 text-white">
                <div class="w-1/2">
                    <h2 class="font-bold">Detail perbankan</h2>
                    <p>REK BCA: 0191116312 a/n: WAWAN SOFYAN</p>
                    <p>REK BCA: 3151336114 a/n: MOH. RAFLY</p>
                </div>
                <div class="w-1/2">
                    <h2 class="font-bold">Rincian lainnya</h2>
                    <p>Dana: -</p>
                    <p>Gopay: -</p>
                    <p>OVO: -</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
