<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Receipt {{ $transaction->tracking_code }}</title>
<style>
body{font-family:Arial,sans-serif;padding:32px;max-width:480px;margin:0 auto;color:#111;font-size:13px}
.header{text-align:center;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #003366}
.company{font-size:24px;font-weight:900;color:#003366;letter-spacing:-1px}
.subtitle{font-size:11px;color:#64748b;margin-top:2px}
.code{font-size:12px;font-weight:700;letter-spacing:2px;color:#003366;margin-top:8px;background:#eef2ff;padding:6px 14px;border-radius:6px;display:inline-block}
.section-title{font-size:11px;font-weight:700;color:#003366;letter-spacing:.8px;text-transform:uppercase;margin:18px 0 8px;border-top:1px dashed #cbd5e1;padding-top:14px}
table{width:100%;border-collapse:collapse;font-size:13px}
td{padding:6px 0;vertical-align:top}
td:first-child{color:#64748b;width:45%}
td:last-child{font-weight:600;color:#111;text-align:right}
.total-row td{font-size:17px;font-weight:900;color:#003366;padding-top:12px;border-top:2px solid #003366}
.badge{display:inline-block;padding:3px 10px;border-radius:50px;font-size:10px;font-weight:700}
.badge-lunas{background:#ecfdf5;color:#065f46}
.badge-belum_bayar{background:#fef2f2;color:#991b1b}
.footer{text-align:center;font-size:10px;color:#94a3b8;margin-top:28px;border-top:1px dashed #e2e8f0;padding-top:14px}
.barcode{text-align:center;margin:16px 0;font-size:10px;letter-spacing:3px;color:#003366;font-family:monospace}
</style>
</head>
<body>
<div class="header">
    <div class="company">LuxeLaundry</div>
    <div class="subtitle">Enterprise Laundry Management</div>
    <br>
    <div class="code">{{ $transaction->tracking_code }}</div>
</div>

<div class="section-title">Informasi Pelanggan</div>
<table>
    <tr><td>Nama</td><td>{{ $transaction->customer_name }}</td></tr>
    <tr><td>No. HP</td><td>{{ $transaction->phone_number }}</td></tr>
</table>

<div class="section-title">Detail Order</div>
<table>
    <tr><td>Layanan</td><td>{{ $transaction->service->name ?? '-' }}</td></tr>
    <tr><td>Berat</td><td>{{ $transaction->weight }} kg</td></tr>
    <tr><td>Harga/kg</td><td>Rp {{ number_format($transaction->price_per_kg,0,',','.') }}</td></tr>
    <tr><td>Status</td><td>{{ $transaction->status }}</td></tr>
    <tr><td>Pembayaran</td><td><span class="badge badge-{{ $transaction->payment_status }}">{{ $transaction->payment_status=='lunas'?'LUNAS':'BELUM BAYAR' }}</span></td></tr>
    <tr><td>Tanggal Masuk</td><td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
    @if($transaction->estimated_completion)
    <tr><td>Est. Selesai</td><td>{{ $transaction->estimated_completion->format('d/m/Y') }}</td></tr>
    @endif
    @if($transaction->notes)
    <tr><td>Catatan</td><td>{{ $transaction->notes }}</td></tr>
    @endif
    <tr class="total-row"><td>TOTAL</td><td>Rp {{ number_format($transaction->total_price,0,',','.') }}</td></tr>
</table>

<div class="barcode">||| {{ $transaction->tracking_code }} |||</div>

<div class="footer">
    Terima kasih telah mempercayakan laundry Anda kepada kami.<br>
    Simpan struk ini sebagai bukti pengambilan.<br>
    LuxeLaundry &copy; {{ date('Y') }}
</div>
</body>
</html>
