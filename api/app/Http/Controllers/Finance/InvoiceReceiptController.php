<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImportController;
use App\Http\Resources\InvoiceReceiptResource;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Company;
use App\Models\Enrollment;
use App\Models\InvoiceReceipt;
use App\Models\InvoiceReceiptItens;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceReceiptController extends Controller
{
    private $imports;

    public function __construct()
    {
        $this->imports = new ImportController();
    }

    public function last_invoice()
    {
        try {
            $invoice_number = InvoiceReceipt::where('company_id', Auth::user()->company_id)->count() + 1;
            return response(
                ['invoice_number' => 'FR ' . date('Y') . '/' . $invoice_number], 200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(["message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
                'invoice_number' => $th->getMessage()], 500)->header('Content-type', 'application/json');
        }
    }

    public function index()
    {
        try {
            $invoice_receipts = InvoiceReceipt::where('company_id', Auth::user()->company_id)->orderByDesc('id')->get();
            $student = Student::where('company_id', Auth::user()->company_id)->get();

            return response(
                ['invoice_receipts' => InvoiceReceiptResource::collection($invoice_receipts), 'studants' => $student], 200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(["message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
                'invoice_receipts' => $th->getMessage()], 500)->header('Content-type', 'application/json');
        }
    }

    public function view()
    {
        try {
            $this->updateItems();

            $company = Auth::user()->company_id;
            $invoice_receipts = InvoiceReceipt::with('student')->where('company_id', $company)->orderByDesc('id')->get();

            return response(
                [
                    'invoice_receipts' => InvoiceReceiptResource::collection($invoice_receipts),
                    'studants' => $this->imports->include('student'),
                    'form_payment' => $this->imports->include('payment'),
                    "articles" => $this->imports->include('article'),
                    "categories" => $this->imports->include('category'),
                    "invoice_number" => count($this->imports->include('invoice')) + 1,
                ], 200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(["message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
                'invoice_receipts' => $th->getMessage()], 500)->header('Content-type', 'application/json');
        }
    }

    public function store(Request $request)
    {
        if (count($request->itens) <= 0) {

            return response()->json(["message" => "Não foi adicionado nenhum artigo na fatura", 500]);
        }

        try {

            $student = Student::findOrFail(($request->input('enrollment_id')));
            $itens = $request->itens;
            $invoice_last = InvoiceReceipt::where('company_id', Auth::user()->company->id)->latest()->first();

            if ($invoice_last) {
                $processo = explode("/", $invoice_last->invoice_number);

                // Obtm o ano atual
                $currentYear = date("Y");

                // Obtém o último ano da fatura
                $lastYear = substr($processo[0], 3);

                if ($currentYear != $lastYear) {
                    // Se o ano atual for diferente do último ano da fatura, define um novo número de processo
                    $newProcess = 'FR ' . $currentYear . "/" . "0001";
                } else {
                    // Se o ano atual for igual ao último ano da fatura, incrementa o número do último processo em 1
                    $newProcess = 'FR ' . $currentYear . "/" . ($processo[1] + 1);
                }
            }
            if (!$invoice_last) {
                $newProcess = 'FR ' . date('Y') . "/0001"; // Adapte conforme necessário
            }

            // Criaca de fatura recibo

            // Helpers::setClientesUsed($student->id);
            $invoice_receipt = new InvoiceReceipt();
            $invoice_receipt->invoice_number = $newProcess;
            $invoice_receipt->date_of_issue = $request->input('date_of_issue');
            $invoice_receipt->due_date = $request->input('due_date') ?? date('Y-m-d');
            $invoice_receipt->coin = $request->input('coin');
            $invoice_receipt->student_id = $student->id;
            $invoice_receipt->client_name = $student->name;
            $invoice_receipt->form_of_payment_id = $request->input('form_of_payment_id');
            // $invoice_receipt->hash = Helpers::assign($invoice_receipt, 'factura-recibo', Auth::user()->company_id);
            $invoice_receipt->user_id = Auth::user()->id;
            $invoice_receipt->company_id = Auth::user()->company_id;
            $invoice_receipt->save();

            $tax = 0;
            $total = 0;
            $discount = 0;

            foreach ($itens as $i => $item) {
                unset($item->price);
                $itens[$i] = $item;
                $artigo = Article::findOrFail($item['article_id']);

                $itemTotal = $item['qtd'] * $artigo->price;
                $total += $itemTotal;

                // Calculando o desconto para este item
                $itemDiscount = ($artigo->price * $item['discount']) / 100;
                $discount += $itemDiscount;

                // Adicionando a taxa para este item
                $tax += ($itemTotal - $itemDiscount) * ($item['rate'] / 100);

                $invoiceItens = new InvoiceReceiptItens();
                $invoiceItens->qtd = $item['qtd'];
                $invoiceItens->rate = $item['rate'];
                $invoiceItens->discount = $itemDiscount; // Salvar o desconto correto
                $invoiceItens->article_designation = $artigo->designation;
                $invoiceItens->category_id = ($artigo->article_category_id);
                $invoiceItens->category_designation = ArticleCategory::find($artigo->article_category_id)->designation;

                // Calcular o total pago por este item (considerando desconto)
                $itemPaid = $itemTotal - $itemDiscount;
                $invoiceItens->paid = $itemPaid;

                $invoiceItens->price = $artigo->price;
                $invoiceItens->article_id = $item['article_id'];
                $invoiceItens->invoice_receipt_id = $invoice_receipt->id;
                $invoiceItens->company_id = $invoice_receipt->company_id;
                $invoiceItens->save();
            }

            $invoice_receipt->update(['total' => $total, 'discount' => $discount, 'tax' => $tax]);

            return response([
                'invoice_receipts' => InvoiceReceiptResource::collection(
                    InvoiceReceipt::where('company_id', '=', Auth::user()->company_id)->get()
                ),
            ], 200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            // if ($th->getCode() == 0) {
            //     return response(['invoice_receipts' => $th->getMessage(), 'message' => 'Verifica se o Estudante, Matricula ou a forma de pagamento existem!'], 400)->header('Content-type', 'application/json');
            // }
            
            return response(['invoice_receipts' => [], 'code' => 500, 'message' => 'Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.'], 500)->header('Content-type', 'application/json');
        }
    }

    public function students($id)
    {
        $invoices = InvoiceReceipt::where('student_id', $id)->get();
        $categories = ArticleCategory::with('article')->where('company_id', Auth::user()->company->id)->get();
        $filteredCategories = [];

        foreach ($categories as $category) {
            $paidCategories = [];
            $year = date('Y');

            foreach ($invoices as $invoice) {
                $filteredItems = $invoice->invoice_receipt_itens->filter(function ($item) use ($category) {
                    return $item->article->article_category_id == $category->id;
                });

                if ($filteredItems->isNotEmpty()) {
                    $paidCategories[] = $invoice->id;
                }
            }

            $filteredItems = InvoiceReceiptItens::whereIn('invoice_receipt_id', $paidCategories)
                ->where('article_id', $category->id)
                ->get();

            $paid = $filteredItems->sum('paid');
            $dueDate = $filteredItems->max('invoiceReceipt.due_date');

            if ($paid == 0 || ($paid > 0 && $dueDate < date('Y-m-d'))) {
                $filteredCategories[] = $category;
            }
        }

        return $filteredCategories;
    }

    // buscar dados de propina de estudante
    public function propinaEstudentId($studentId, $company = 1)
    {

        $categoryId = ArticleCategory::where('company_id', $company)->where('designation', 'Propinas')->first()->id;

        $invoices = InvoiceReceipt::where('student_id', $studentId)
            ->whereHas('invoice_receipt_itens', function ($query) use ($categoryId) {
                $query->whereHas('article', function ($query) use ($categoryId) {
                    $query->where('article_category_id', $categoryId);
                });
            })
            ->with(['invoice_receipt_itens' => function ($query) use ($categoryId) {
                $query->whereHas('article', function ($query) use ($categoryId) {
                    $query->where('article_category_id', $categoryId);
                });
            }])
            ->get();

        return $invoices;

    }

    public function Propinas(Request $request)
    {

        if ($request->document == 'debts') {
            return $this->PropinaDividas($request);
        }

        $company = Company::findOrFail($request->companyId);
        $categoryId = ArticleCategory::where('company_id', $request->companyId)
            ->where(function ($query) {
                $query->where('description', 'Propinas')
                    ->orWhere('description', 'Propina')
                    ->orWhere('description', 'like', '%Propina');
            })->first()->id;

        $invoices = InvoiceReceipt::with('student')->with('invoice_receipt_itens')
        // ->whereBetween('created_at', [$request->dateOf . ' 00:00:00', $request->dateTo . ' 23:59:59'])
            ->whereHas('invoice_receipt_itens', function ($query) use ($categoryId) {
                $query->whereHas('article', function ($query) use ($categoryId) {
                    $query->where('article_category_id', $categoryId);
                });
            })
            ->with(['invoice_receipt_itens' => function ($query) use ($categoryId) {
                $query->whereHas('article', function ($query) use ($categoryId) {
                    $query->where('article_category_id', $categoryId);
                });
            }])->get();

        $dados = array(
            "empresa" => $company,
            "dados" => $invoices,
            "request" => $request,
        );

        $pdf = PDF::loadView('documents.relatorio_propinas', compact('dados'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("Propinas " . $request->dateOf . " - " . $request->dateTo . ".pdf");
    }
    
    


    public function PropinaDividas(Request $request)
    {
        $company = Company::findOrFail($request->companyId);
        $categoryId = ArticleCategory::where('company_id', $request->companyId)
            ->where(function ($query) {
                $query->where('description', 'Propinas')
                    ->orWhere('description', 'Propina')
                    ->orWhere('description', 'like', '%Propina');
            })->first()->id;
        
        // Obter todos os meses da categoria Propinas
        $allMonths = Article::where('article_category_id', $categoryId)->get()->pluck('designation')->toArray();
        
        $invoices = [];
        $students = Enrollment::where('company_id', $request->companyId)
            ->where('status', '1')
            ->with('student')
            ->get();
        
        foreach ($students as $student) {
            // Obter os meses não pagos
            $paidMonths = Article::where('article_category_id', $categoryId)
                ->whereIn('id', function ($query) use ($student) {
                    $query->select('article_id')
                        ->from('invoice_receipt_itens')
                        ->join('invoice_receipts', 'invoice_receipts.id', '=', 'invoice_receipt_itens.invoice_receipt_id')
                        ->where('invoice_receipts.student_id', $student->student->id);
                })
                ->get()
                ->pluck('designation')
                ->toArray();
        
            // Identificar os meses não pagos
            $unpaidMonths = array_diff($allMonths, $paidMonths);
        
            if (!empty($unpaidMonths)) {
                $invoices[] = [
                    'student' => $student->student,
                    'details' => [
                        "course" => $student->course->designation,
                        "classe" => $student->classe->designation,
                        "period" => $student->period->designation,
                        "turma" => $student->turma->designation,
                    ],
                    'meses' => [
                        'unpaid' => $unpaidMonths,
                        'paid' => $paidMonths,
                    ],
                ];
            }
        }
        
        
        $dados = array(
            'document' => "Relatorio de Dividas",
            "empresa" => $company,
            "dados" => $invoices,
            "request" => $request,
        );
        $pdf = PDF::loadView('documents.relatorio_propina_current', compact('dados'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("RD " . time() . ".pdf");
    }


    public function show(string $id)
    {
        try {
            $invoice_receipt = InvoiceReceipt::findOrFail($id);
            return response(
                ['invoice_receipt' => InvoiceReceiptResource::make($invoice_receipt)], 200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            if ($th->getCode() == 0) {
                return response(['invoice_receipts' => $th->getMessage(),
                    "message" => "Verifica se o Estudante, Matricula ou a forma de pagamento existem!"], 400)->header('Content-type', 'application/json');
            }
            return response(['invoice_receipt' => $th->getMessage(), "message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."], 500)->header('Content-type', 'application/json');
        }
    }

    public function teste()
    {
        try {

            // $subselect = InvoiceReceiptItens::select('article_id as id','invoice_receipts.created_at')
            // ->join('invoice_receipts','invoice_receipts.id','=','invoice_receipt_itens.invoice_receipt_id')

            //     ->where('invoice_receipts.created_at','<>',date('Y'))
            //     ->get();
            //     $article_categories = ArticleCategory::
            //     get();
            //     for ($i=0; $i < count($article_categories) ; $i++) {
            //         $article_categories[$i]['article'] = Article::where('article_category_id', $article_categories[$i]['id'])
            //         ->whereNotIn('id', $subselect->pluck('id'))
            //         ->get();
            //     }
            // ,'article_categories.company_id'=>Auth::user()->company->id,'articles.company_id'=>Auth::user()->company->id

            $articles = Article::select('articles.id', 'articles.designation', 'article_categories.company_id')
                ->join('article_categories', 'article_categories.id', '=', 'articles.article_category_id')
                ->where(['article_categories.designation' => 'Confirmação'])
                ->get();
            return response(
                ['articles' => $articles], 200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            if ($th->getCode() == 0) {
                return response(['invoice_receipts' => $th->getMessage(),
                    "message" => "Verifica se o Estudante, Matricula ou a forma de pagamento existem!"], 400)->header('Content-type', 'application/json');
            }
            return response(['invoice_receipt' => $th->getMessage(), "message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."], 500)->header('Content-type', 'application/json');
        }
    }

    // list>studantes
    public function list_studant()
    {
        $student = Student::where('company_id', Auth::user()->company_id)->get();
        return response()->json(['studants' => $student]);
    }

    public function updateItems()
    {
        $itens = DB::connection('mysql')->table('invoice_receipt_itens')->get();
        foreach ($itens as $item) {
            // Obter o artigo correspondente
            $article = DB::connection('mysql')->table('articles')->find($item->article_id);

            // Se o artigo for encontrado, buscar a categoria
            if ($article) {
                $category = DB::connection('mysql')->table('article_categories')->find($article->article_category_id);

                // Atualizar os campos category_designation e article_designation
                DB::connection('mysql')->table('invoice_receipt_itens')
                    ->where('id', $item->id)
                    ->update([
                        'category_designation' => $category ? $category->designation : null,
                        'article_designation' => $article->designation,
                    ]);
            }
        }
    }
}
