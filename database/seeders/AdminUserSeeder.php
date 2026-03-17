<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin Users
            [
                'name' => 'System Administrator',
                'email' => 'admin@nationalid.gov.ph',
                'password' => Hash::make('Admin@2025'),
                'full_name' => 'System Administrator',
                'designation' => 'Administrator',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'PSA Central Office',
                'username' => 'admin',
                'password_hashed' => 'Admin@2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@nationalid.gov.ph',
                'password' => Hash::make('SuperAdmin@2025'),
                'full_name' => 'Super Administrator',
                'designation' => 'Super Administrator',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'PSA Central Office',
                'username' => 'superadmin',
                'password_hashed' => 'SuperAdmin@2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Additional Operators
            [
                'name' => 'Maria Santos',
                'email' => 'msantos.psa@nationalid.gov.ph',
                'password' => Hash::make('Santos2025'),
                'full_name' => 'Maria Santos',
                'designation' => 'Registration Kit Operator',
                'window_num' => 7,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'msantos.psa',
                'password_hashed' => 'Santos2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John Dela Cruz',
                'email' => 'jdelacruz.psa@nationalid.gov.ph',
                'password' => Hash::make('DelaCruz2025'),
                'full_name' => 'John Dela Cruz',
                'designation' => 'Registration Assistant',
                'window_num' => 8,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'jdelacruz.psa',
                'password_hashed' => 'DelaCruz2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Additional Screeners
            [
                'name' => 'Ana Reyes',
                'email' => 'areyes.psa@nationalid.gov.ph',
                'password' => Hash::make('Reyes2025'),
                'full_name' => 'Ana Reyes',
                'designation' => 'Screener',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'areyes.psa',
                'password_hashed' => 'Reyes2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pedro Lim',
                'email' => 'plim.psa@nationalid.gov.ph',
                'password' => Hash::make('Lim2025'),
                'full_name' => 'Pedro Lim',
                'designation' => 'Screener',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'Ground Floor Limketkai Mall, C. M. Recto Avenue',
                'username' => 'plim.psa',
                'password_hashed' => 'Lim2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Managers/Supervisors
            [
                'name' => 'Director Garcia',
                'email' => 'dgarcia.psa@nationalid.gov.ph',
                'password' => Hash::make('Garcia2025'),
                'full_name' => 'Director Garcia',
                'designation' => 'Operations Manager',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'PSA Regional Office',
                'username' => 'dgarcia.psa',
                'password_hashed' => 'Garcia2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Supervisor Torres',
                'email' => 'storres.psa@nationalid.gov.ph',
                'password' => Hash::make('Torres2025'),
                'full_name' => 'Supervisor Torres',
                'designation' => 'Team Supervisor',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'PSA Regional Office',
                'username' => 'storres.psa',
                'password_hashed' => 'Torres2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // IT Support
            [
                'name' => 'IT Support',
                'email' => 'itsupport@nationalid.gov.ph',
                'password' => Hash::make('ITSupport2025'),
                'full_name' => 'IT Support Team',
                'designation' => 'Technical Support',
                'window_num' => 0,
                'reg' => 0,
                'prov' => 0,
                'mun' => 0,
                'brgy' => 0,
                'specific_loc' => 'IT Department',
                'username' => 'itsupport',
                'password_hashed' => 'ITSupport2025',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $userData) {
            $existingUser = User::where('username', $userData['username'])->first();

            if ($existingUser) {
                // Update existing user
                $existingUser->update($userData);
                $this->command->info("Updated: {$userData['username']} - {$userData['full_name']}");
            } else {
                // Create new user
                User::create($userData);
                $this->command->info("Created: {$userData['username']} - {$userData['full_name']}");
            }
        }

        $this->command->info('====================================');
        $this->command->info('Admin users seeded successfully!');
        $this->command->info('====================================');
        $this->command->info('Admin Login Credentials:');
        $this->command->info('Username: admin');
        $this->command->info('Password: Admin@2025');
        $this->command->info('------------------------------------');
        $this->command->info('Super Admin Login Credentials:');
        $this->command->info('Username: superadmin');
        $this->command->info('Password: SuperAdmin@2025');
        $this->command->info('====================================');
    }
}
