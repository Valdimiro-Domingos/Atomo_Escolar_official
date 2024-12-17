<?php

namespace App\Http\Controllers\Config\Company;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SeedersExcuteController;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Departament;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $companies = Company::where('id', Auth::user()->company_id)->first();
            return response(['companies' => CompanyResource::make($companies)], 200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_data = $request->only(
            'designation',
            'nif',
            'logo',
            'foundation_date',
            'share_capital',
            'contact',
            'description',
            'optional_contact',
            'representative_name',
            'representative_identification',
            'country',
            'city',
            'address',
            'email',
            'whatsapp',
            'facebook',
            'web_site',
        );
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/company'), $filename);
            $company_data['logo'] = $filename;
        } else {
            $company_data['logo'] = null;
        }

        try {
            $company = Company::create($company_data);
            $return = SeedersExcuteController::execute_seed($company->id);
            $user = new User();
            $user->company_id = $company->id;
            $user->name = $request->input('name_user');
            $user->email = $request->input('email_user');
            $user->password = Hash::make('12345678');
            $user->status = '1';
            $user->role_id = 1;
            $user->departament_id = Departament::where('company_id', '=', $company->id)->first()->id;
            $user->save();
            return response(['companies' => CompanyResource::collection(Company::get())], 200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            if ($th->getCode() == 23000) {
                return response(["message" => "Ocorreu um erro ao criar está Instituição.", 'companies' => $th->getMessage()], 500)->header('Content-type', 'application/json');
            } else {
                return response(['companies' => $th->getMessage(), "message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."], 500)->header('Content-type', 'application/json');
            }
        }
    }

    public function create(Request $request)
    {
        try {
            if (Company::where('nif', $request->input('nif'))->get()->count()) {
                return response(["message" => "Empresa já existente!"], 400)->header('Content-Type', 'application/json');
            } elseif (User::where('email', $request->input('email'))->get()->count()) {
                return response(["message" => "Utilizador já existente!"], 400)->header('Content-Type', 'application/json');
            } else {
                $company = new Company();
                $company->nif = $request->input('nif');
                $company->designation = $request->input('designation');
                $company->email = $request->input('email');
                $company->status = '1';
                $company->dateIssure = Carbon::now();
                $company->dateDue = Carbon::now()->copy()->addDays(8);

                $company->save();

                if ($this->init($company->id, $request) == true) {
                    return response(["message" => "Empresa Cadastrada"], 200)->header('Content-Type', 'application/json');
                }

            }
        } catch (Exception $th) {
            if ($th->getCode() == 23000) {
                return response(["message" => "Ocorreu um erro ao criar está Instituição."], 500)->header('Content-type', 'application/json');
            } else {
                return response(["message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.", 'error' => $th->getMessage()], 500)->header('Content-type', 'application/json');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            return response(['company' => CompanyResource::make($company)], 200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->designation = $request->input('designation');
            if (($request->input('nif') != $company->nif)) {
                $company->nif = $request->input('nif');
            }
            $company->foundation_date = $request->input('foundation_date') ?? $company->foundation_date;
            $company->share_capital = $request->input('share_capital') ?? $company->share_capital;
            $company->contact = $request->input('contact') ?? $company->contact;
            $company->description = $request->input('description') ?? $company->description;
            $company->representative_name = $request->input('representative_name') ?? $company->representative_name;
            $company->representative_identification = $request->input('representative_identification') ?? $company->representative_identification;
            $company->municipal_manager = $request->input('municipal_manager') ?? $company->municipal_manager;
            $company->provincial_manager = $request->input('provincial_manager') ?? $company->provincial_manager;
            $company->pedagogical_manager = $request->input('pedagogical_manager') ?? $company->pedagogical_manager;
            $company->general_manager = $request->input('general_manager') ?? $company->general_manager;
            $company->country = $request->input('country') ?? $company->country;
            $company->city = $request->input('city') ?? $company->city;
            $company->address = $request->input('address') ?? $company->address;
            $company->email = $request->input('email') ?? $company->email;
            $company->whatsapp = $request->input('whatsapp') ?? $company->whatsapp;
            $company->facebook = $request->input('facebook') ?? $company->facebook;
            $company->web_site = $request->input('web_site') ?? $company->web_site;

            $company->save();
            return response(['companies' => CompanyResource::make($company)], 200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error' => $th->getMessage()], 400)->header('Content-Type', 'application/json');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();
            return response(['companies' => CompanyResource::collection(Company::get())], 200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error' => $th->getMessage()], 400)->header('Content-Type', 'application/json');
        }
    }

    public function updateLogo(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        if ($request->hasFile('logo')) {

            if ($company->logo) {
                $logoPath = public_path('images/company') . '/' . $company->logo;
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }

            $file = $request->file('logo');
            $fileName = time() . $file->getClientOriginalName();
            $file->move(public_path('images/company'), $fileName);
            $company->update(['logo' => $fileName]);

            return response()->json(['message' => 'Arquivo enviado com sucesso!']);
        }
        return response()->json(['message' => 'Nenhum arquivo foi enviado.'], 400);
    }

    private function initOld($company_id, $request)
    {
        $roles = ['super admin', 'admin', 'pedagogica', 'secretaria', 'professor', 'finanças'];
        foreach ($roles as $role) {
            Role::create([
                'role' => $role,
                'guard' => $role,
                'company_id' => $company_id,
            ]);
        }

        $departament = Departament::create([
            'designation' => "Administração",
            'description' => 'Administração geral da Escola',
            'status' => '1',
            'company_id' => $company_id,
        ]);

        $user = new User();
        $user->company_id = $company_id;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make('12345678');
        $user->status = '1';
        $user->role_id = Role::where('company_id', $company_id)->where('role', 'super admin')->value('id');
        $user->departament_id = Departament::where('company_id', $company_id)->value('id');
        $user->save();
    }

    private function init($company_id, $request)
    {

        $roles = ['super admin', 'admin', 'pedagogica', 'secretaria', 'professor', 'finanças'];
        foreach ($roles as $role) {
            Role::create([
                'role' => $role,
                'guard' => $role,
                'company_id' => $company_id,
            ]);
        }

        $departament = Departament::create([
            'designation' => "Administração",
            'description' => 'Administração geral da Escola',
            'status' => '1',
            'company_id' => $company_id,
        ]);

        $user = new User();
        $user->company_id = $company_id;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make('12345678');
        $user->status = '1';
        $user->role_id = Role::where('company_id', $company_id)->where('role', 'super admin')->value('id');
        $user->departament_id = Departament::where('company_id', $company_id)->value('id');
        $user->save();

        $classes = ["ATL", "Iniciação"];
        for ($i = 0; $i <= 1; $i++) {
            DB::connection('mysql')->table('classes')->insert([
                'designation' => $classes[$i],
                'description' => $classes[$i],
                'status' => '1',
                'company_id' => $company_id,
            ]);
        }

        for ($i = 1; $i < 14; $i++) {
            DB::connection('mysql')->table('classes')->insert([
                'designation' => '' . $i,
                'description' => $i . '  Classe',
                'status' => '1',
                'company_id' => $company_id,
            ]);
        }

        for ($i = 1; $i < 6; $i++) {
            DB::connection('mysql')->table('class_rooms')->insert([
                'designation' => '' . $i,
                'description' => $i . '',
                'status' => '1',
                'company_id' => $company_id,
            ]);
        }

        DB::connection('mysql')->table('school_years')->insert([
            'designation' => '2023/2024',
            'description' => '2023/2024',
            'status' => '1',
            'company_id' => $company_id,
        ]);

        $turmas = ["A", "B"];
        foreach ($turmas as $turma) {
            DB::connection('mysql')->table('turmas')->insert([
                'designation' => $turma,
                'description' => $turma,
                'status' => '1',
                'company_id' => $company_id,
            ]);
        }

        DB::connection('mysql')->table('periods')->insert([
            'time_start' => '08:30:00',
            'time_end' => '12:00:00',
            'designation' => 'Manhã',
            'description' => ' MANHÃ',
            'status' => '1',
            'company_id' => $company_id,
        ]);

        DB::connection('mysql')->table('periods')->insert([
            'time_start' => '12:45:00',
            'time_end' => '17:00:00',
            'designation' => 'Tarde',
            'description' => ' TARDE',
            'status' => '1',
            'company_id' => $company_id,
        ]);
        DB::connection('mysql')->table('periods')->insert([
            'time_start' => '18:45:00',
            'time_end' => '21:00:00',
            'designation' => 'Noite',
            'description' => ' NOITE',
            'status' => '1',
            'company_id' => $company_id,
        ]);

        $disciplinas = ["Lngua Portuguesa", "Matemática", "Geografia", "Historia", "Educaço Artística", "Educaço Física", "Educao Moral e Cívica"];
        foreach ($disciplinas as $disciplina) {
            DB::connection('mysql')->table('disciplines')->insert([
                'designation' => $disciplina,
                'description' => ($disciplina),
                'status' => '1',
                'company_id' => $company_id,
            ]);
        }

        DB::connection('mysql')->table('courses')->insert([
            'designation' => "Sem Curso",
            'description' => "Sem Curso",
            'status' => '1',
            'company_id' => $company_id,
        ]);

        for ($i = 1; $i < 4; $i++) {
            DB::connection('mysql')->table('trimestres')->insert([
                'designation' => '' . $i,
                'description' => $i . ' ª trimestre',
                'status' => '1',
                'company_id' => $company_id,
            ]);
        }

        $article_types = ['Produto', 'Serviços'];
        foreach ($article_types as $article_type) {
            DB::connection('mysql')->table('article_types')->insert([
                'designation' => $article_type,
                'description' => $article_type,
                'company_id' => $company_id,
            ]);
        }

        $article_categorys = ['Matrículas', 'Confirmação', 'Propinas', 'Transporte', 'Vendas', 'Pagamento de Documentos', 'Diversos'];
        foreach ($article_categorys as $article_category) {
            DB::connection('mysql')->table('article_categories')->insert([
                'designation' => $article_category,
                'description' => $article_category,
                'company_id' => $company_id,
            ]);
        }

        $retentions = ['Sem reteno na fonte', 'Reteno na fonte 6.5%'];
        foreach ($retentions as $retention) {
            DB::connection('mysql')->table('retentions')->insert([
                'designation' => $retention,
                'description' => $retention,
                'company_id' => $company_id,
            ]);
        }

        DB::connection('mysql')->table('taxes')->insert([
            'designation' => "M00",
            'description' => "M00 - Regime Simplificado",
            'status' => "1",
            'company_id' => $company_id,
        ]);
        DB::connection('mysql')->table('taxes')->insert([
            'designation' => "M02",
            'status' => "1",
            'description' => "M02 - Transmissão de bens e servio não sujeita",
            'company_id' => $company_id,
        ]);

        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
            10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Serviços')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Propinas')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();
        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }

        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Serviços')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Transporte')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();

        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }

        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Produto')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Vendas')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();
        $meses = ['Folha de Prova', 'Bata', 'Uniforme de Educaão Física', 'Uniforme'];
        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }

        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Produto')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Diversos')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();
        $meses = ['Carto de Propina', 'Passe de estudante', 'Taxa/Seguro', 'Actividade Extra Currilucar'];
        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }

        $meses = ['Declaraço S/Nota', 'Declaraão C/Nota', 'Certificado', 'Diploma', 'Transferência', 'Boletim de Nota', 'Termo'];
        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Serviços')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Pagamento de Documentos')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();

        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }
        
        $article_meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        foreach ($article_meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => DB::connection('mysql')->table('article_categories')->where('designation', 'Propinas')->first()->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }

        $meses = ['Pagamento de matricula'];
        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Serviços')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Matrículas')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();

        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        }

        $article_type = DB::connection('mysql')->table('article_types')->where('company_id', $company_id)->where('designation', 'Serviços')->first();
        $article_category = DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Confirmação')->first();
        $tax = DB::connection('mysql')->table('taxes')->where('company_id', $company_id)->first();
        $retention = DB::connection('mysql')->table('retentions')->where('company_id', $company_id)->first();
        $meses = ['Pagamento de matrícula'];

        foreach ($meses as $mes) {
            DB::connection('mysql')->table('articles')->insert([
                'designation' => $mes,
                'description' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => DB::connection('mysql')->table('article_categories')->where('company_id', $company_id)->where('designation', 'Confirmação')->first()->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id' => $company_id,
            ]);
        } /*   */

        $form_of_pays = ['Deposito', 'TPA', 'Transferência', 'Dinheiro'];
        foreach ($form_of_pays as $form_of_pay) {
            DB::connection('mysql')->table('form_of_payments')->insert([
                'designation' => $form_of_pay,
                'description' => $form_of_pay,
                'company_id' => $company_id,
            ]);
        }

        return true;
    }
}
