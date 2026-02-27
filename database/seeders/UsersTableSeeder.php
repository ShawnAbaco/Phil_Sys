<?php
// database/seeders/UsersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Joy E. Llido',
                'email' => 'jllido.psa@nationalid.gov.ph',
                'password' => Hash::make('Llido2025'),
                'full_name' => 'Joy E. Llido',
                'designation' => 'Registration Kit Operator',
                'window_num' => 1,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'jllido.psa',
                'password_hashed' => 'Llido2025',
            ],
            [
                'name' => 'Lourdes Joy Fabela',
                'email' => 'lfabela.psa@nationalid.gov.ph',
                'password' => Hash::make('Fabela2025'),
                'full_name' => 'Lourdes Joy Fabela',
                'designation' => 'Registration Kit Operator',
                'window_num' => 3,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'lfabela.psa',
                'password_hashed' => 'Fabela2025',
            ],
            [
                'name' => 'screener',
                'email' => 'screener@nationalid.gov.ph',
                'password' => Hash::make('screener'),
                'full_name' => 'screener',
                'designation' => 'screener',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'screener',
                'password_hashed' => 'screener',
            ],
            [
                'name' => 'KHIMBOY VERSON',
                'email' => 'kverson.psa@nationalid.gov.ph',
                'password' => Hash::make('Verson2025'),
                'full_name' => 'KHIMBOY VERSON',
                'designation' => 'Registration Kit Operator',
                'window_num' => 2,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'kverson.psa',
                'password_hashed' => 'Verson2025',
            ],
            [
                'name' => 'CATHERINE CAWALING',
                'email' => 'ccawaling.psa@nationalid.gov.ph',
                'password' => Hash::make('Cawaling2025'),
                'full_name' => 'CATHERINE CAWALING',
                'designation' => 'Registration Assistant',
                'window_num' => 5,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'ccawaling.psa',
                'password_hashed' => 'Cawaling2025',
            ],
            [
                'name' => 'MARK RAYMUND DEGALA',
                'email' => 'mdegala.psa@nationalid.gov.ph',
                'password' => Hash::make('Degala2025'),
                'full_name' => 'MARK RAYMUND DEGALA',
                'designation' => 'Registration Assistant',
                'window_num' => 4,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'mdegala.psa',
                'password_hashed' => 'Degala2025',
            ],
            [
                'name' => 'Lovely H. Lomopog',
                'email' => 'llomopog.psa@nationalid.gov.ph',
                'password' => Hash::make('Lomopog2025'),
                'full_name' => 'Lovely H. Lomopog',
                'designation' => 'Registration Kit Operator',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'llomopog.psa',
                'password_hashed' => 'Lomopog2025',
            ],
            [
                'name' => 'Honey L. Yamit',
                'email' => 'hyamit.psa@nationalid.gov.ph',
                'password' => Hash::make('Yamit2025'),
                'full_name' => 'Honey L. Yamit',
                'designation' => 'Registration Assistant',
                'window_num' => 6,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'hyamit.psa',
                'password_hashed' => 'Yamit2025',
            ],
        ];

        foreach ($users as $userData) {
            // Check if user already exists
            $existingUser = User::where('username', $userData['username'])->first();

            if ($existingUser) {
                // Update existing user
                $existingUser->update($userData);
                $this->command->info("Updated: {$userData['username']}");
            } else {
                // Create new user
                User::create($userData);
                $this->command->info("Created: {$userData['username']}");
            }
        }

        $this->command->info('Users seeded successfully!');
    }
}
