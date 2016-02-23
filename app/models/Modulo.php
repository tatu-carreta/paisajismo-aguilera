<?php

class Modulo extends Eloquent {

    //Tabla de la BD
    protected $table = 'modulo';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Me quedo con las categorias a las que pertenece el Item
    public function menus() {
        return $this->belongsToMany('Menu', 'menu_modulo', 'modulo_id')->where('menu.estado', 'A');
    }

}