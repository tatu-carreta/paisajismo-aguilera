<?php

class TipoPrecio extends Eloquent {

    //Tabla de la BD
    protected $table = 'tipo_precio';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Me quedo con las categorias a las que pertenece el Item
    public function precios() {
        return $this->belongsToMany('Tipo_Precio', 'producto_precio', 'producto_id', 'tipo_precio_id');
    }

}