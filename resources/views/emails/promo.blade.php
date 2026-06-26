<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $promo->title }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #003366; padding: 32px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px;">HAZ Laundry</h1>
                            <p style="color: #cbd5e1; margin: 6px 0 0 0; font-size: 13px;">Precision Laundry & Dry Cleaning Services</p>
                        </td>
                    </tr>
                    
                    <!-- Promo Banner Image -->
                    @if($promo->image_url)
                    <tr>
                        <td align="center" style="background-color: #ffffff; padding: 0;">
                            <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" style="width: 100%; max-width: 600px; height: auto; display: block; border-bottom: 1px solid #f1f5f9;">
                        </td>
                    </tr>
                    @endif

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px; color: #334155; line-height: 1.6;">
                            <span style="display: inline-block; padding: 4px 12px; background-color: #f97316; color: #ffffff; font-size: 10px; font-weight: bold; text-transform: uppercase; border-radius: 50px; margin-bottom: 16px; letter-spacing: 0.8px;">PROMO SPESIAL</span>
                            <h2 style="color: #003366; margin: 0 0 16px 0; font-size: 22px; font-weight: 800; line-height: 1.3;">{{ $promo->title }}</h2>
                            
                            <p style="margin: 0 0 28px 0; font-size: 15px; color: #475569; white-space: pre-line;">{{ $promo->description }}</p>
                            
                            <!-- Action Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('tracking.pickup.form') }}" style="background-color: #003366; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(0, 51, 102, 0.2);">Pesan Laundry Sekarang</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 32px 0 24px 0;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8; text-align: center;">Anda menerima email ini karena Anda berlangganan info promo menarik dari HAZ Laundry.</p>
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
