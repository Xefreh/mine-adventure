<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeAdmin extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Make a user admin';

    public function handle(): void
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->is_admin = true;
            $user->save();
            $this->info("User {$email} is now an admin!");
        } else {
            $this->error("User not found!");
        }
    }
}
