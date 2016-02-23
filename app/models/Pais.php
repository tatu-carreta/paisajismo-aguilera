<?php

class Pais extends Eloquent {

    //Tabla de la BD
    protected $table = 'pais';
    //Atributos que van a ser modificables
    protected $fillable = array('codigo', 'nombre', 'mostrar');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public function provincias() {
        return $this->hasMany('Provincia')->orderBy('nombre');
    }

}
