<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Datos del Tenant a crear
        $tenantData = [
            'id' => 'gym-alpha',
            'name' => 'Gym Alpha',
            'domain' => 'alpha.localhost',
            'database' => 'gym_alpha',
        ];

        // 2. Crear el Tenant
        $tenant = Gym::create($tenantData);

        // 3. Opcional: Adjuntar el dominio (Stancl lo hace automáticamente si usas HasDomains,
        // pero es bueno asegurar si no se usó el comando de CLI)
        if (! $tenant->domains()->where('domain', $tenantData['domain'])->exists()) {
             $tenant->domains()->create(['domain' => $tenantData['domain']]);
        }

        $this->command->info("Tenant 'Gym Alpha' creado exitosamente.");

        // 4. Ejecutar las migraciones para la nueva base de datos del Tenant
        // Esto es CRUCIAL para que el tenant pueda funcionar
        $this->command->call('tenants:migrate', [
            '--tenants' => ['gym-alpha'],
        ]);

        $this->command->info("Migraciones ejecutadas para el tenant 'gym-alpha'.");
    }
}
