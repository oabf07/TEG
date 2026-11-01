<?php

session_start();

require '../vendor/flight/autoload.php';
require '../vendor/flight/Flight.php';
require '../config/database.php';
require '../app/models/Socio.php';
require '../app/models/ConceptoPago.php';
require '../app/models/Periodo.php';
require '../app/models/Pago.php';
require '../app/controllers/SociosController.php';
require '../app/controllers/ConceptosController.php';
require '../app/controllers/FinanzasController.php';
require '../app/controllers/PagosController.php';
require '../app/models/Morosidad.php';
require '../app/controllers/MorosidadController.php';
require '../app/models/Solvencia.php';
require '../app/controllers/SolvenciasController.php';
require '../app/Auth.php';
require '../app/models/Usuario.php';
require '../app/controllers/AuthController.php';
require '../app/controllers/DashboardController.php';
require '../app/models/Vehiculo.php';
require '../app/controllers/VehiculosController.php';

define('BASE_URL', '/TEG/public');

// Socios
Flight::route('GET /socios', ['SociosController', 'listar']);
Flight::route('GET /socios/nuevo', ['SociosController', 'formularioNuevo']);
Flight::route('POST /socios', ['SociosController', 'guardar']);
Flight::route('GET /socios/editar/@id', ['SociosController', 'formularioEditar']);
Flight::route('POST /socios/editar/@id', ['SociosController', 'actualizar']);
Flight::route('GET /socios/eliminar/@id', ['SociosController', 'eliminar']);
Flight::route('GET /socios/reactivar/@id', ['SociosController', 'reactivar']);
Flight::route('GET /socios/inactivos', ['SociosController', 'listarInactivos']);

// Conceptos de Pago
Flight::route('GET /conceptos', ['ConceptosController', 'listar']);
Flight::route('GET /conceptos/nuevo', ['ConceptosController', 'formularioNuevo']);
Flight::route('POST /conceptos', ['ConceptosController', 'guardar']);
Flight::route('GET /conceptos/editar/@id', ['ConceptosController', 'formularioEditar']);
Flight::route('POST /conceptos/editar/@id', ['ConceptosController', 'actualizar']);
Flight::route('GET /conceptos/eliminar/@id', ['ConceptosController', 'eliminar']);

// Finanzas / Periodos
Flight::route('GET /finanzas', ['FinanzasController', 'listar']);
Flight::route('GET /finanzas/generar', ['FinanzasController', 'formularioGenerar']);
Flight::route('POST /finanzas/generar', ['FinanzasController', 'generar']);

// Pagos
Flight::route('GET /pagos', ['PagosController', 'buscarSocio']);
Flight::route('GET /pagos/socio/@id', ['PagosController', 'verCargos']);
Flight::route('POST /pagos', ['PagosController', 'registrar']);
Flight::route('GET /pagos/comprobante/@id', ['PagosController', 'verComprobante']);

// Morosidad
Flight::route('GET /morosidad', ['MorosidadController', 'reporte']);

// Solvencias
Flight::route('GET /solvencias', ['SolvenciasController', 'buscarSocio']);
Flight::route('GET /solvencias/socio/@id', ['SolvenciasController', 'verSocio']);
Flight::route('POST /solvencias/emitir/@id', ['SolvenciasController', 'emitir']);
Flight::route('GET /solvencias/constancia/@id', ['SolvenciasController', 'verConstancia']);

// Vehiculos
Flight::route('GET /vehiculos', ['VehiculosController', 'listar']);
Flight::route('GET /vehiculos/nuevo', ['VehiculosController', 'formularioNuevo']);
Flight::route('POST /vehiculos', ['VehiculosController', 'guardar']);
Flight::route('GET /vehiculos/editar/@id', ['VehiculosController', 'formularioEditar']);
Flight::route('POST /vehiculos/editar/@id', ['VehiculosController', 'actualizar']);
Flight::route('GET /vehiculos/desactivar/@id', ['VehiculosController', 'desactivar']);
Flight::route('GET /vehiculos/reactivar/@id', ['VehiculosController', 'reactivar']);

// Autenticacion
Flight::route('GET /login', ['AuthController', 'formularioLogin']);
Flight::route('POST /login', ['AuthController', 'login']);
Flight::route('GET /logout', ['AuthController', 'logout']);
Flight::route('GET /usuarios', ['AuthController', 'listarUsuarios']);
Flight::route('GET /usuarios/nueva-secretaria', ['AuthController', 'formularioNuevaSecretaria']);
Flight::route('POST /usuarios/nueva-secretaria', ['AuthController', 'guardarSecretaria']);

//Dashboard
Flight::route('GET /inicio', ['DashboardController', 'inicio']);

Flight::route('/', function () {
    Flight::redirect(BASE_URL . '/socios');
});

Flight::start();