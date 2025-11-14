<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perkembangan Pertumbuhan - {{ $childName }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #55BF3B;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #55BF3B;
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #2196F3;
            font-size: 14px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
        }
        .info-value {
            display: table-cell;
            width: 60%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background: #55BF3B;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success {
            background: #55BF3B;
            color: white;
        }
        .badge-warning {
            background: #FFC107;
            color: #333;
        }
        .badge-danger {
            background: #F44336;
            color: white;
        }
        .summary-box {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 2px solid #55BF3B;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #55BF3B;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        .chart-placeholder {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border: 2px dashed #ddd;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>📊 LAPORAN PERKEMBANGAN PERTUMBUHAN ANAK</h1>
        <p>Berdasarkan Standar WHO Z-Score</p>
        <p>Tanggal Cetak: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
    </div>

    {{-- Informasi Anak --}}
    <div class="info-box">
        <h3>👶 Informasi Anak</h3>
        <div class="info-row">
            <div class="info-label">Nama:</div>
            <div class="info-value">{{ $childName }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">ID Pengenal:</div>
            <div class="info-value">{{ $data->first()->child_id ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jenis Kelamin:</div>
            <div class="info-value">{{ $data->first()->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total Pemeriksaan:</div>
            <div class="info-value">{{ $data->count() }} kali</div>
        </div>
        <div class="info-row">
            <div class="info-label">Periode:</div>
            <div class="info-value">
                {{ $data->last()->created_at->locale('id')->isoFormat('D MMMM YYYY') }} - 
                {{ $data->first()->created_at->locale('id')->isoFormat('D MMMM YYYY') }}
            </div>
        </div>
    </div>

    {{-- Ringkasan Terbaru --}}
    @if($data->count() > 0)
    @php
        $latest = $data->first();
        $latestHeight = $latest->history->where('type', 'LH')->first();
        $latestWeight = $latest->history->where('type', 'W')->first();
    @endphp
    <div class="summary-box">
        <h3>📈 Ringkasan Data Terbaru ({{ $latest->age }} bulan)</h3>
        <table style="margin: 0;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>🟢 Tinggi Badan (TB/U)</strong><br>
                    Z-Score: <strong>{{ $latestHeight ? number_format($latestHeight->zscore, 2) : 'N/A' }}</strong><br>
                    Status: 
                    @if($latestHeight && $latestHeight->zscore)
                        @if($latestHeight->zscore >= -2 && $latestHeight->zscore <= 2)
                            <span class="badge badge-success">Normal ✓</span>
                        @elseif($latestHeight->zscore >= -3 && $latestHeight->zscore < -2 || $latestHeight->zscore > 2 && $latestHeight->zscore <= 3)
                            <span class="badge badge-warning">Waspada !</span>
                        @else
                            <span class="badge badge-danger">Perlu Perhatian !!</span>
                        @endif
                    @endif
                </td>
                <td style="border: none; width: 50%;">
                    <strong>🔵 Berat Badan (BB/U)</strong><br>
                    Z-Score: <strong>{{ $latestWeight ? number_format($latestWeight->zscore, 2) : 'N/A' }}</strong><br>
                    Status: 
                    @if($latestWeight && $latestWeight->zscore)
                        @if($latestWeight->zscore >= -2 && $latestWeight->zscore <= 2)
                            <span class="badge badge-success">Normal ✓</span>
                        @elseif($latestWeight->zscore >= -3 && $latestWeight->zscore < -2 || $latestWeight->zscore > 2 && $latestWeight->zscore <= 3)
                            <span class="badge badge-warning">Waspada !</span>
                        @else
                            <span class="badge badge-danger">Perlu Perhatian !!</span>
                        @endif
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Riwayat Pemeriksaan --}}
    <h3 style="color: #55BF3B; margin-top: 30px;">📋 Riwayat Pemeriksaan Lengkap</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Usia<br>(bulan)</th>
                <th>Tinggi<br>(cm)</th>
                <th>Berat<br>(kg)</th>
                <th>Z-Score<br>TB/U</th>
                <th>Status<br>TB/U</th>
                <th>Z-Score<br>BB/U</th>
                <th>Status<br>BB/U</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->reverse() as $index => $record)
            @php
                $heightHistory = $record->history->where('type', 'LH')->first();
                $weightHistory = $record->history->where('type', 'W')->first();
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $record->created_at->locale('id')->isoFormat('D MMM YYYY') }}</td>
                <td style="text-align: center;">{{ $record->age }}</td>
                <td style="text-align: center;">{{ $record->height }}</td>
                <td style="text-align: center;">{{ $record->weight }}</td>
                <td style="text-align: center;">
                    {{ $heightHistory ? number_format($heightHistory->zscore, 2) : 'N/A' }}
                </td>
                <td>
                    @if($heightHistory && $heightHistory->zscore)
                        @if($heightHistory->zscore >= -2 && $heightHistory->zscore <= 2)
                            <span class="badge badge-success">Normal</span>
                        @elseif($heightHistory->zscore >= -3 && $heightHistory->zscore < -2 || $heightHistory->zscore > 2 && $heightHistory->zscore <= 3)
                            <span class="badge badge-warning">Waspada</span>
                        @else
                            <span class="badge badge-danger">Perlu Perhatian</span>
                        @endif
                    @endif
                </td>
                <td style="text-align: center;">
                    {{ $weightHistory ? number_format($weightHistory->zscore, 2) : 'N/A' }}
                </td>
                <td>
                    @if($weightHistory && $weightHistory->zscore)
                        @if($weightHistory->zscore >= -2 && $weightHistory->zscore <= 2)
                            <span class="badge badge-success">Normal</span>
                        @elseif($weightHistory->zscore >= -3 && $weightHistory->zscore < -2 || $weightHistory->zscore > 2 && $weightHistory->zscore <= 3)
                            <span class="badge badge-warning">Waspada</span>
                        @else
                            <span class="badge badge-danger">Perlu Perhatian</span>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Interpretasi --}}
    <div class="info-box" style="border-left-color: #FFC107;">
        <h3>💡 Interpretasi & Rekomendasi</h3>
        <p><strong>Kategori Z-Score WHO:</strong></p>
        <ul style="margin: 5px 0;">
            <li><strong>Normal:</strong> Z-Score antara -2 dan +2 (pertumbuhan sesuai standar)</li>
            <li><strong>Waspada:</strong> Z-Score antara -3 dan -2 atau +2 dan +3 (perlu perhatian)</li>
            <li><strong>Perlu Perhatian:</strong> Z-Score < -3 atau > +3 (perlu konsultasi medis)</li>
        </ul>
        
        @if($latestHeight && $latestWeight)
        <p style="margin-top: 15px;"><strong>Kesimpulan:</strong></p>
        <p>
            Berdasarkan pemeriksaan terakhir pada usia {{ $latest->age }} bulan, 
            @if($latestHeight->zscore >= -2 && $latestHeight->zscore <= 2 && $latestWeight->zscore >= -2 && $latestWeight->zscore <= 2)
                pertumbuhan anak berada dalam kategori <strong>NORMAL</strong>. Pertahankan pola makan sehat dan pemeriksaan rutin.
            @else
                @if($latestHeight->zscore < -2 || $latestHeight->zscore > 2)
                    tinggi badan anak memerlukan perhatian khusus.
                @endif
                @if($latestWeight->zscore < -2 || $latestWeight->zscore > 2)
                    Berat badan anak memerlukan perhatian khusus.
                @endif
                Disarankan untuk berkonsultasi dengan tenaga kesehatan untuk evaluasi lebih lanjut.
            @endif
        </p>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            <strong>Catatan:</strong> Laporan ini dibuat secara otomatis oleh sistem Growth Monitoring for Stunting.<br>
            Data berdasarkan standar WHO Z-Score. Untuk interpretasi lebih lanjut, konsultasikan dengan tenaga kesehatan profesional.
        </p>
        <p style="margin-top: 10px;">
            © {{ date('Y') }} Growth Monitoring System | Dicetak: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </p>
    </div>
</body>
</html>
