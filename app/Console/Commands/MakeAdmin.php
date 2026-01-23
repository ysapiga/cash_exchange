<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin
                            {name=Admin}
                            {email=admin@example.com}
                            {password=123123q}';

    protected $description = 'Створює адмін-юзера';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (User::where('email', $email)->exists()) {
            $this->error("Користувач з email {$email} вже існує!");
            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            // 'is_admin' => true, // розкоментуй, якщо у тебе є поле is_admin
        ]);

        $this->info("Адмін-юзер {$name} створений! Email: {$email}");
    }
}
