<?php

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

    protected $fillable = array('nombre');
    public $timestamps = false;

    public function usuarios() {
        return $this->belongsToMany('Usuario', 'assigned_roles');
    }

    public function permisos_asignados() {
        $asignados = $this->belongsToMany('Permission', 'permission_role')->lists('permission_id');

        $permisos = array();

        if ($asignados) {
            foreach ($asignados as $permiso => $key) {
                array_push($permisos, $key);
            }
        }

        return $permisos;
    }

    public function permisos() {
        return $this->belongsToMany('Permission', 'permission_role');
    }

}