<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian Kinerja Guru - {{ $penilaian->user->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h3 {
            margin: 3px 0;
            font-size: 12px;
            font-weight: normal;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #555;
        }
        .section-title {
            font-weight: bold;
            font-size: 11px;
            background-color: #f2f2f2;
            padding: 4px 6px;
            margin-top: 10px;
            margin-bottom: 6px;
            border-left: 3px solid #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table-data th, .table-data td {
            border: 1px solid #777;
            padding: 5px 6px;
            vertical-align: top;
        }
        .table-data th {
            background-color: #f8f9fa;
            text-align: center;
            font-weight: bold;
            font-size: 10.5px;
        }
        .table-identity td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            font-size: 10.5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            color: #fff;
        }
        .badge-success { background-color: #28a745; }
        .badge-info { background-color: #17a2b8; }
        .badge-primary { background-color: #007bff; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; }
        .signatures {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .sign-space {
            height: 55px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Evaluasi Penilaian Kinerja Guru</h2>
        <h3>Metode Behaviorally Anchored Rating Scales (BARS)</h3>
        <p>Tahun Penilaian: {{ $penilaian->tahun_penilaian->tahun_penilaian ?? '-' }} | Periode: {{ $penilaian->periode_penilaian->periode_penilaian ?? '-' }}</p>
    </div>

    <!-- Data Identitas Pegawai & Penilai -->
    <div class="section-title">I. DATA PEGAWAI & PEJABAT PENILAI</div>
    <table class="table-identity">
        <tr>
            <th colspan="2" style="background:#e9ecef; width:50%; text-align:left; font-weight:bold; padding:4px 6px;">PEGAWAI YANG DINILAI</th>
            <th colspan="2" style="background:#e9ecef; width:50%; text-align:left; font-weight:bold; padding:4px 6px;">PEJABAT PENILAI KINERJA</th>
        </tr>
        <tr>
            <td style="width:18%;">Nama</td>
            <td style="width:32%;" class="fw-bold">{{ $penilaian->user->nama ?? '-' }}</td>
            <td style="width:18%;">Nama</td>
            <td style="width:32%;" class="fw-bold">{{ $penilaian->penilai->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIP / Username</td>
            <td>{{ $penilaian->user->username ?? '-' }}</td>
            <td>NIP / Username</td>
            <td>{{ $penilaian->penilai->username ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pangkat / Gol.</td>
            <td>{{ $penilaian->user->pangkat ?? '-' }}</td>
            <td>Pangkat / Gol.</td>
            <td>{{ $penilaian->penilai->pangkat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>{{ $penilaian->user->jabatan ?? '-' }}</td>
            <td>Jabatan</td>
            <td>{{ $penilaian->penilai->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>{{ $penilaian->user->unit_kerja ?? '-' }}</td>
            <td>Unit Kerja</td>
            <td>{{ $penilaian->penilai->unit_kerja ?? '-' }}</td>
        </tr>
    </table>

    <!-- Rincian Evaluasi Kinerja (BARS) -->
    <div class="section-title">II. HASIL PENILAIAN PERILAKU KERJA (METODE BARS)</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 24%;">Kriteria Penilaian</th>
                <th style="width: 10%;">Bobot</th>
                <th style="width: 12%;">Skor Kriteria<br><small>(Skala 1 - 5)</small></th>
                <th style="width: 25%;">Ekspektasi Pimpinan</th>
                <th style="width: 25%;">Umpan Balik Berkelanjutan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kriteria_data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $item['kriteria'] }}</td>
                    <td class="text-center">{{ ($item['bobot'] * 100) }}%</td>
                    <td class="text-center fw-bold" style="font-size:11.5px; color:#0056b3;">
                        {{ number_format($item['skor'], 2) }}
                    </td>
                    <td>{{ $item['ekspektasi'] }}</td>
                    <td>{{ $item['umpan_balik'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Ringkasan Nilai Akhir & Predikat -->
    <div class="section-title">III. KESIMPULAN PENILAIAN KINERJA</div>
    <table class="table-data">
        <tr>
            <td style="width: 30%;" class="fw-bold">Nilai Akhir Kumulatif (NAK)</td>
            <td style="width: 70%;" class="fw-bold" style="font-size:12px;">
                {{ number_format($penilaian->nilai_akhir, 2) }} / 5.00
            </td>
        </tr>
        <tr>
            <td class="fw-bold">Predikat Kinerja</td>
            <td class="fw-bold">
                {{ $penilaian->predikat }}
            </td>
        </tr>
        <tr>
            <td class="fw-bold">Rating Perilaku Kerja</td>
            <td style="text-transform: capitalize;">
                {{ $penilaian->rating_perilaku_kerja }}
            </td>
        </tr>
        <tr>
            <td class="fw-bold">Status Penilaian</td>
            <td>{{ $penilaian->status }}</td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <table class="signatures">
        <tr>
            <td>
                Pegawai yang Dinilai,
                <div class="sign-space"></div>
                <span class="fw-bold" style="text-decoration: underline;">{{ $penilaian->user->nama ?? '-' }}</span><br>
                <span>NIP. {{ $penilaian->user->username ?? '-' }}</span>
            </td>
            <td>
                {{ $penilaian->user->unit_kerja ?? 'Tempat' }}, {{ $penilaian->updated_at ? $penilaian->updated_at->format('d F Y') : date('d F Y') }}<br>
                Pejabat Penilai Kinerja,
                <div class="sign-space"></div>
                <span class="fw-bold" style="text-decoration: underline;">{{ $penilaian->penilai->nama ?? '-' }}</span><br>
                <span>NIP. {{ $penilaian->penilai->username ?? '-' }}</span>
            </td>
        </tr>
    </table>
</body>
</html>
