@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-house-door"></i> Menú Principal</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('bancos.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-bank fs-2"></i><br>Bancos
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('cheques.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-cash-coin fs-2"></i><br>Cheques
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('facturas.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-receipt fs-2"></i><br>Facturas
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-truck fs-2"></i><br>Proveedores
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('proyecciones.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-graph-up-arrow fs-2"></i><br>Proyección Cuentas
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('usersAdmin.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-people fs-2"></i><br>Usuarios Admin
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('/sincronizacion') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-arrow-repeat fs-2"></i><br>Sincronización PC ↔ Notebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection