<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    protected $dbUser;
    protected $dbPass;
    protected $dbName;
    protected $dbHost;
    protected $dbPort;
    protected $backupPath;
    protected $cloudFolder;

    // Configuracion de tablas con PKs y columnas actualizables para UPSERT
    protected $tablasConfig = [
        'bancos' => [
            'pk' => 'numero',
            'columnas' => ['numero', 'nombre', 'comentario', 'created_at', 'updated_at'],
            'actualizables' => ['nombre', 'comentario', 'updated_at']
        ],
        'proveedores' => [
            'pk' => 'codigo',
            'columnas' => ['codigo', 'numero_proveedor', 'nombre', 'direccion', 'telefono', 'localidad', 'provincia', 'fecha_ultima_compra', 'created_at', 'updated_at', 'activo'],
            'actualizables' => ['numero_proveedor', 'nombre', 'direccion', 'telefono', 'localidad', 'provincia', 'fecha_ultima_compra', 'updated_at', 'activo']
        ],
        'facturas' => [
            'pk' => 'id',
            'columnas' => ['id', 'codigo_proveedor', 'fecha', 'numero', 'total', 'created_at', 'updated_at'],
            'actualizables' => ['codigo_proveedor', 'fecha', 'numero', 'total', 'updated_at']
        ],
        'cuentas' => [
            'pk' => 'codigo',
            'columnas' => ['codigo', 'numero_banco', 'numero_cuenta', 'created_at', 'updated_at'],
            'actualizables' => ['numero_banco', 'numero_cuenta', 'updated_at']
        ],
        'cheques' => [
            'pk' => 'id',
            'columnas' => ['id', 'entro', 'codigo_cuenta', 'numero_cheque', 'fecha', 'debe', 'haber', 'codigo_factura', 'created_at', 'updated_at', 'fecha_entrada'],
            'actualizables' => ['entro', 'codigo_cuenta', 'numero_cheque', 'fecha', 'debe', 'haber', 'codigo_factura', 'updated_at', 'fecha_entrada']
        ],
        'users' => [
            'pk' => 'id',
            'columnas' => ['id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at'],
            'actualizables' => ['name', 'email', 'email_verified_at', 'password', 'remember_token', 'updated_at']
        ],
        'cache' => [
            'pk' => 'key',
            'columnas' => ['key', 'value', 'expiration'],
            'actualizables' => ['value', 'expiration']
        ],
        'cache_locks' => [
            'pk' => 'key',
            'columnas' => ['key', 'owner', 'expiration'],
            'actualizables' => ['owner', 'expiration']
        ],
        'sessions' => [
            'pk' => 'id',
            'columnas' => ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'],
            'actualizables' => ['user_id', 'ip_address', 'user_agent', 'payload', 'last_activity']
        ],
        'migrations' => [
            'pk' => 'id',
            'columnas' => ['id', 'migration', 'batch'],
            'actualizables' => ['migration', 'batch']
        ],
        'jobs' => [
            'pk' => 'id',
            'columnas' => ['id', 'queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at'],
            'actualizables' => ['queue', 'payload', 'attempts', 'reserved_at', 'available_at']
        ],
        'job_batches' => [
            'pk' => 'id',
            'columnas' => ['id', 'name', 'total_jobs', 'pending_jobs', 'failed_jobs', 'failed_job_ids', 'options', 'cancelled_at', 'created_at', 'finished_at'],
            'actualizables' => ['name', 'total_jobs', 'pending_jobs', 'failed_jobs', 'failed_job_ids', 'options', 'cancelled_at', 'finished_at']
        ],
        'failed_jobs' => [
            'pk' => 'id',
            'columnas' => ['id', 'uuid', 'connection', 'queue', 'payload', 'exception', 'failed_at'],
            'actualizables' => ['uuid', 'connection', 'queue', 'payload', 'exception', 'failed_at']
        ],
        'password_reset_tokens' => [
            'pk' => 'email',
            'columnas' => ['email', 'token', 'created_at'],
            'actualizables' => ['token', 'created_at']
        ],
    ];

    // Mapeo de tablas con secuencias (solo las que usan SERIAL/BIGSERIAL)
    protected $tablasConSecuencias = [
        'bancos' => ['secuencia' => 'bancos_numero_seq', 'columna_pk' => 'numero'],
        'proveedores' => ['secuencia' => 'proveedores_codigo_seq', 'columna_pk' => 'codigo'],
        'facturas' => ['secuencia' => 'facturas_id_seq', 'columna_pk' => 'id'],
        'cuentas' => ['secuencia' => 'cuentas_codigo_seq', 'columna_pk' => 'codigo'],
        'cheques' => ['secuencia' => 'cheques_id_seq', 'columna_pk' => 'id'],
        'users' => ['secuencia' => 'users_id_seq', 'columna_pk' => 'id'],
        'migrations' => ['secuencia' => 'migrations_id_seq', 'columna_pk' => 'id'],
        'jobs' => ['secuencia' => 'jobs_id_seq', 'columna_pk' => 'id'],
        'failed_jobs' => ['secuencia' => 'failed_jobs_id_seq', 'columna_pk' => 'id'],
    ];

    public function __construct()
    {
        $this->dbUser = env('DB_USERNAME', 'postgres');
        $this->dbPass = env('DB_PASSWORD', 'postgres');
        $this->dbName = env('DB_DATABASE', 'muebleria');
        $this->dbHost = env('DB_HOST', '127.0.0.1');
        $this->dbPort = env('DB_PORT', '5432');
        
        $this->backupPath = 'C:\\Proyecto-muebleria\\backups\\sincro_actual.sql';
        $this->cloudFolder = 'muebleria-Marquez:SincronizacionManual/';
    }

    private function verificarRclone()
    {
        $output = shell_exec('rclone version 2>&1');
        
        if ($output === null || strpos($output, 'rclone') === false) {
            return false;
        }
        
        return true;
    }

    private function verificarYCrearDirectorio()
    {
        try {
            $folder = $this->cloudFolder;
            $comandoCheck = 'rclone lsf ' . $folder . ' 2>&1';
            $resultado = shell_exec($comandoCheck);
            
            if ($resultado === null || strpos($resultado, 'not found') !== false || strpos($resultado, 'directory not found') !== false) {
                Log::info('Creando directorio en Google Drive: ' . $folder);
                
                $comandoMkdir = 'rclone mkdir ' . $folder . ' 2>&1';
                $resultadoMkdir = shell_exec($comandoMkdir);
                
                Log::info('Directorio SincronizacionManual creado exitosamente');
            } else {
                Log::info('Directorio ' . $folder . ' ya existe');
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error verificando/creando directorio: ' . $e->getMessage());
            return false;
        }
    }

    private function getPgPasswordCommand($password)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return 'set PGPASSWORD=' . $password . '&&';
        } else {
            return 'PGPASSWORD=' . $password;
        }
    }

    private function verificarPostgreSQL()
    {
        $rutasPosibles = [
            'C:\\Program Files\\PostgreSQL\\18\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\17\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\16\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\15\\bin\\pg_dump.exe',
            'C:\\Program Files\\PostgreSQL\\14\\bin\\pg_dump.exe',
        ];
        
        foreach ($rutasPosibles as $ruta) {
            if (file_exists($ruta)) {
                return $ruta;
            }
        }
        
        $output = shell_exec('pg_dump --version 2>&1');
        if ($output !== null && strpos($output, 'pg_dump') !== false) {
            return 'pg_dump';
        }
        
        return null;
    }

    /**
     * Procesa el archivo SQL convirtiendo COPY statements a INSERT con UPSERT
     * Esto permite: insertar nuevos registros, actualizar existentes, sin duplicados
     */
    private function procesarSQLParaMerge($archivoOriginal, $archivoDestino)
    {
        try {
            Log::info('Procesando SQL para UPSERT (merge sin duplicados)...');
            
            $contenido = file_get_contents($archivoOriginal);
            if ($contenido === false) {
                throw new \Exception('No se pudo leer el archivo SQL');
            }
            
            $lineas = explode("\n", $contenido);
            $resultado = [];
            $enCopy = false;
            $tablaActual = null;
            $columnasActuales = [];
            $datosBuffer = [];
            
            foreach ($lineas as $linea) {
                $lineaTrim = trim($linea);
                
                // Comentar comandos destructivos (DROP, TRUNCATE, DELETE)
                if (preg_match('/^(DROP\s+|TRUNCATE\s+|DELETE\s+FROM)/i', $lineaTrim)) {
                    $resultado[] = '-- OMITIDO (seguridad): ' . $linea;
                    Log::info('Comando destructivo omitido: ' . substr($lineaTrim, 0, 50));
                    continue;
                }
                
                // Comentar setval de secuencias (evitar conflictos de IDs)
                if (preg_match('/^SELECT\s+pg_catalog\.setval/i', $lineaTrim)) {
                    $resultado[] = '-- OMITIDO (secuencia): ' . $linea;
                    continue;
                }
                
                // Detectar inicio de COPY ... FROM stdin
                if (preg_match('/^COPY\s+public\.(\w+)\s*\(([^)]+)\)\s*FROM\s+stdin/i', $lineaTrim, $matches)) {
                    $enCopy = true;
                    $tablaActual = $matches[1];
                    $columnasActuales = array_map('trim', explode(',', $matches[2]));
                    $datosBuffer = [];
                    $resultado[] = '-- Convertido de COPY a INSERT con UPSERT para tabla: ' . $tablaActual;
                    Log::info('Procesando tabla: ' . $tablaActual . ' con ' . count($columnasActuales) . ' columnas');
                    continue;
                }
                
                // Fin del COPY (linea con solo \.)
                if ($enCopy && $lineaTrim === '\.') {
                    // Generar INSERTs con ON CONFLICT DO UPDATE
                    $inserts = $this->generarInsertsConUpsert($tablaActual, $columnasActuales, $datosBuffer);
                    $resultado = array_merge($resultado, $inserts);
                    
                    Log::info('Tabla ' . $tablaActual . ': ' . count($datosBuffer) . ' registros procesados');
                    
                    $enCopy = false;
                    $tablaActual = null;
                    $columnasActuales = [];
                    $datosBuffer = [];
                    continue;
                }
                
                // Dentro de COPY: acumular datos
                if ($enCopy) {
                    if (!empty($lineaTrim)) {
                        $datosBuffer[] = $linea;
                    }
                    continue;
                }
                
                // Manejar INSERT statements directos (por si pg_dump usa --inserts)
                if (preg_match('/^INSERT\s+INTO\s+(?:public\.)?(\w+)\s*\(/i', $lineaTrim, $matches)) {
                    $tabla = $matches[1];
                    $config = $this->tablasConfig[$tabla] ?? null;
                    
                    if ($config && strpos($lineaTrim, 'ON CONFLICT') === false) {
                        $pk = $config['pk'];
                        $actualizables = $config['actualizables'];
                        
                        // Construir clausula UPDATE
                        $updateSet = [];
                        foreach ($actualizables as $col) {
                            $updateSet[] = "$col = EXCLUDED.$col";
                        }
                        $updateClause = implode(', ', $updateSet);
                        
                        // Reemplazar ; por ON CONFLICT ... ;
                        if (!empty($updateClause)) {
                            $linea = preg_replace('/;\s*$/', " ON CONFLICT ($pk) DO UPDATE SET $updateClause;", $linea);
                        } else {
                            $linea = preg_replace('/;\s*$/', " ON CONFLICT ($pk) DO NOTHING;", $linea);
                        }
                    }
                }
                
                // Lineas normales: pasar sin modificar
                $resultado[] = $linea;
            }
            
            $contenidoFinal = implode("\n", $resultado);
            
            if (file_put_contents($archivoDestino, $contenidoFinal) === false) {
                throw new \Exception('No se pudo escribir el archivo SQL procesado');
            }
            
            Log::info('SQL procesado exitosamente con UPSERT');
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error procesando SQL: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Genera INSERT statements con ON CONFLICT DO UPDATE para cada fila
     */
    private function generarInsertsConUpsert($tabla, $columnas, $datos)
    {
        $inserts = [];
        
        if (empty($datos)) {
            return $inserts;
        }
        
        // Obtener configuracion de la tabla
        $config = $this->tablasConfig[$tabla] ?? null;
        
        if (!$config) {
            // Tabla no configurada: usar ON CONFLICT DO NOTHING como fallback seguro
            Log::warning("Tabla '$tabla' no tiene configuracion de UPSERT, usando DO NOTHING");
            foreach ($datos as $fila) {
                $valores = $this->parsearFilaCopy($fila);
                if (empty($valores) || count($valores) !== count($columnas)) {
                    Log::warning("Fila con valores incorrectos en tabla $tabla, saltando...");
                    continue;
                }
                
                $valoresSQL = $this->formatearValoresSQL($valores);
                $columnasSQL = implode(', ', $columnas);
                
                $inserts[] = "INSERT INTO public.$tabla ($columnasSQL) VALUES ($valoresSQL) ON CONFLICT DO NOTHING;";
            }
            return $inserts;
        }
        
        $pk = $config['pk'];
        $actualizables = $config['actualizables'];
        
        // Generar clausula DO UPDATE SET
        $updateSet = [];
        foreach ($actualizables as $col) {
            $updateSet[] = "$col = EXCLUDED.$col";
        }
        $updateClause = implode(', ', $updateSet);
        
        foreach ($datos as $fila) {
            $valores = $this->parsearFilaCopy($fila);
            if (empty($valores) || count($valores) !== count($columnas)) {
                Log::warning("Fila con valores incorrectos en tabla $tabla, saltando...");
                continue;
            }
            
            $valoresSQL = $this->formatearValoresSQL($valores);
            $columnasSQL = implode(', ', $columnas);
            
            if (!empty($updateClause)) {
                $inserts[] = "INSERT INTO public.$tabla ($columnasSQL) VALUES ($valoresSQL) ON CONFLICT ($pk) DO UPDATE SET $updateClause;";
            } else {
                $inserts[] = "INSERT INTO public.$tabla ($columnasSQL) VALUES ($valoresSQL) ON CONFLICT ($pk) DO NOTHING;";
            }
        }
        
        return $inserts;
    }

    /**
     * Parsea una fila del formato COPY (separado por tabs, \N para NULL)
     */
    private function parsearFilaCopy($fila)
    {
        // COPY usa tabs como separador
        $valores = explode("\t", $fila);
        return $valores;
    }

    /**
     * Resetea todas las secuencias al valor maximo de cada tabla
     * Esto evita errores de "llave duplicada" al insertar nuevos registros
     */
    private function resetearSecuencias()
    {
        try {
            Log::info('Reseteando secuencias de PostgreSQL...');
            
            $conexion = \DB::connection()->getPdo();
            $secuenciasReseteadas = 0;
            
            foreach ($this->tablasConSecuencias as $tabla => $config) {
                $secuencia = $config['secuencia'];
                $columnaPk = $config['columna_pk'];
                
                try {
                    // Obtener el valor maximo actual de la columna PK
                    $resultado = \DB::select("SELECT COALESCE(MAX($columnaPk), 0) as max_val FROM public.$tabla");
                    $maxVal = $resultado[0]->max_val ?? 0;
                    
                    if ($maxVal > 0) {
                        // Resetear la secuencia al valor maximo + 1
                        \DB::statement("SELECT setval('public.$secuencia', $maxVal, true)");
                        Log::info("Secuencia $secuencia reseteada a $maxVal");
                        $secuenciasReseteadas++;
                    } else {
                        // Si la tabla esta vacia, resetear a 1
                        \DB::statement("SELECT setval('public.$secuencia', 1, false)");
                        Log::info("Secuencia $secuencia reseteada a 1 (tabla vacia)");
                        $secuenciasReseteadas++;
                    }
                } catch (\Exception $e) {
                    // La secuencia puede no existir si la tabla usa otro tipo de PK
                    Log::warning("No se pudo resetear secuencia para $tabla: " . $e->getMessage());
                }
            }
            
            Log::info("Total de secuencias reseteadas: $secuenciasReseteadas");
            return $secuenciasReseteadas;
            
        } catch (\Exception $e) {
            Log::error('Error reseteando secuencias: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Formatea valores para SQL escapando strings y manejando tipos especiales
     */
    private function formatearValoresSQL($valores)
    {
        $formateados = [];
        
        foreach ($valores as $valor) {
            // NULL en formato COPY
            if ($valor === '\\N' || $valor === '') {
                $formateados[] = 'NULL';
            } 
            // Booleano
            elseif (preg_match('/^(true|false|t|f)$/i', $valor)) {
                $formateados[] = (strtolower($valor) === 'true' || strtolower($valor) === 't') ? 'true' : 'false';
            }
            // Entero (sin punto decimal y longitud razonable)
            elseif (is_numeric($valor) && strpos($valor, '.') === false && strlen($valor) < 15 && $valor[0] !== '0') {
                $formateados[] = $valor;
            } 
            // Decimal
            elseif (is_numeric($valor)) {
                $formateados[] = $valor;
            } 
            // String: escapar comillas simples y caracteres especiales
            else {
                // Escapar comillas simples duplicandolas
                $valorEscapado = str_replace("'", "''", $valor);
                // Manejar caracteres especiales del formato COPY
                $valorEscapado = str_replace(
                    ['\\n', '\\r', '\\t', '\\\\'],
                    ["\n", "\r", "\t", "\\"],
                    $valorEscapado
                );
                $formateados[] = "'" . $valorEscapado . "'";
            }
        }
        
        return implode(', ', $formateados);
    }

    public function exportarANube()
    {
        try {
            Log::info('=== INICIANDO EXPORTACION A GOOGLE DRIVE ===');
            
            if (!$this->verificarRclone()) {
                Log::error('rclone no esta instalado');
                return back()->with('error', 'rclone no esta instalado. Por favor, instala rclone primero.');
            }
            
            $pgDumpPath = $this->verificarPostgreSQL();
            if (!$pgDumpPath) {
                Log::error('pg_dump no encontrado');
                return back()->with('error', 'PostgreSQL no esta instalado o no se encuentra pg_dump.');
            }
            
            Log::info('pg_dump encontrado en: ' . $pgDumpPath);
            
            if (!$this->verificarYCrearDirectorio()) {
                return back()->with('error', 'No se pudo verificar/crear el directorio en Google Drive.');
            }
            
            $directorioLocal = dirname($this->backupPath);
            if (!is_dir($directorioLocal)) {
                mkdir($directorioLocal, 0777, true);
                Log::info('Directorio local creado: ' . $directorioLocal);
            }
            
            Log::info('Exportando base de datos: ' . $this->dbName);
            Log::info('MODO: UPSERT - Actualiza existentes, inserta nuevos, sin duplicados');
            
            $pgPasswordCmd = $this->getPgPasswordCommand($this->dbPass);
            
            // Usar formato por defecto de pg_dump (COPY) que es mas eficiente
            if (strpos($pgDumpPath, '.exe') !== false) {
                $comando = $pgPasswordCmd . ' "' . $pgDumpPath . '" -h ' . $this->dbHost . ' -p ' . $this->dbPort . ' -U ' . $this->dbUser . ' --data-only ' . $this->dbName . ' -f "' . $this->backupPath . '"';
            } else {
                $comando = $pgPasswordCmd . ' pg_dump -h ' . $this->dbHost . ' -p ' . $this->dbPort . ' -U ' . $this->dbUser . ' --data-only ' . $this->dbName . ' -f "' . $this->backupPath . '"';
            }
            
            Log::info('Ejecutando pg_dump...');
            $resultadoExport = shell_exec($comando . ' 2>&1');
            Log::info('Resultado pg_dump: ' . ($resultadoExport ?: 'OK'));
            
            if (!file_exists($this->backupPath)) {
                Log::error('No se pudo crear el archivo de backup');
                return back()->with('error', 'Error al crear el respaldo. Revisar logs.');
            }
            
            $fileSize = filesize($this->backupPath);
            
            if ($fileSize == 0) {
                Log::error('El archivo de backup esta vacio');
                return back()->with('error', 'El archivo de backup esta vacio. Verificar credenciales.');
            }
            
            Log::info('Archivo creado: ' . $this->backupPath . ' (Tamano: ' . round($fileSize / 1024, 2) . ' KB)');

            Log::info('Subiendo archivo a Google Drive');
            
            $comandoRclone = 'rclone copy "' . $this->backupPath . '" ' . $this->cloudFolder . ' 2>&1';
            $resultadoRclone = shell_exec($comandoRclone);
            
            Log::info('Resultado rclone: ' . ($resultadoRclone ?: 'OK'));
            
            $comandoVerificar = 'rclone lsf ' . $this->cloudFolder . 'sincro_actual.sql 2>&1';
            $archivoEnNube = shell_exec($comandoVerificar);
            
            if (strpos($archivoEnNube, 'sincro_actual.sql') !== false) {
                Log::info('Exportacion completada exitosamente');
                return back()->with('success', 'Exportacion exitosa! Los datos se subieron a Google Drive (modo UPSERT habilitado).');
            } else {
                Log::warning('No se pudo verificar el archivo en Google Drive');
                return back()->with('warning', 'El archivo se proceso pero no se pudo verificar en Google Drive.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error exportando: ' . $e->getMessage());
            return back()->with('error', 'Error al sincronizar con Google Drive: ' . $e->getMessage());
        }
    }

    public function importarDeNube()
    {
        try {
            Log::info('=== INICIANDO IMPORTACION DESDE GOOGLE DRIVE ===');
            Log::info('MODO: UPSERT - Actualiza existentes, inserta nuevos, sin duplicados ni perdida de datos');
            
            if (!$this->verificarRclone()) {
                Log::error('rclone no esta instalado');
                return back()->with('error', 'rclone no esta instalado. Por favor, instala rclone primero.');
            }
            
            $rutasPosiblesPsql = [
                'C:\\Program Files\\PostgreSQL\\18\\bin\\psql.exe',
                'C:\\Program Files\\PostgreSQL\\17\\bin\\psql.exe',
                'C:\\Program Files\\PostgreSQL\\16\\bin\\psql.exe',
                'C:\\Program Files\\PostgreSQL\\15\\bin\\psql.exe',
                'C:\\Program Files\\PostgreSQL\\14\\bin\\psql.exe',
            ];
            
            $psqlPath = null;
            foreach ($rutasPosiblesPsql as $ruta) {
                if (file_exists($ruta)) {
                    $psqlPath = $ruta;
                    break;
                }
            }
            
            if (!$psqlPath) {
                $output = shell_exec('psql --version 2>&1');
                if ($output !== null && strpos($output, 'psql') !== false) {
                    $psqlPath = 'psql';
                } else {
                    Log::error('psql no encontrado');
                    return back()->with('error', 'PostgreSQL (psql) no esta instalado.');
                }
            }
            
            Log::info('psql encontrado en: ' . $psqlPath);
            
            $comandoCheck = 'rclone lsf ' . $this->cloudFolder . 'sincro_actual.sql 2>&1';
            $archivoExiste = shell_exec($comandoCheck);
            
            if (strpos($archivoExiste, 'sincro_actual.sql') === false) {
                Log::error('No se encontro el archivo de sincronizacion');
                return back()->with('error', 'No se encontro el archivo en Google Drive. Primero exporta desde la otra PC.');
            }
            
            Log::info('Descargando archivo desde Google Drive');
            
            $directorioLocal = dirname($this->backupPath);
            if (!is_dir($directorioLocal)) {
                mkdir($directorioLocal, 0777, true);
                Log::info('Directorio local creado: ' . $directorioLocal);
            }
            
            $comandoRclone = 'rclone copy ' . $this->cloudFolder . 'sincro_actual.sql "' . $directorioLocal . '" 2>&1';
            $resultadoRclone = shell_exec($comandoRclone);
            
            Log::info('Resultado descarga: ' . ($resultadoRclone ?: 'OK'));
            
            if (!file_exists($this->backupPath)) {
                Log::error('El archivo no se descargo');
                return back()->with('error', 'Error al descargar el archivo desde Google Drive.');
            }
            
            $fileSize = filesize($this->backupPath);
            Log::info('Archivo descargado: ' . $this->backupPath . ' (Tamano: ' . round($fileSize / 1024, 2) . ' KB)');

            $archivoProcessado = dirname($this->backupPath) . '\\sincro_procesado.sql';
            
            if (!$this->procesarSQLParaMerge($this->backupPath, $archivoProcessado)) {
                return back()->with('error', 'Error al procesar el archivo SQL para UPSERT.');
            }

            Log::info('Importando datos con UPSERT (actualiza existentes, inserta nuevos)');
            
            $pgPasswordCmd = $this->getPgPasswordCommand($this->dbPass);
            
            if (strpos($psqlPath, '.exe') !== false) {
                $comandoImport = $pgPasswordCmd . ' "' . $psqlPath . '" -h ' . $this->dbHost . ' -p ' . $this->dbPort . ' -U ' . $this->dbUser . ' -d ' . $this->dbName . ' -f "' . $archivoProcessado . '" 2>&1';
            } else {
                $comandoImport = $pgPasswordCmd . ' psql -h ' . $this->dbHost . ' -p ' . $this->dbPort . ' -U ' . $this->dbUser . ' -d ' . $this->dbName . ' -f "' . $archivoProcessado . '" 2>&1';
            }
            
            Log::info('Ejecutando psql...');
            $resultadoImport = shell_exec($comandoImport);
            
            Log::info('Resultado importacion: ' . substr($resultadoImport ?: '', 0, 500));
            
            // Contar operaciones realizadas
            $insertados = substr_count($resultadoImport, 'INSERT 0 1');
            $actualizados = 0;
            
            // ON CONFLICT DO UPDATE tambien retorna INSERT 0 1, pero podemos detectar por contexto
            // En la practica, PostgreSQL no diferencia en el output
            
            Log::info('Operaciones completadas: ' . $insertados . ' registros procesados');
            
            if (strpos($resultadoImport, 'FATAL') !== false || strpos($resultadoImport, 'ERROR') !== false) {
                Log::error('Errores durante la importacion: ' . $resultadoImport);
                return back()->with('error', 'Hubo errores durante la importacion. Verificar logs para detalles.');
            }

            // IMPORTANTE: Resetear secuencias despues de importar
            // Esto evita el error "llave duplicada viola restriccion de unicidad"
            Log::info('Sincronizando secuencias con los datos importados...');
            $secuenciasReseteadas = $this->resetearSecuencias();

            // Limpiar archivo temporal
            if (file_exists($archivoProcessado)) {
                unlink($archivoProcessado);
                Log::info('Archivo temporal eliminado');
            }

            Log::info('Importacion completada exitosamente');
            
            $mensaje = 'Importacion exitosa con UPSERT! ';
            $mensaje .= $insertados . ' registros procesados (insertados o actualizados). ';
            $mensaje .= $secuenciasReseteadas . ' secuencias sincronizadas. ';
            $mensaje .= 'No se elimino ningun dato existente.';
            
            return back()->with('success', $mensaje);
            
        } catch (\Exception $e) {
            Log::error('Error importando: ' . $e->getMessage());
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    public function infoSincronizacion()
    {
        try {
            $info = [
                'archivo_local_existe' => file_exists($this->backupPath),
                'tamano_local' => file_exists($this->backupPath) ? filesize($this->backupPath) : 0,
                'fecha_local' => file_exists($this->backupPath) ? date('Y-m-d H:i:s', filemtime($this->backupPath)) : 'N/A',
                'rclone_instalado' => $this->verificarRclone(),
                'modo' => 'UPSERT (actualiza existentes, inserta nuevos, sin duplicados)',
                'tablas_configuradas' => array_keys($this->tablasConfig),
            ];
            
            if ($info['rclone_instalado']) {
                $comandoCheck = 'rclone lsf ' . $this->cloudFolder . 'sincro_actual.sql --format pt 2>&1';
                $archivoNube = shell_exec($comandoCheck);
                
                $info['archivo_nube_existe'] = strpos($archivoNube, 'sincro_actual.sql') !== false;
            } else {
                $info['archivo_nube_existe'] = false;
            }
            
            return response()->json($info);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
