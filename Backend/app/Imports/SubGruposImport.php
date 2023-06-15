<?php

namespace App\Imports;

use App\Models\Inventario\SubGrupos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubGruposImport implements ToModel, WithStartRow
{
    // Declaramos contador de filas
    private $rows = 0;
    /**
     * @param array $row
     *
     * @return Model|null
     */

    public function model(array $row)
    {
        return new SubGrupos([
            'id_grupo' => $row[0],
            'codigo' => $row[1],
            'descripcion' => $row[2],
            'estado' => 1,
            'u_creacion' => Auth::user()->name,
            'u_modificacion' => null,
        ]);
    }


    public function getRowCount(): int
    {
        return $this->rows;
    }

    /**
     * @inheritDoc
     */
    public function startRow(): int
    {
        return 2;
    }
}
