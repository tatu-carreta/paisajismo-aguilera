<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Offitec - Equipamiento para oficinas</title>
    </head>

    <body>
        <div style="width:650px; margin-left:20px">

            <table width="650" border="0" cellpadding="0" cellspacing="0" style="font-family:arial, sans-serif; font-size:14px;">
                <tr><!-- única fila  -->
                    <td valign="top" style="padding:30px 40px 30px 40px"><!-- celda columna principal  -->

                        <img src="{{URL::to('images/offitec.gif')}}" alt="Logo Offitec">
                        <p style="margin-top:0"><strong>PRESUPUESTO</strong> / Fecha: {{date('d/m/Y')}}<!--datos --></p>
                        <p style="margin-top:35px;line-height:20px">
                            Nombre y apellido: {{ $data['nombre'] }}<br>
                            @if(isset($data['empresa']))
                            Institución o empresa: {{ $data['empresa'] }}<br>
                            @endif
                            Email: {{ $data['email'] }}<br>
                            @if(isset($data['telefono']))
                            Teléfono: {{ $data['telefono'] }}<br>
                            @endif
                            @if(isset($data['consulta']))
                            Comentarios: {{ $data['consulta'] }}<br>
                            @endif
                        </p>

                        <!-- PRODUCTOS -->
                        @if(count($data['productos']) > 0)
                            <p style="margin-top:30px; font-size:16px">Productos seleccionados:</P>
                                
                            <table width="540" border="0" cellpadding="0" cellspacing="0"><!-- tabla contenedora de productos, 2 filas de 3 celdas -->
                            
                                <?php $i=0; ?>
                                @foreach($data['productos'] as $producto)
                                
                                    @if($i == 0)
                                        <tr><!-- fila 1 -->
                                    @endif
                                    <!-- PRODUCTO 1 -->
                                    <td>
                                        <table width="165" border="0" cellpadding="0" cellspacing="0" style="margin-right:15px; margin-top:15px">
                                            <tr>
                                                <td style="border:1px solid #999; height: 165px; width: 165px;font-size:0px;">
                                                    @if(!is_null(Producto::find($producto['id'])->item()->imagen_destacada()))<img style="height: 165px; width: 165px;" src="{{URL::to(Producto::find($producto['id'])->item()->imagen_destacada()->carpeta.Producto::find($producto['id'])->item()->imagen_destacada()->nombre) }}"><br>@endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="font-family:verdana; font-size:12px">
                                                        ART: {{ Producto::find($producto['id'])->item()->titulo }}<br>
                                                        CANT: {{ $producto['cantidad'] }}<br>
                                                        PRECIO UNIT: <!--datos--> <br>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    @if($i == 2)
                                        </tr>
                                        <?php $i = 0; ?>
                                    @else
                                        <?php $i++; ?>
                                    @endif
                                
                                @endforeach
                                
                            </table><!-- /tabla contenedora de productos  -->
                            
                        @endif
                        
                        

                        <p style="margin-top:30px; padding-bottom:30px; border-bottom:1px solid #ccc">
                            <strong>Nota del vendedor:</strong><br>
                            Los precios son al contado, sin IVA
                        </p>

                        <img src="{{URL::to('images/offitec-pie.gif')}}" style="margin-top:30px">
                        <p style="margin-top:0px; font-size:16px">
                            www.offitec.com  /  ventas@offitec.com</p>

                        <p>
                            <strong>Offitec en La Plata</strong><br>
                            Calle 39 N° 833 e/ 11 y 12 <br>
                            Teléfono: (0221) 4221273 / Fax: (0221) 4273777 <br>
                            Email: ventas@offitec.com
                        </p>

                        <p>
                            <strong>Offitec en Lomas de Zamora</strong><br>
                            Av. Hipólito Yrigoyen 9275 (ex Av. Pavón) <br>
                            Teléfono: (011) 4244 4099 <br>
                            Email: lomas@offitec.com
                        </p>

                        <p style="font-size:10px; color:#666; margin-top:15px">VENTA TELEFONICA    /   ENVIOS A TODO EL PAIS</p>

                    </td> <!-- /celda columna principal-->
                </tr>
            </table> <!-- /tabla principal-->
        </div>
    </body>
</html>
