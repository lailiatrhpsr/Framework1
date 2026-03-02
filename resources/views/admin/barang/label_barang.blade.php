<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Barang</title>
    <style>
        @page { margin: 0; }

        body { 
            margin: 0; 
            padding: 0; 
            font-family: Arial, sans-serif; 
            font-size: 10px;
        }

        /* Ukuran Kertas TnJ 108: 180mm x 114mm */
        .container {
            width: 180mm;
            height: 114mm;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
            margin-left: 2mm; 
            margin-top: 2mm;
        }

        td {
            width: 38mm;   /* lebar label */
            height: 18mm;  /* tinggi label */
            vertical-align: middle;
            text-align: center;
            border: 0.1pt solid transparent; 
            padding: 0;
            margin: 0;
        }

        .label-box {
            width: 34mm;
            margin: auto;
            font-size: 10px;
        }

        .nama { font-weight: bold; font-size: 9px; }
        .harga { font-size: 12px; font-weight: bold; display: block; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="container">
        <table>
            @php
                $startX = $x;
                $startY = $y;
                $currentIndex = (($startY - 1) * 5) + ($startX - 1);
            @endphp

            @for ($row = 0; $row < 8; $row++)
                <tr>
                    @for ($col = 0; $col < 5; $col++)
                        @php
                            $index = $row * 5 + $col;
                            $barangIndex = $index - $currentIndex;
                        @endphp
                        <td>
                            @if(isset($barang[$barangIndex]))
                                <div class="label-box">
                                    <span class="nama">{{ $barang[$barangIndex]->nama_barang }}</span><br>
                                    <span class="harga">Rp {{ number_format($barang[$barangIndex]->harga,0,',','.') }}</span>
                                </div>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>
</body>
</html>
