<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedConsultasModule extends Migration
{

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
        $this->groupsAndPermissionRepository->createModules('Consultas',$modulePermissions);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->groupsAndPermissionRepository = App::make('GroupsAndModulesRepo');
        $this->groupsAndPermissionRepository->deleteModule('Consultas');

    }
}
