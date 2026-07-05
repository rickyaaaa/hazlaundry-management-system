<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembaruan Status Laundry Anda</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #003366; padding: 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px;">HAZ Laundry</h1>
                            <p style="color: #cbd5e1; margin: 8px 0 0 0; font-size: 14px;">Precision Laundry & Dry Cleaning Services</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px; color: #334155; line-height: 1.6;">
                            <h2 style="color: #003366; margin: 0 0 16px 0; font-size: 20px; font-weight: 700;">Halo, {{ $transaction->customer_name }}!</h2>
                            <p style="margin: 0 0 24px 0; font-size: 15px; color: #475569;">Status pesanan laundry Anda saat ini adalah: <strong>{{ $transaction->status }}</strong>. Berikut adalah rincian informasi pesanan Anda:</p>
                            
                            <!-- Order Info Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 12px; padding: 24px; border: 1px solid #f1f5f9; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding-bottom: 12px; font-size: 14px; font-weight: 600; color: #64748b; width: 35%;">Kode Tracking:</td>
                                    <td style="padding-bottom: 12px; font-size: 14px; font-weight: 700; color: #003366; font-family: monospace;">{{ $transaction->tracking_code }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 12px; font-size: 14px; font-weight: 600; color: #64748b;">Jenis Layanan:</td>
                                    <td style="padding-bottom: 12px; font-size: 14px; font-weight: 600; color: #334155;">{{ $transaction->service->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 12px; font-size: 14px; font-weight: 600; color: #64748b;">Status Cucian:</td>
                                    <td style="padding-bottom: 12px; font-size: 14px; font-weight: 700; color: #10b981;">{{ $transaction->status }}</td>
                                </tr>
                                
                                @if($transaction->delivery_type === 'pickup_delivery')
                                    @if($transaction->pickup_time)
                                        <tr>
                                            <td style="padding-bottom: 12px; font-size: 14px; font-weight: 600; color: #64748b;">Jadwal Penjemputan:</td>
                                            <td style="padding-bottom: 12px; font-size: 14px; font-weight: 600; color: #334155;">{{ $transaction->pickup_time->format('d-m-Y H:i') }}</td>
                                        </tr>
                                    @endif
                                    @if($transaction->address)
                                        <tr>
                                            <td style="font-size: 14px; font-weight: 600; color: #64748b; vertical-align: top;">Alamat Antar-Jemput:</td>
                                            <td style="font-size: 14px; font-weight: 600; color: #334155; line-height: 1.4;">{{ $transaction->address }}</td>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                            
                            <!-- QRIS Payment -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 12px; padding: 24px; border: 1px solid #f1f5f9; margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0 0 16px 0; font-size: 14px; color: #334155; font-weight: 600; line-height: 1.5;">Silakan scan QRIS untuk melakukan pembayaran, tolong abaikan pesan ini jika sudah melakukan pembayaran</p>
                                        @if(file_exists(public_path('images/qris.jpeg')))
                                            <img src="{{ $message->embed(public_path('images/qris.jpeg')) }}" alt="QRIS HAZ Laundry" width="220" style="max-width: 220px; width: 100%; height: auto; border-radius: 12px; border: 1px solid #e2e8f0;">
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #64748b;">Anda dapat terus memantau pengerjaan pakaian Anda secara langsung dengan mengklik tombol di bawah ini:</p>

                            <!-- Action Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 32px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('tracking.index', ['tracking_code' => $transaction->tracking_code]) }}" style="background-color: #003366; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 6px rgba(0, 51, 102, 0.15);">Lacak Cucian Anda</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 24px;">
                            <p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">Jika Anda memiliki pertanyaan, silakan hubungi tim dukungan kami di support@hazlaundry.com</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px; text-align: center; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">© {{ date('Y') }} HAZ Laundry. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
