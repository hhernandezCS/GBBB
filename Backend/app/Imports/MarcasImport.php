<?php

namespace App\Imports;

use App\Models\Inventario\Marcas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MarcasImport implements ToModel, WithStartRow
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
        //Se incrementa contador de filas luego de cada insercion
        ++$this->rows;
        return new Marcas([
            'descripcion' => $row[1],
            'estado' => 1,
            'u_creacion' => Auth::user()->name,
            'u_modificacion' => null,
            'id_empresa' => 1
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
