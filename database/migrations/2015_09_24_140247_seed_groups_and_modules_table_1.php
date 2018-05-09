<?php

use Illuminate\Database\Migrations\Migration;
use App\Repositories\GroupsAndPermissionRepository;

class SeedGroupsAndModulesTable1 extends Migration
{
	/**
	* @var GroupsAndPermissionRepository
	*/
    protected $groupsAndPermissionRepository;

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
		$this->groupsAndPermissionRepository = App::make('GroupsAndModulesRepo');
		$modulePermissions = array(
			'list' => 1,
			'read' => 1,
			'create' =>1,
			'update' =>1,
			'delete' =>1
		);
		$this->groupsAndPermissionRepository->createGroups('Desarrollador','Acceso Ilimitado',1);
		$this->groupsAndPermissionRepository->createGroups('Administrador','Acceso total al sistema',1);
		$this->groupsAndPermissionRepository->createGroups('Admin - Concesionaria ','Acceso a consecionaria',0);
		$this->groupsAndPermissionRepository->createGroups('Usuario - Concesionaria ','Acceso a consecionaria',0);
		sleep(1);
		$this->groupsAndPermissionRepository->createModules('Perfiles',$modulePermissions);
		$this->groupsAndPermissionRepository->createModules('Permisos',$modulePermissions);
		$this->groupsAndPermissionRepository->createModules('Usuarios',$modulePermissions);
		$this->groupsAndPermissionRepository->createModules('Vehiculos',$modulePermissions);
		$this->groupsAndPermissionRepository->createModules('Contactos',$modulePermissions);
		$this->groupsAndPermissionRepository->createModules('Concesionarias',$modulePermissions);
		$this->groupsAndPermissionRepository->createModules('Ventas',$modulePermissions);
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->groupsAndPermissionRepository = App::make('GroupsAndModulesRepo');
        $this->groupsAndPermissionRepository->deleteGroupsAndModules();
    }
}
