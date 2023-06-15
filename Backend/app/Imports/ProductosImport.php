<?php

namespace App\Imports;


use App\Models\Inventario\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;


class ProductosImport implements ToModel, WithStartRow, WithUpsertColumns, WithUpserts
{
    // Declaramos contador de filas
    private $rows = 0;
    private $consecutivo = 0;
    /**
    * @param array $row
    *
    * @return Model|null
    */

    public function model(array $row)
    {


        $codigo = Productos::select([DB::raw("COALESCE(max(codigo_consecutivo),0)+1 as codigo_siguiente")])
            ->first()
            ->codigo_siguiente;

        $codigo_sistema_obj = DB::selectOne('SELECT inventario.obtener_descripcion_grupo(?) as descripcion', [$row[5]]);
        $codigo_sistema_str = $codigo_sistema_obj->descripcion;
        $codigo_sistema_str .= strval($codigo);

        //Se incrementa contador de filas luego de cada insercion
        ++$this->rows;
        $this->consecutivo++;

        return new Productos([
            'codigo_consecutivo' =>$codigo,
            'codigo_sistema' => $codigo_sistema_str,
            /*'codigo_sistema' => 'PRD' . strval($codigo),*/
            'descripcion' => $row[1],
            'nombre_comercial' => $row[0],
            'codigo_barra' => $row[2],
            'costo_estandar' => 0,
            'precio_sugerido' => $row[3],
            'precio_distribuidor' => $row[4],
            'precio_compra' => $row[5],
            'id_unidad_medida' => '1',
            'id_tipo_producto' => '1',
            'id_impuesto' => '2',
            'estado' => '1',
            'u_creacion' => Auth::user()->name,
            'u_modificacion' => null,
            'id_empresa' => '1',
            'condicion' => '1',
            'id_marca' => '1',
            'tipo_servicio' => '1',
            'costo_estandar_me' => 0,
            'id_grupo' => $row[6],
            'id_subgrupo' => $row[7],
            'cantidad_inicial' => $row[8],
            'existencia_min' => 1,
            'existencia_max' => 100,
            'porcentaje_ganancia' => 0,
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
        return ['descripcion', 'nombre_comercial','id_grupo','id_subgrupo'];
    }

    public function uniqueBy()
    {
        return ['codigo_barra'];
    }
}
