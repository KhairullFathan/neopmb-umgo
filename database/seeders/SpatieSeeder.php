<?php
// ROLE:
// SUPERADMIN
// ADMIN
// KEUANGAN
// AKADEMIK / KEMAHSISWAAN
// PIMPINAN
// CAMABA
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class SpatieSeeder extends Seeder
{
	use WithoutModelEvents;

	/**
	 * Seed the application's database.
	 */
    private array $permissions = [
        'dashboard.view',
        'masterdata.faculty.view',
        'masterdata.faculty.manage',
        'masterdata.study_program.view',
        'masterdata.study_program.manage',
        'user.view',
        'user.manage',
        'registrant.view',
        'registrant.manage',
        'pmb.period.view',
        'pmb.period.manage',
        'pmb.registration_path.view',
        'pmb.registration_path.manage',
        'pmb.registration_fee.view',
        'pmb.registration_fee.manage',
        'pmb.reregistration_fee.view',
        'pmb.reregistration_fee.manage',
        'pmb.wave.view',
        'pmb.wave.manage',
        'pmb.application.view',
        'pmb.application.manage',
        'pmb.verification.view',
        'pmb.verification.manage',
        'pmb.graduation.view',
        'pmb.graduation.manage',
        'reregistration.view',
        'reregistration.manage',
        'report.application.view',
        'report.application.export',
        'report.document.view',
        'report.document.export',
        'report.graduation.view',
        'report.graduation.export',
        'report.reregistration.view',
        'report.reregistration.export',
        // camaba - fe
        'my.applicant',
        'my.application',
        'my.exam',
    ];
    private function superadmin(){
		$role = Role::firstOrCreate(['name' => 'superadmin']);
		$role->syncPermissions([
            'dashboard.view',
            'masterdata.faculty.view',
            'masterdata.faculty.manage',
            'masterdata.study_program.view',
            'masterdata.study_program.manage',
            'user.view',
            'user.manage',
            'registrant.view',
            'registrant.manage',
            'pmb.period.view',
            'pmb.period.manage',
            'pmb.registration_path.view',
            'pmb.registration_path.manage',
            'pmb.registration_fee.view',
            'pmb.registration_fee.manage',
            'pmb.reregistration_fee.view',
            'pmb.reregistration_fee.manage',
            'pmb.wave.view',
            'pmb.wave.manage',
            'pmb.application.view',
            'pmb.application.manage',
            'pmb.verification.view',
            'pmb.verification.manage',
            'pmb.graduation.view',
            'pmb.graduation.manage',
            'reregistration.view',
            'reregistration.manage',
            'report.application.view',
            'report.application.export',
            'report.document.view',
            'report.document.export',
            'report.graduation.view',
            'report.graduation.export',
            'report.reregistration.view',
            'report.reregistration.export',
        ]);
    }
    private function admin(){
		$role = Role::firstOrCreate(['name' => 'admin']);
		$role->syncPermissions([
            'dashboard.view',
            'registrant.view',
            'registrant.manage',
            'pmb.period.view',
            'pmb.registration_path.view',
            'pmb.registration_fee.view',
            'pmb.reregistration_fee.view',
            'pmb.wave.view',
            'pmb.application.view',
            'pmb.application.manage',
            'pmb.verification.view',
            'pmb.verification.manage',
            'pmb.graduation.view',
            'pmb.graduation.manage',
            'report.application.view',
            'report.application.export',
            'report.document.view',
            'report.document.export',
            'report.graduation.view',
            'report.graduation.export',
            'report.reregistration.view',
            'report.reregistration.export',
        ]);
    }
    private function camaba(){
        $role = Role::firstOrCreate(['name' => 'camaba']);
		$role->syncPermissions([
            'dashboard.view',
            'my.applicant',
            'my.application',
            'my.exam',
        ]);
    }
    private function keuangan(){}
    private function akademik(){}
    private function pimpinan(){}
    private function createPermissions(){
        foreach(@$this->permissions as $permission) {
			Permission::firstOrCreate(['name'=> $permission]);
		}
    }
	public function run(): void
	{
		// 1. Reset cache peran dan izin
		app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->createPermissions();
        $this->superadmin();
        $this->admin();
        $this->camaba();
	}
}
