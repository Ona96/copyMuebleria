<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestRclone extends Command
{
    protected $signature = 'app:test-rclone';
    protected $description = 'Prueba de rclone desde PHP/Laravel';

    public function handle()
    {
        echo "INICIO DE PRUEBA\n";
        echo "================\n\n";
        
        // 1. Test básico
        echo "1. rclone version:\n";
        $version = shell_exec('rclone version 2>&1');
        echo $version . "\n\n";
        
        // 2. Listar raíz de Google Drive
        echo "2. Archivos en raíz de Google Drive:\n";
        $root = shell_exec('rclone lsf muebleria-Marquez: 2>&1');
        echo $root . "\n\n";
        
        // 3. Listar carpeta SincronizacionManual
        echo "3. Archivos en SincronizacionManual:\n";
        $folder = shell_exec('rclone lsf muebleria-Marquez:SincronizacionManual/ 2>&1');
        echo $folder . "\n\n";
        
        // 4. Listar con detalles (ls)
        echo "4. Detalles de archivos (ls):\n";
        $ls = shell_exec('rclone ls muebleria-Marquez:SincronizacionManual/ 2>&1');
        echo $ls . "\n\n";
        
        // 5. Crear archivo de prueba
        echo "5. Creando archivo de prueba...\n";
        $testFile = 'C:\\Proyecto-muebleria\\backups\\test_php.txt';
        file_put_contents($testFile, 'Test desde PHP - ' . date('Y-m-d H:i:s'));
        echo "Archivo creado: {$testFile}\n\n";
        
        // 6. Subir archivo
        echo "6. Subiendo archivo a Google Drive...\n";
        $upload = shell_exec("rclone copy \"{$testFile}\" muebleria-Marquez:SincronizacionManual/ --verbose 2>&1");
        echo $upload . "\n\n";
        
        // 7. Esperar y verificar
        echo "7. Esperando 2 segundos...\n";
        sleep(2);
        
        echo "8. Verificando archivo en la nube:\n";
        $verify = shell_exec('rclone lsf muebleria-Marquez:SincronizacionManual/ 2>&1');
        echo $verify . "\n\n";
        
        // 9. Resultado
        if (strpos($verify, 'test_php.txt') !== false) {
            echo "✅ EXITO! El archivo se subio correctamente\n";
        } else {
            echo "❌ ERROR: El archivo no se encuentra\n";
        }
        
        echo "\nFIN DE PRUEBA\n";
        
        return 0;
    }
}