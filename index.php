<?php
/**
 * Sistema Didáctico de Consulta de RIF y Generación de Comprobantes SENIAT
 */

// Base de datos local de demostración para estudiantes
$contribuyentes = [
    '5633037' => [
        'id' => 1,
        'cedula' => '5.633.037',
        'rif' => 'V056330379',
        'nombre' => 'HERNANDEZ BARAZARTE JOSE DE LOS SANTOS',
        'domicilio' => 'CALLE PRINCIPAL CASA S/N SECTOR ALTO DE SAN ANTONIO BATATAL BOCONO ESTADO TRUJILLO BATATAL TRUJILLO ZONA POSTAL 3103',
        'comprobante' => '202505U0000067943276',
        'fecha_inscripcion' => '11/10/2007',
        'fecha_actualizacion' => '19/10/2006',
        'unidad' => 'UNIDAD TRIBUTOS INTERNOS BOCONÓ / SECTOR TRIBUTOS INTERNOS TRUJILLO-VALERA',
        'codigo_firma' => '1056330379-IUT',
        'nota_firmas' => '(Este contribuyente no posee firmas personales)',
        'retencion' => 'La condición de este contribuyente requiere la retención del 100% del impuesto causado, salvo que esté exento, no sujeto o demuestre ante el Agente de Retención del IVA que es un contribuyente exonerado.'
    ],
    '9159300' => [
        'id' => 2,
        'cedula' => '9.159.300',
        'rif' => 'V091593005',
        'nombre' => 'HERNANDEZ BARAZARTE ROSALIA DEL CARMEN',
        'domicilio' => 'CALLE PRINCIPAL CASA S/N SECTOR ANTIMANO LIBERTADOR, DTTO. CAPITAL ZONA POSTAL 1010',
        'comprobante' => '202505U0000067943280',
        'fecha_inscripcion' => '04/08/2009',
        'fecha_actualizacion' => '19/10/2006',
        'unidad' => 'SECTOR DE TRIBUTOS INTERNOS LIBERTADOR / REGIÓN CAPITAL',
        'codigo_firma' => '1091593005-IUT',
        'nota_firmas' => '(Este contribuyente no posee firmas personales)',
        'retencion' => 'La condición de este contribuyente requiere la retención del 100% del impuesto causado, salvo que esté exento, no sujeto o demuestre ante el Agente de Retención del IVA que es un contribuyente exonerado.'
    ],
    '5633038' => [
        'id' => 3,
        'cedula' => '5.633.038',
        'rif' => 'V056330387',
        'nombre' => 'HERNANDEZ BARAZARTE ANTONIA MARIA',
        'domicilio' => 'CALLE PRINCIPAL CASA S/N SECTOR ANTIMANO LIBERTADOR, DTTO. CAPITAL ZONA POSTAL 1010',
        'comprobante' => '202505U0000067943290',
        'fecha_inscripcion' => '14/07/2010',
        'fecha_actualizacion' => '19/10/2006',
        'unidad' => 'SECTOR DE TRIBUTOS INTERNOS LIBERTADOR / REGIÓN CAPITAL',
        'codigo_firma' => '1056330387-IUT',
        'nota_firmas' => '(Este contribuyente no posee firmas personales)',
        'retencion' => 'La condición de este contribuyente requiere la retención del 100% del impuesto causado, salvo que esté exento, no sujeto o demuestre ante el Agente de Retención del IVA que es un contribuyente exonerado.'
    ]
];

