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

    public function testRechazaRegistroConDniLongitudInvalida()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("FORMATO_DNI_INVALIDO");
    
        $datosCliente = [
            'tipoDocumento'   => 'DNI',
            'numeroDocumento' => '12345', // Menos de 8 dígitos
            'nombres'         => 'Ana',
            'apellidos'       => 'Torres',
            'email'           => 'ana.torres@example.com'
        ];
    
        $this->clienteService->registrarCliente($datosCliente, []);
    }

    public function testRechazaRegistroConEmailInvalido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("FORMATO_EMAIL_INVALIDO");
    
        $datosCliente = [
            'tipoDocumento'   => 'DNI',
            'numeroDocumento' => '70654321',
            'nombres'         => 'Ana',
            'apellidos'       => 'Torres',
            'email'           => 'ana.torres.com' // Sin @
        ];
    
        $this->clienteService->registrarCliente($datosCliente, []);
    }
}
