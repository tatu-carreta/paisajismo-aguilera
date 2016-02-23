<?php

class Provincia extends Eloquent {

    //Tabla de la BD
    protected $table = 'provincia';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'pais_id', 'mostrar');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public function pais() {
        return $this->belongsTo('Pais');
    }
    
    public function ciudades() {
        return $this->hasMany('Ciudad')->orderBy('nombre');
    }

}
