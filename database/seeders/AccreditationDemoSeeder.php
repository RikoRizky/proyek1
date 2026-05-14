<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Module;
use App\Models\Requirement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Data demo: admin, asesor, satu program studi, modul, dan persyaratan.
 * Akun program studi tambahan sebaiknya dibuat lewat panel Admin → Akun prodi & asesor.
 */
class AccreditationDemoSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin123'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'asesor@gmail.com'],
            [
                'name' => 'Asesor Demo',
                'password' => Hash::make('Asesor123'),
                'role' => UserRole::Asesor,
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'informatika@gmail.com'],
            [
                'name' => 'Program Studi Informatika',
                'password' => Hash::make('Informatika123'),
                'role' => UserRole::UnitKerja,
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'logistik@gmail.com'],
            [
                'name' => 'Program Studi Logistik',
                'password' => Hash::make('Logistik123'),
                'role' => UserRole::UnitKerja,
                'email_verified_at' => now(),
            ]
        );

        $m1 = Module::query()->updateOrCreate(
            ['name' => 'Kriteria 1: Visi Misi'],
            [
                'description' => 'Kecocokan visi, misi, tujuan, dan strategi dengan SPMI.',
                'weight' => 25,
                'sort_order' => 1,
            ]
        );

        $m2 = Module::query()->updateOrCreate(
            ['name' => 'Kriteria 2: Tata Pamong'],
            [
                'description' => 'Sistem tata pamong, kepemimpinan, sistem pengelolaan, dan penjaminan mutu.',
                'weight' => 25,
                'sort_order' => 2,
            ]
        );

        $m3 = Module::query()->updateOrCreate(
            ['name' => 'Kriteria 3: Mahasiswa'],
            [
                'description' => 'Kemahasiswaan dan layanan akademik.',
                'weight' => 25,
                'sort_order' => 3,
            ]
        );

        $m4 = Module::query()->updateOrCreate(
            ['name' => 'Kriteria 4: Sumber Daya Manusia'],
            [
                'description' => 'SDM pendidik dan tenaga kependidikan.',
                'weight' => 25,
                'sort_order' => 4,
            ]
        );

        $this->seedRequirements($m1->id, [
            ['title' => 'Dokumen visi institusi', 'description' => 'PDF visi yang disahkan.', 'sort_order' => 1],
            ['title' => 'Dokumen misi dan tujuan', 'description' => 'Excel/ PDF rencana strategis.', 'sort_order' => 2],
        ]);

        $this->seedRequirements($m2->id, [
            ['title' => 'SK struktur organisasi', 'description' => null, 'sort_order' => 1],
            ['title' => 'Dokumen SPMI', 'description' => 'Manual mutu terbaru.', 'sort_order' => 2],
        ]);

        $this->seedRequirements($m3->id, [
            ['title' => 'Panduan akademik mahasiswa', 'description' => null, 'sort_order' => 1],
        ]);

        $this->seedRequirements($m4->id, [
            ['title' => 'Profil dosen tetap', 'description' => 'Rekapitulasi SDM.', 'sort_order' => 1],
        ]);
    }

    private function seedRequirements(int $moduleId, array $rows): void
    {
        foreach ($rows as $row) {
            Requirement::query()->updateOrCreate(
                [
                    'module_id' => $moduleId,
                    'title' => $row['title'],
                ],
                [
                    'description' => $row['description'] ?? null,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }
    }
}
