@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">
                    <i class="bi bi-cloud-arrow-up-down text-primary"></i>
                    Sincronización de Datos
                </h1>
                <p class="lead text-muted">Mantén sincronizadas la PC de escritorio y la notebook</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>¡Éxito!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Error:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <strong>Advertencia:</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4 mb-5">
                
                <div class="col-md-6">
                    <div class="card border-0 shadow-lg h-100 hover-card">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <i class="bi bi-cloud-upload display-1 text-primary"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Exportar a la Nube</h3>
                            <p class="card-text text-muted mb-4">
                                Guarda los datos actuales de esta PC en Google Drive. 
                                El archivo anterior será reemplazado automáticamente.
                            </p>
                            <form method="POST" action="{{ route('backup.exportar') }}" id="formExportar">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                    <i class="bi bi-upload me-2"></i>
                                    Exportar Ahora
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-lg h-100 hover-card">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <i class="bi bi-cloud-download display-1 text-success"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Importar desde la Nube</h3>
                            <p class="card-text text-muted mb-4">
                                Descarga los datos de la otra PC desde Google Drive. 
                                <strong class="text-danger">¡ATENCIÓN!</strong> Esto mezclara los datos actuales con los nuevos.
                            </p>
                            <form method="POST" action="{{ route('backup.importar') }}" id="formImportar">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                                    <i class="bi bi-download me-2"></i>
                                    Importar Ahora
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-3">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Información Importante
                    </h4>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Los archivos se guardan en: <code>C:\Proyecto-muebleria\backups\sincro_actual.sql</code>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            La carpeta en Google Drive es: <code>SincronizacionManual</code>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Esta sincronización <strong>NO afecta</strong> los backups automáticos diarios
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Siempre se sobrescribe el mismo archivo (sin generar basura)
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Asegurate de tener <strong>rclone</strong> instalado y configurado
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver al Dashboard
                </a>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <h5 class="fw-bold mb-2">Procesando...</h5>
                <p class="text-muted mb-0">Esto puede tardar unos segundos. Por favor, no cierres esta ventana.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        
        document.getElementById('formExportar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm(
                '¿Estás seguro de exportar los datos actuales a Google Drive?\n\n' +
                'Esta acción:\n' +
                '✓ Guardará la base de datos actual\n' +
                '✓ Subirá el archivo a Google Drive\n' +
                '✓ Reemplazará el archivo anterior\n\n' +
                '¿Continuar?'
            )) {
                loadingModal.show();
                this.submit();
            }
        });
        
        document.getElementById('formImportar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const confirmar = confirm(
                '⚠️ ¡ATENCIÓN! ⚠️\n\n' +
                'Esta acción MEZCLARA TODOS LOS DATOS ACTUALES con los datos de la otra PC.\n\n' +
                '¿Estás COMPLETAMENTE SEGURO de continuar?'
            );
            
            if (confirmar) {
                const reconfirmar = confirm(
                    'Última confirmación:\n\n' +
                    '¿Realmente querés mezclar todos los datos actuales?'
                );
                
                if (reconfirmar) {
                    loadingModal.show();
                    this.submit();
                }
            }
        });
    });
</script>
@endsection