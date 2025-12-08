<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuestsImport implements ToModel, WithValidation
{
    public function rules(): array
    {
        return [
            '0' => 'required|numeric', // event_id (columna A)
            '1' => 'required|string',  // name (columna B)
            '2' => 'required|string',  // last_name (columna C)
            '3' => 'required|email',   // email (columna D)
        ];
    }

    public function model(array $row)
    {
        // Generar un code_in único
        $codeIn = $this->generateUniqueCode();

        return new Guest([
            'event_id' => $row[0],
            'name'     => $row[1],
            'last_name' => $row[2],
            'email'     => $row[3],
            'code_in'   => $codeIn, // Campo generado automáticamente
        ]);
    }

    /**
     * Genera un código único con formato EVT-{random}.
     * 
     * @return string
     */
    private function generateUniqueCode(): string
    {
        do {
            $random = Str::random(15); // 13 caracteres aleatorios (ej: 67f860880e6f9)
            $codeIn = 'EVT-' . $random;
        } while (Guest::where('code_in', $codeIn)->exists()); // Verificar que no exista

        return $codeIn;
    }
}