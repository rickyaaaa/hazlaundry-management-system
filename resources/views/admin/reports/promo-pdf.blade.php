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
        <tr><th>Nama Promo</th><th>Jumlah Digunakan</th><th>Total Revenue</th></tr>
    </thead>
    <tbody>
    @php $grandTotal=0; @endphp
    @forelse($promoUsage as $p)
    @php $grandTotal += $p->revenue; @endphp
    <tr>
        <td>{{ $p->promo_used }}</td>
        <td>{{ $p->total_used }}</td>
        <td>Rp {{ number_format($p->revenue,0,',','.') }}</td>
    </tr>
    @empty
    <tr><td colspan="3">Belum ada promo yang digunakan pada periode ini</td></tr>
    @endforelse
    <tr><td colspan="2" class="total">TOTAL TRANSAKSI PAKAI PROMO</td><td class="total">{{ $totalPromoUsed }}</td></tr>
    </tbody>
</table>

<div class="footer">HAZ Laundry Enterprise Laundry Management · Laporan dibuat otomatis oleh sistem</div>
</body>
</html>
