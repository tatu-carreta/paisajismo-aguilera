<?php

class Ciudad extends Eloquent {

    //Tabla de la BD
    protected $table = 'ciudad';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'codigo_postal', 'provincia_id');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public function provincia() {
        return $this->belongsTo('Provincia');
    }

    public function direcciones() {
        return $this->hasMany('Direccion')->where('direccion.estado', 'A')->orderBy('calle');
    }
}
