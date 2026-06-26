<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusLaundryMail;
use App\Models\Transaction;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Kirim email uji coba ke alamat tertentu untuk mengetes konfigurasi SMTP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Mencoba mengirim email uji coba ke: {$email}...");

        // Buat dummy/mock transaction untuk keperluan testing email
        $transaction = new Transaction([
            'tracking_code' => 'TEST12345',
            'customer_name' => 'Pelanggan Uji Coba',
            'phone_number' => '081234567890',
            'address' => 'Jl. Test No. 123',
            'status' => 'Selesai',
            'payment_status' => 'lunas',
            'weight' => 5,
            'price_per_kg' => 10000,
            'total_price' => 50000,
            'email' => $email,
            'delivery_type' => 'pickup_delivery',
        ]);

        try {
            Mail::to($email)->send(new StatusLaundryMail($transaction));
            $this->info("Sukses! Email berhasil dikirim ke {$email}!");
            $this->info("Silakan periksa kotak masuk atau spam di email penerima.");
        } catch (\Exception $e) {
            $this->error("Gagal mengirim email!");
            $this->error("Pesan Error: " . $e->getMessage());
            
            $this->comment("\nTips Troubleshooting:");
            $this->comment("1. Pastikan MAIL_HOST, MAIL_PORT, MAIL_USERNAME, dan MAIL_PASSWORD di .env sudah benar.");
            $this->comment("2. Jika menggunakan Gmail, pastikan Anda menggunakan 'Sandi Aplikasi' (App Password), bukan password utama akun Gmail Anda.");
            $this->comment("3. Pastikan port (misal 587 atau 465) tidak diblokir oleh provider internet Anda.");
            $this->comment("4. Jalankan 'php artisan config:clear' setelah setiap kali mengubah file .env agar Laravel membaca perubahan tersebut.");
        }
    }
}
