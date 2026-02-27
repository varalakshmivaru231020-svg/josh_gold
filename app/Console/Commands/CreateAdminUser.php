<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $existingUser = DB::table('admins')->where('email', $email)->first();

        if ($existingUser) {
            DB::table('admins')
                ->where('email', $email)
                ->update([
                    'password' => Hash::make($password),
                    'updated_at' => now()
                ]);
            
            $this->info("Admin user {$email} updated successfully with new password.");
        } else {
            DB::table('admins')->insert([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->info("Admin user {$email} created successfully.");
        }

        return 0;
    }
}