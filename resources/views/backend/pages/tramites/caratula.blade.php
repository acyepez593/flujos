<!DOCTYPE html>
<html>
<head>
    <title>Plantilla</title>
</head>
<body>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            margin: 0 auto;
        }
        table {
            margin-left: auto;
            margin-right: auto;
            background-color: #F5F5F5;
        }
        
        table, th, td {
            border: 1px solid #FFFFFF;
            border-collapse: collapse;
        }
        p {
            margin: 5px;
        }
        .img-discapacidad{
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            vertical-align: middle;
        }
        .img-proteccion{
            background-color: #FFFFFF;
        }
        .header {
            margin-top: -43px;
            margin-left: -43px;
            width: 790px;
            overflow: hidden;
        }
        .header img {
            width: 100%;
            object-fit: cover;
            object-position: center;
        }
        .footer {
            position: fixed;
            bottom: -43px;
            width: 100%;
            color: white;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            vertical-align: middle;
        }
        .footer img {
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            vertical-align: middle;
        }
        
	</style>
    <div class="header">
        <img class="" src="{{ $headerImg }}" width="100%">
    </div>
    <h3 style="text-align: center;">TIPO DE PROTECCIÓN:</h3>
    <table class="img-proteccion" width="400">
        <tbody>
            <tr style="height: 40.2px;">
                <td class="img-discapacidad" style="height: 40.2px;" width="400">
                    <img class="" src="{{ $proteccionImg }}" width="150px">
                </td>
            </tr>
        </tbody>
    </table>
    <h3 style="text-align: center;">{{ $proceso->nombre }}</h3>
    
    <table width="400">
        <tbody>
            <tr style="height: 40.2px;">
                <td style="height: 40.2px;" width="400">
                    <p style="text-align: center;">ID DE EXPEDIENTE:</p>
                </td>
            </tr>
            <tr style="height: 35px;">
                <td style="height: 35px;" width="400">
                    <p style="text-align: center; font-size: 3em;"><strong>{{ $identificadorTramite }}</strong></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <h3 style="text-align: center;">DATOS DE LA VÍCTIMA:</h3>
    <table width="400">
        <tbody>
            <tr>
                <td style="width: 400px; text-align: center;" colspan="4">
                    <p>{{ $victima['nombre_completo'] }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 60px;">
                    <p><strong>NUI:</strong></p>
                </td>
                <td style="width: 200px;">
                    <p>{{ $victima['numero_documento'] }}</p>
                </td>
                <td style="width: 70px;">
                    <p><strong>EDAD</strong><strong>:</strong></p>
                </td>
                <td style="width: 70px;">
                    <p>{{ $victima['edad'] }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <table width="400">
        <tbody>
            <tr>
                <td style="width: 100px;">
                    <p><strong>SOLICITANTE:</strong></p>
                </td>
                <td style="width: 300px;">
                    <p>{{ $reclamante['nombre_completo'] }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 100px;">
                    <p><strong>NUI:</strong></p>
                </td>
                <td style="width: 300px;">
                    <p>{{ $reclamante['numero_documento'] }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <table width="400">
        <tbody>
            <tr>
                <td style="width: 60px;" rowspan="2">
                    <p><strong>SINIESTRO</strong></p>
                </td>
                <td style="width: 80px;">
                    <p><strong>FECHA</strong></p>
                </td>
                <td style="width: 130px;">
                    <p><strong>PROVINCIA</strong></p>
                </td>
                <td style="width: 130px;">
                    <p><strong>CANTÓN</strong></p>
                </td>
            </tr>
            <tr>
                <td style="width: 80px;">
                    <p>{{ ($proceso->id == 3) ? $siniestro['fecha_accidente'] : $siniestro['fecha_siniestro']  }}</p>
                </td>
                <td style="width: 130px;">
                    <p>{{ $listaCatalogos[$siniestro['provincia_accidente_id']] }}</p>
                </td>
                <td style="width: 130px;">
                    <p>{{ $listaCatalogos[$siniestro['canton_accidente_id']] }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <table style="background-color: #FFFFFF; font-size: 12px;" width="400">
        <tbody>
            <tr>
                <td style="width: 150px;">
                    <p><strong>Fecha de Creación:</strong></p>
                </td>
                <td style="width: 250px;">
                    <p>{{ $fechaCreacion }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">
                    <p><strong>Usuario:</strong></p>
                </td>
                <td style="width: 250px;">
                    <p>{{ $user->name }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">
                    <p><strong>Agencia:</strong></p>
                </td>
                <td style="width: 250px;">
                    <p>{{ $agencia->nombre }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="footer">
        <img class="" src="{{ $footerImg }}" width="50%">
    </div>
</body>
</html>