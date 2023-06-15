<?php

namespace App\Imports;


use App\Models\Inventario\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;


class ListasPreciosImport implements ToModel, WithStartRow, WithUpsertColumns, WithUpserts, SkipsEmptyRows
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
        foreach ($row as $field) {
            if (empty($field) || is_null($field)) {
                return null; // Si algún campo está vacío o nulo, se omitirá la actualización
            }
        }

        ++$this->rows; //Se incrementa contador de filas luego de cada insercion o registro actualizado

        return new Productos([
            'codigo_sistema' => $row[0],
            'nombre_comercial' => $row[1],
            'precio_compra' => $row[2],
            'costo_estandar' => $row[2],
            'precio_sugerido' => $row[3],
            'precio_distribuidor' => $row[4],
            'u_modificacion' => Auth::user()->name,
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

    public function upsertColumns()
    {
        return ['precio_compra', 'costo_estandar', 'precio_sugerido', 'precio_distribuidor', 'u_modificacion'];
    }

    public function uniqueBy()
    {
        return ['codigo_sistema'];
    }
}
