<?php

use PHPUnit\Framework\TestCase;
use App\Services\ClienteService;

class ClienteServiceTest extends TestCase
{
    private ClienteService $clienteService;

    protected function setUp(): void
    {
        $this->clienteService = new ClienteService();
    }

    public function testRegistrarClienteExitoso(): void
    {
        $datosCliente = [
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '70654321',
            'nombres' => 'Maria',
            'apellidos' => 'Perez',
            'telefono' => '952000111',
            'email' => 'maria.perez@example.com'
        ];

        $resultado = $this->clienteService->registrarCliente($datosCliente);

        $this->assertEquals('REGISTRADO', $resultado['estado']);
        $this->assertEquals('70654321', $resultado['cliente']['numeroDocumento']);
        $this->assertEquals('maria.perez@example.com', $resultado['cliente']['email']);
    }

    public function testErrorPorDocumentoDuplicado(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("CLIENTE_YA_EXISTE");

        $datosCliente = [
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '70654321',
            'nombres' => 'Maria',
            'apellidos' => 'Perez',
            'email' => 'maria.perez@example.com'
        ];

        $clientesExistentes = ['70654321'];

        $this->clienteService->registrarCliente($datosCliente, $clientesExistentes);
    }
}