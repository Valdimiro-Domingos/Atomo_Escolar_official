<?php

namespace Database\Seeders;

use App\Models\Retention;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RetentionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
    public static function initial_retentions($company_id){
        // DB::table('impostos')->insert([
        //     [ 'designation' => 'M00 - Regime Simplificado', 'status' => true],
        //     [ 'designation' => 'IVA', 'taxa' => 14, 'motivo' => '', 'status' => true],
        //     [ 'designation' => 'M02 - Transmissão de bens e serviço não sujeita', 'status' => true],
        //     [ 'designation' => 'M04 - Regime de Exclusão', 'status' => true],
        //     [ 'designation' => 'M11 - Isento nos termos da alínea b) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea b) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M12 - Isento nos termos da alínea c) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea c) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M13 - Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M14 - Isento nos termos da alínea e) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea e) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M15 - Isento nos termos da alínea f) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea f) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M16 - Isento nos termos da alínea g) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea g) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M17 - Isento nos termos da alínea h) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea h) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M18 - Isento nos termos da alínea i) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea i) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M19 - Isento nos termos da alínea j) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea j) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M20 - Isento nos termos da alínea k) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea k) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M21 - Isento nos termos da alínea l) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea l) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M22 - Isento nos termos da alínea m) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea m) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M23 - Isento nos termos da alínea n) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea n) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M24 - Isento nos termos da alínea o) do nº1 do artigo 12.º do CIVA', 'motivo' => 'Isento nos termos da alínea o) do nº1 do artigo 12.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M80 - Isento nos termos da alínea a) do nº1 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea a) do nº1 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M81 - Isento nos termos da alínea b) do nº1 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea b) do nº1 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M82 - Isento nos termos da alínea c) do nº1 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea c) do nº1 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M83 - Isento nos termos da alínea d) do nº1 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea d) do nº1 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M84 - Isento nos termos da alínea e) do nº1 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea e) do nº1 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M85 - Isento nos termos da alínea a) do nº2 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea a) do nº2 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M86 - Isento nos termos da alínea b) do nº2 do artigo 14.º do CIVA', 'motivo' => 'Isento nos termos da alínea b) do nº2 do artigo 14.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M30 - Isento nos termos da alínea a) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea a) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M31 - Isento nos termos da alínea b) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea b) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M32 - Isento nos termos da alínea c) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea c) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M33 - Isento nos termos da alínea d) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea d) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M34 - Isento nos termos da alínea e) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea e) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M35 - Isento nos termos da alínea f) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea f) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M36 - Isento nos termos da alínea g) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea g) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M37 - Isento nos termos da alínea h) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea h) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M38 - Isento nos termos da alínea i) do artigo 15.º do CIVA', 'motivo' => 'Isento nos termos da alínea i) do artigo 15.º do CIVA', 'status' => true],
        //     [ 'designation' => 'M90 - Isento nos termos da alinea a) do nº1 do artigo 16.º', 'motivo' => 'Isento nos termos da alinea a) do nº1 do artigo 16.º', 'status' => true],
        //     [ 'designation' => 'M91 - Isento nos termos da alinea b) do nº1 do artigo 16.º', 'motivo' => 'Isento nos termos da alinea b) do nº1 do artigo 16.º', 'status' => true],
        //     [ 'designation' => 'M92 - Isento nos termos da alinea c) do nº1 do artigo 16.º', 'motivo' => 'Isento nos termos da alinea c) do nº1 do artigo 16.º', 'status' => true],
        //     [ 'designation' => 'M93 - Isento nos termos da alinea d) do nº1 do artigo 16.º', 'motivo' => 'Isento nos termos da alinea d) do nº1 do artigo 16.º', 'status' => true],
        //     [ 'designation' => 'M94 - Isento nos termos da alinea e) do nº1 do artigo 16.º', 'motivo' => 'Isento nos termos da alinea e) do nº1 do artigo 16.º', 'status' => true],
        // ]);
        $retentions = ['Sem retenção na fonte','Retenção na fonte 6.5%'];
        foreach ($retentions as $retention) {
            Retention::create([
                'designation' => $retention,
                'description' => $retention,
                'company_id'=> $company_id,
            ]);
        }
    }
}
