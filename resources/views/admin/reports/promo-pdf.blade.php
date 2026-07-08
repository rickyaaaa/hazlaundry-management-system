<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Promo {{ $month }}-{{ $year }}</title>
<style>
body{font-family:Arial,sans-serif;padding:32px;color:#111;font-size:13px}
h1{color:#003366;font-size:22px;margin-bottom:4px}
.sub{color:#64748b;font-size:12px;margin-bottom:24px}
table{width:100%;border-collapse:collapse;margin-top:16px;font-size:12px}
th{background:#003366;color:#fff;padding:8px 12px;text-align:left}
td{padding:8px 12px;border-bottom:1px solid #e2e8f0}
tr:nth-child(even) td{background:#f8fafc}
.total{font-weight:700;font-size:14px;color:#003366}
.footer{margin-top:32px;font-size:11px;color:#94a3b8;text-align:center}
</style>
</head>
<body>
<h1>HAZ Laundry – Laporan Penggunaan Promo</h1>
<div class="sub">Periode: {{ \Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }} {{ $year }} · Dicetak: {{ now()->format('d M Y H:i') }}</div>

<table>
    <thead>
        <tr><th>Kode Promo</th><th>Potongan Diskon (%)</th><th>Jumlah Dipakai</th><th>Total Diskon</th></tr>
    </thead>
    <tbody>
    @php $grandTotalDiscount=0; @endphp
    @forelse($promoUsage as $p)
    @php $grandTotalDiscount += $p->total_discount; @endphp
    <tr>
        <td>{{ $p->code }}</td>
        <td>{{ $p->percentage }}%</td>
        <td>{{ $p->total_used }}</td>
        <td>Rp {{ number_format($p->total_discount,0,',','.') }}</td>
    </tr>
    @empty
    <tr><td colspan="4">Belum ada promo yang digunakan pada periode ini</td></tr>
    @endforelse
    <tr><td colspan="2" class="total">TOTAL DIPAKAI: {{ $totalPromoUsed }}</td><td colspan="2" class="total">TOTAL DISKON: Rp {{ number_format($grandTotalDiscount,0,',','.') }}</td></tr>
    </tbody>
</table>

<div class="footer">HAZ Laundry Enterprise Laundry Management · Laporan dibuat otomatis oleh sistem</div>
</body>
</html>
