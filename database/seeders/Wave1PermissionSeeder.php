<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Wave1PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'announcements.view','announcements.create','announcements.update','announcements.delete',
            'events.view','events.create','events.update','events.delete','events.participants.manage',
            'id-cards.view','id-cards.create','id-cards.delete','id-card-templates.manage',
            'certificates.view','certificates.create','certificates.delete','certificate-templates.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission,'web');
        }
    }
}