// Procesamiento de búsqueda vía POST o GET
$error = '';
if (isset($_REQUEST['cedula'])) {
    $input = trim($_REQUEST['cedula']);
    // Extraer solo dígitos de la cédula
    $cedulaLimpia = preg_replace('/[^0-9]/', '', $input);
    
    // Si viene con formato de 8 o 9 dígitos con el prefijo o ceros
    $cedulaNormalizada = ltrim($cedulaLimpia, '0');
    // Si ingresó el RIF completo con dígito verificador, probar extrayendo la base
    if (!isset($contribuyentes[$cedulaNormalizada]) && strlen($cedulaNormalizada) > 7) {
        $posibleBase = substr($cedulaNormalizada, 0, -1);
        if (isset($contribuyentes[$posibleBase])) {
            $cedulaNormalizada = $posibleBase;
        }
    }

    if (isset($contribuyentes[$cedulaNormalizada])) {
        // Redirigir a comprobante.html con la cédula encontrada
        header("Location: comprobante.html?cedula=" . urlencode($cedulaNormalizada));
        exit;
    } else if (!empty($input)) {
        $error = "La cédula ingresada (<strong>" . htmlspecialchars($input) . "</strong>) no se encuentra registrada en el sistema.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de RIF - SENIAT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-hover: #1e40af;
            --accent: #2563eb;
            --bg-page: #f1f5f9;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --error-bg: #fee2e2;
            --error-text: #991b1b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .portal-card {
            width: 100%;
            max-width: 580px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .portal-header {
            background: linear-gradient(135deg, #0b1f44 0%, #1e3a8a 60%, #2563eb 100%);
            color: #ffffff;
            padding: 36px 28px 30px;
            text-align: center;
            position: relative;
        }

        .seniat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .portal-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
            line-height: 1.25;
        }

        .portal-header p {
            font-size: 0.92rem;
            opacity: 0.9;
            color: #cbd5e1;
        }

        .portal-body {
            padding: 32px 28px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-main);
        }

        .input-container {
            display: flex;
            position: relative;
        }

        .input-prefix {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            background: #f8fafc;
            border: 2px solid var(--border-color);
            border-right: none;
            border-radius: 12px 0 0 12px;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.05rem;
        }

        .input-cedula {
            flex: 1;
            padding: 14px 18px;
            font-size: 1.15rem;
            font-weight: 600;
            border: 2px solid var(--border-color);
            border-radius: 0 12px 12px 0;
            outline: none;
            transition: all 0.2s;
            color: #0f172a;
        }

        .input-cedula:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .btn-consultar {
            width: 100%;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            padding: 15px 20px;
            font-size: 1.02rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-consultar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        }

        .btn-consultar:active {
            transform: translateY(0);
        }

        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 22px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Sección de Cédulas Registradas para pruebas */
        .demo-section {
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px dashed var(--border-color);
        }

        .demo-title {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 12px;
            text-align: center;
        }

        .demo-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .demo-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.2s;
            gap: 12px;
        }

        .demo-item:hover {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .demo-info {
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .demo-name {
            font-size: 0.86rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .demo-meta {
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .demo-rif-badge {
            font-weight: 700;
            color: #1e40af;
            background: #dbeafe;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Botón de PDF */
        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #dc2626;
            color: #ffffff;
            padding: 8px 14px;
            font-size: 0.82rem;
            font-weight: 700;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.2);
            white-space: nowrap;
        }

        .btn-pdf:hover {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="portal-card">
        <div class="portal-header">
            <div class="seniat-badge">
                🏛️ Portal SENIAT
            </div>
            <h1>Consulta de RIF</h1>
            <p>Ingrese la cédula de identidad para verificar y generar el Comprobante Digital</p>
        </div>

        <div class="portal-body">
            
            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <span>⚠️</span>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form action="index.php" method="GET" id="searchForm">
                <div class="form-group">
                    <label class="form-label" for="txtCedula">Cédula de Identidad:</label>
                    <div class="input-container">
                        <div class="input-prefix">V-</div>
                        <input 
                            type="text" 
                            name="cedula" 
                            id="txtCedula" 
                            class="input-cedula" 
                            placeholder="Ej: 5633037" 
                            required 
                            autofocus 
                            autocomplete="off"
                        />
                    </div>
                </div>

                <button type="submit" class="btn-consultar">
                    <span>🔍</span> Consultar Comprobante RIF
                </button>
            </form>

            <!-- Cédulas de demostración para estudiantes -->
            <div class="demo-section">
                <div class="demo-title">Cédulas Registradas para Generar PDF</div>
                <div class="demo-list">
                    <?php foreach ($contribuyentes as $ced => $c): ?>
                        <div class="demo-item">
                            <a href="comprobante.html?cedula=<?= $ced ?>" class="demo-info" title="Ver comprobante de <?= $c['nombre'] ?>">
                                <div class="demo-name"><?= $c['nombre'] ?></div>
                                <div class="demo-meta">
                                    <span>C.I: <?= $c['cedula'] ?></span>
                                    <span class="demo-rif-badge"><?= $c['rif'] ?></span>
                                </div>
                            </a>
                            <a href="pdf.html?cedula=<?= $ced ?>" target="_blank" class="btn-pdf" title="Abrir y Guardar PDF (Formato RIF Digital v2.0)">
                                <span>📄</span> PDF
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>

</body>
</html>