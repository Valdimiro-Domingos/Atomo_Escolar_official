<?php

use App\Models\Company;
use App\Models\MiniSchedule;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('viewLogo/{id}', function ($id) {
    
    $company = Company::findOrFail($id);
    $logoPath = public_path('images/company') . '/' . $company->logo;

    if (file_exists($logoPath)) {
        $fileContents = file_get_contents($logoPath);
        $response = response($fileContents, 200);
        $response->header('Content-Type', 'image/png'); // Altere o tipo de conteúdo conforme necessário
        return $response;
    }

    return response()->json(['message' => 'Arquivo não encontrado.'], 404);
});



// Rota para visualizar um arquivo
Route::get('viewFile/{id}', function ($id) {
    // Caminho completo do arquivo
    
    $logoPath = public_path('secretary/shedule/' . $id);

    // Verifica se o arquivo existe
    if (file_exists($logoPath)) {
        // Obtém o conteúdo do arquivo
        $fileContents = file_get_contents($logoPath);

        // Determina o tipo MIME do arquivo
        $mimeType = mime_content_type($logoPath);

        // Cria a resposta com o conteúdo do arquivo e o tipo MIME
        $response = response($fileContents, 200)
            ->header('Content-Type', $mimeType);

        return $response;
    }

    // Retorna um erro 404 se o arquivo não for encontrado
    return redirect('https: //escolar.atomo.co.ao/');
});


