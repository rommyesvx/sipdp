<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;   

class EncryptExistingUsers extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:encrypt-existing-users';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Encrypts existing plain-text no_hp in the users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting encryption of existing user phone numbers...');

        // Ambil data mentah (raw) menggunakan Query Builder untuk menghindari casting Eloquent
        $usersToEncrypt = DB::table('users')
            ->whereNotNull('no_hp')
            ->where('no_hp', 'NOT LIKE', 'ey%') // Filter untuk data yang belum terenkripsi
            ->get(['id', 'no_hp']);

        if ($usersToEncrypt->isEmpty()) {
            $this->info('No unencrypted phone numbers found. All done!');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($usersToEncrypt->count());
        $progressBar->start();

        foreach ($usersToEncrypt as $user) {
            // Enkripsi nilai no_hp secara manual
            $encryptedPhone = Crypt::encryptString($user->no_hp);

            // Update baris di database secara langsung menggunakan Query Builder
            DB::table('users')
                ->where('id', $user->id)
                ->update(['no_hp' => $encryptedPhone]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\nEncryption complete for " . $usersToEncrypt->count() . " users.");
        return 0;
    }
}