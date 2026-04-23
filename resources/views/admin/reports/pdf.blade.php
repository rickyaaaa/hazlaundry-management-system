<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Laundry {{ $year }}</title>
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
<h1>LuxeLaundry – Laporan Tahunan</h1>
<div class="sub">Tahun: {{ $year }} · Dicetak: {{ now()->format('d M Y H:i') }}</div>

<table>
    <thead>
        <tr><th>Bulan</th><th>Total Order</th><th>Revenue</th></tr>
    </thead>
    <tbody>
    @php $months=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; $grandTotal=0; @endphp
    @for($m=1;$m<=12;$m++)
    @php $d=$monthlyRevenue->firstWhere('month',$m); $rev=$d?$d->revenue:0; $ord=$d?$d->orders:0; $grandTotal+=$rev; @endphp
    <tr>
        <td>{{ $months[$m-1] }} {{ $year }}</td>
        <td>{{ $ord }}</td>
        <td>Rp {{ number_format($rev,0,',','.') }}</td>
    </tr>
    @endfor
    <tr><td colspan="2" class="total">TOTAL REVENUE {{ $year }}</td><td class="total">Rp {{ number_format($grandTotal,0,',','.') }}</td></tr>
    </tbody>
</table>

<h2 style="color:#003366;font-size:16px;margin-top:28px">Distribusi Status</h2>
<table>
    <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
    <tbody>
    @foreach($statusReport as $s)
    <tr><td>{{ $s->status }}</td><td>{{ $s->total }}</td></tr>
    @endforeach
    </tbody>
</table>

<div class="footer">LuxeLaundry Enterprise Laundry Management · Laporan dibuat otomatis oleh sistem</div>
</body>
</html>
