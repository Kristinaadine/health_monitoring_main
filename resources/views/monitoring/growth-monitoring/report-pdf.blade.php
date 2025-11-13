<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pertumbuhan Anak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #28a745;
            font-size: 24pt;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 10pt;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 12px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
        }
        
        .info-table td:first-child {
            font-weight: bold;
            width: 35%;
            background-color: #f8f9fa;
        }
        
        .result-box {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .result-box.normal {
            border-color: #28a745;
            background-color: #d4edda;
        }
        
        .result-box.warning {
            border-color: #ffc107;
            background-color: #fff3cd;
        }
        
        .result-box.danger {
            border-color: #dc3545;
            background-color: #f8d7da;
        }
        
        .result-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .zscore-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .category-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9pt;
        }
        
        .category-table th,
        .category-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        
        .category-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        .recommendation {
            background-color: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 12px;
            margin-top: 10px;
        }
        
        .recommendation strong {
            color: #0066cc;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>📊 LAPORAN PERTUMBUHAN ANAK</h1>
        <p>Berdasarkan Standar WHO Z-Score</p>
        <p style="font-size: 9pt; margin-top: 5px;">Digenerate pada: {{ $generatedAt }}</p>
    </div>

    {{-- Informasi Anak --}}
    <div class="section">
        <div class="section-title">👶 INFORMASI ANAK</div>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>{{ $child->name }}</td>
            </tr>
            <tr>
                <td>Usia</td>
                <td>{{ $child->age }} bulan ({{ floor($child->age / 12) }} tahun {{ $child->age % 12 }} bulan)</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>{{ $child->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Tinggi Badan</td>
                <td>{{ $child->height }} cm</td>
            </tr>
            <tr>
                <td>Berat Badan</td>
                <td>{{ $child->weight }} kg</td>
            </tr>
            <tr>
                <td>Tanggal Pengukuran</td>
                <td>{{ \Carbon\Carbon::parse($child->created_at)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
            </tr>
        </table>
    </div>

    {{-- Hasil TB/U --}}
    <div class="section">
        <div class="section-title">📏 HASIL ANALISIS TB/U (Tinggi Badan menurut Umur)</div>
        
        @if($heightHistory)
            @php
                $hZscore = $heightHistory->zscore;
                $hClass = 'normal';
                if ($hZscore < -3 || $hZscore > 3) $hClass = 'danger';
                elseif ($hZscore < -2 || $hZscore > 2) $hClass = 'warning';
            @endphp
            
            <div class="result-box {{ $hClass }}">
                <div class="result-title">{{ $heightHistory->hasil_diagnosa }}</div>
                <p><strong>Z-Score:</strong> <span class="zscore-badge">{{ number_format($hZscore, 2) }}</span></p>
                <p style="margin-top: 8px;"><strong>Interpretasi:</strong></p>
                <p>{{ $heightHistory->deskripsi_diagnosa }}</p>
            </div>
            
            <div class="recommendation">
                <strong>💡 Rekomendasi Tindakan:</strong>
                <p>{{ $heightHistory->penanganan }}</p>
            </div>
        @else
            <p>Data tidak tersedia</p>
        @endif
    </div>

    {{-- Hasil BB/U --}}
    <div class="section">
        <div class="section-title">⚖️ HASIL ANALISIS BB/U (Berat Badan menurut Umur)</div>
        
        @if($weightHistory)
            @php
                $wZscore = $weightHistory->zscore;
                $wClass = 'normal';
                if ($wZscore < -2 || $wZscore > 3) $wClass = 'danger';
                elseif ($wZscore > 1) $wClass = 'warning';
            @endphp
            
            <div class="result-box {{ $wClass }}">
                <div class="result-title">{{ $weightHistory->hasil_diagnosa }}</div>
                <p><strong>Z-Score:</strong> <span class="zscore-badge">{{ number_format($wZscore, 2) }}</span></p>
                <p style="margin-top: 8px;"><strong>Interpretasi:</strong></p>
                <p>{{ $weightHistory->deskripsi_diagnosa }}</p>
            </div>
            
            <div class="recommendation">
                <strong>💡 Rekomendasi Tindakan:</strong>
                <p>{{ $weightHistory->penanganan }}</p>
            </div>
        @else
            <p>Data tidak tersedia</p>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Tabel Referensi TB/U --}}
    <div class="section">
        <div class="section-title">📋 REFERENSI KATEGORI TB/U</div>
        <table class="category-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Kategori</th>
                    <th style="width: 15%;">Rentang Z-Score</th>
                    <th style="width: 30%;">Interpretasi</th>
                    <th style="width: 35%;">Rekomendasi / Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Sangat Pendek</strong></td>
                    <td>Z &lt; -3</td>
                    <td>Anak mengalami stunting berat</td>
                    <td>Perlu intervensi segera, konsultasi dokter gizi/pediatri</td>
                </tr>
                <tr>
                    <td><strong>Pendek</strong></td>
                    <td>-3 ≤ Z &lt; -2</td>
                    <td>Anak menunjukkan risiko stunting</td>
                    <td>Pantau pertumbuhan dan berikan asupan protein tinggi</td>
                </tr>
                <tr>
                    <td><strong>Normal</strong></td>
                    <td>-2 ≤ Z ≤ +2</td>
                    <td>Tinggi badan sesuai umur</td>
                    <td>Pertahankan pola makan bergizi dan aktivitas fisik</td>
                </tr>
                <tr>
                    <td><strong>Tinggi</strong></td>
                    <td>+2 &lt; Z ≤ +3</td>
                    <td>Tinggi badan di atas rata-rata</td>
                    <td>Awasi pertumbuhan, pastikan asupan seimbang</td>
                </tr>
                <tr>
                    <td><strong>Sangat Tinggi</strong></td>
                    <td>Z &gt; +3</td>
                    <td>Pertumbuhan berlebih</td>
                    <td>Evaluasi kemungkinan kelebihan hormon pertumbuhan atau pola makan tidak seimbang</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tabel Referensi BB/U --}}
    <div class="section">
        <div class="section-title">📋 REFERENSI KATEGORI BB/U</div>
        <table class="category-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Kategori</th>
                    <th style="width: 15%;">Rentang Z-Score</th>
                    <th style="width: 30%;">Interpretasi</th>
                    <th style="width: 35%;">Rekomendasi / Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Gizi Kurang</strong></td>
                    <td>Z &lt; -2</td>
                    <td>Anak kekurangan gizi</td>
                    <td>Tambah porsi makan bergizi tinggi protein, konsultasi ke ahli gizi</td>
                </tr>
                <tr>
                    <td><strong>Gizi Normal</strong></td>
                    <td>-2 ≤ Z ≤ +1</td>
                    <td>Berat badan sesuai umur</td>
                    <td>Pertahankan pola makan sehat dan aktivitas fisik rutin</td>
                </tr>
                <tr>
                    <td><strong>Risiko Gizi Lebih</strong></td>
                    <td>+1 &lt; Z ≤ +2</td>
                    <td>Potensi kelebihan berat badan</td>
                    <td>Kurangi makanan tinggi gula/lemak, lebihkan buah & sayur</td>
                </tr>
                <tr>
                    <td><strong>Gizi Lebih</strong></td>
                    <td>+2 &lt; Z ≤ +3</td>
                    <td>Anak mengalami overweight</td>
                    <td>Atur pola makan dan perbanyak aktivitas fisik</td>
                </tr>
                <tr>
                    <td><strong>Obesitas</strong></td>
                    <td>Z &gt; +3</td>
                    <td>Anak mengalami obesitas</td>
                    <td>Konsultasikan dengan dokter, kurangi asupan kalori tinggi</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p><strong>Catatan Penting:</strong></p>
        <p>Laporan ini dibuat berdasarkan standar WHO Z-Score untuk monitoring pertumbuhan anak.</p>
        <p>Hasil ini bersifat informatif dan sebaiknya dikonsultasikan dengan tenaga kesehatan profesional.</p>
        <p style="margin-top: 10px;">© {{ date('Y') }} Healthy Monitoring System</p>
    </div>
</body>
</html>
