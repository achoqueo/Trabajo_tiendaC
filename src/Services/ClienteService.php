<?php

namespace App\Services;

use Exception;

class ClienteService
{
    /**
     * Registra un nuevo cliente validando los datos requeridos (Clean Code).
     */
    public function registrarCliente(array $datosCliente, array $clientesExistentes = []): array
    {
        $this->validarCamposObligatorios($datosCliente);
        $this->validarFormatoEmail($datosCliente['email']);
        $this->validarDocumentoUnico($datosCliente['numeroDocumento'], $clientesExistentes);

        return [
            'estado' => 'REGISTRADO',
            'cliente' => [
                'tipoDocumento' => $datosCliente['tipoDocumento'],
                'numeroDocumento' => $datosCliente['numeroDocumento'],
                'nombres' => trim($datosCliente['nombres']),
                'apellidos' => trim($datosCliente['apellidos']),
                'telefono' => $datosCliente['telefono'] ?? null,
                'email' => strtolower(trim($datosCliente['email'])),
                'direccion' => $datosCliente['direccion'] ?? null
            ]
        ];
    }

    private function validarCamposObligatorios(array $datos): void
    {
        $camposRequeridos = ['tipoDocumento', 'numeroDocumento', 'nombres', 'apellidos', 'email'];

        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo]) || trim($datos[$campo]) === '') {
                throw new Exception("CAMPO_OBLIGATORIO_FALTANTE_" . strtoupper($campo));
            }
        }
    }

    private function validarFormatoEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("FORMATO_EMAIL_INVALIDO");
        }
    }

    private function validarDocumentoUnico(string $numeroDocumento, array $clientesExistentes): void
    {
        if (in_array($numeroDocumento, $clientesExistentes, true)) {
            throw new Exception("CLIENTE_YA_EXISTE");
        }
    }
}