<?php

use App\Http\Controllers\Config\Company\{
    CompanyBankController,
    CompanyController,
    DepartamentController,
};
use App\Http\Controllers\Config\ManegementUser\{
    PermissionController,
    RoleController,
    UserController,
 };
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\Finance\{
    ArticleCategoryController,
    ArticleController,
    ArticleTypeController,
    ExpenseController,
    FormOfPaymentController,
    RetentionController,
    TaxController,
    InvoiceReceiptController,
};
use App\Http\Controllers\Pdf\EnrollmentPdf;
use App\Http\Controllers\Pdf\InvoiceReceiptPdf;
use App\Http\Controllers\Pdf\MiniSchedulePdf;
use App\Http\Controllers\Pdf\ShedulePdf;
use App\Http\Controllers\Pedagogical\{
    ClasseController,
    ClassRoomController,
    CoursesController,
    DisciplineController,
    GradeScheduleController,
    MiniScheduleController,
    PeriodController,
    ScheduleController,
    StudentController,
    TrimestreController,
    TurmaController,
    ReportCardController,
    CertificationController
};
use App\Http\Controllers\secretary\{
    EnrollmentController,
    EnrollmentRegistationController,
    SchoolYearController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Config\Reports\ReportController;
use App\Http\Controllers\Config\Reports\RelatorioController;

use App\Http\Controllers\Config\ConfigController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/carregar/{id}',[CompanyController::class,'initSE']);

/** AUTH **/ 
Route::group(['prefix'=> '/auth'], function () {
    Route::get('/system',[AuthController::class,'update_system']);
    Route::post('/login',[AuthController::class,'login'])->name('auth.login');
    Route::post('company/create', [CompanyController::class,'create'])->name('company.create');
    Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout'])->name('auth.logout');
});


/* EXPORTACAO DE PDF */
Route::group(['middleware' => 'cors','TimeoutMiddleware'], function () {
    Route::group(['prefix'=> '/exportacao'], function () {
        Route::get('enrollment/{id}', [RelatorioController::class, 'matricula']);
        Route::get('schedule/{id}', [RelatorioController::class, 'pautageral']);
        Route::get('minischeduleFile/{file}', [RelatorioController::class, 'minipautaFile']);
        Route::get('minischedule/{id}', [RelatorioController::class, 'minipauta']);
        Route::get('invoice_receipts/{id}', [RelatorioController::class, 'fatura_recibo']);
    });
});

/* RELATORIO GERAL */
Route::get('settings/reports/{type}/{id}/{of?}/{due?}', [ReportController::class, 'report']);

Route::middleware('auth:sanctum')->post("auth/init", function (Request $request) {
    return response()->json(['valid' => true]);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix'=> '/dashboard'], function (){
        Route::get('/', [DashBoardController::class, 'index'])->name('dashboard.index');
    });
    
    /* SETTINGS */
    Route::group(['prefix'=> '/settings'], function () {
        Route::group(['prefix' => '/company'],function () {
            Route::get('/', [CompanyController::class, 'index'])->name('company.index');

            Route::get('/show/{id}', [CompanyController::class,'show'])->name('company.show');
            Route::put('/update/{id}', [CompanyController::class, 'update'])->name('company.update');
            Route::post('logo/{id}', [CompanyController::class, 'updateLogo'])->name('company.updateLogo');
            Route::delete('/destroy/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
            Route::post('users_pass/{id}', [UserController::class, 'password']);
            Route::get('users_pass_default/{id}', [UserController::class, 'password_default']);

            Route::group(['prefix' => '/bank'],function () {
                Route::get('/', [CompanyBankController::class, 'index'])->name('company.bank.index');
                Route::post('/store', [CompanyBankController::class,'store'])->name('company.bank.store');
                Route::get('/show/{id}', [CompanyBankController::class,'show'])->name('company.bank.show');
                Route::put('/update/{id}', [CompanyBankController::class, 'update'])->name('company.bank.update');
                Route::delete('/destroy/{id}', [CompanyBankController::class, 'destroy'])->name('company.bank.destroy');
            });
            Route::group(['prefix' => '/departament'],function () {
                Route::get('/', [DepartamentController::class, 'index'])->name('departament.index');
                Route::post('/store', [DepartamentController::class,'store'])->name('departament.store');
                Route::get('/show/{id}', [DepartamentController::class,'show'])->name('departament.show');
                Route::put('/update/{id}', [DepartamentController::class, 'update'])->name('departament.update');
                Route::delete('/destroy/{id}', [DepartamentController::class, 'destroy'])->name('departament.destroy');
            });
        });

        Route::group(['prefix'=> 'manegment'],function () {
            Route::group(['prefix' => '/role'],function () {
                Route::get('/', [RoleController::class, 'index'])->name('role.index');
                Route::post('/store', [RoleController::class,'store'])->name('role.store');
                Route::get('/show/{id}', [RoleController::class,'show'])->name('role.show');
                Route::put('/update/{id}', [RoleController::class, 'update'])->name('role.update');
                Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

            });
            Route::group(['prefix' => '/user'], function() {
                Route::get('/', [UserController::class, 'index'])->name('manegment.user.index');
                Route::get('/professor', [UserController::class, 'professor'])->name('manegment.user.professor');
                Route::post('/store', [UserController::class,'store'])->name('manegment.user.store');
                Route::get('/show/{id}', [UserController::class,'show'])->name('manegment.user.show');
                Route::put('/update/{id}', [UserController::class, 'update'])->name('manegment.user.update');
                Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('manegment.user.destroy');
            });
            Route::group(['prefix' => '/permission'],function () {
                Route::get('/', [PermissionController::class, 'index'])->name('permission.index');
                Route::post('/store', [PermissionController::class,'store'])->name('permission.store');
                Route::get('/show/{id}', [PermissionController::class,'show'])->name('permission.show');
                Route::put('/update/{id}', [PermissionController::class, 'update'])->name('permission.update');
                Route::delete('/destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
            });
        });
    });


    /* AREA PEGADOGICA */
    Route::group(['prefix' => '/pedagogical'],function () {
        
        Route::group(['prefix' => '/certification'], function () {
            Route::get('/', [CertificationController::class, 'index'])->name('certification.index');
            Route::post('/uplode/{id}', [CertificationController::class, 'uplode'])->name('certification.uplode');
        });
                 

        Route::group(['prefix'=> '/period'],function () {
            Route::get('/', [PeriodController::class, 'index'])->name('period.index');
            Route::post('/store', [PeriodController::class,'store'])->name('period.store');
            Route::get('/show/{id}', [PeriodController::class,'show'])->name('period.show');
            Route::put('/update/{id}', [PeriodController::class, 'update'])->name('period.update');
            Route::delete('/destroy/{id}', [PeriodController::class, 'destroy'])->name('period.destroy');
        });
        
        Route::group(['prefix'=> '/class'],function () {
            Route::get('/', [ClasseController::class, 'index'])->name('class.index');
            Route::post('/store', [ClasseController::class,'store'])->name('class.store');
            Route::get('/show/{id}', [ClasseController::class,'show'])->name('class.show');
            Route::put('/update/{id}', [ClasseController::class, 'update'])->name('class.update');
            Route::delete('/destroy/{id}', [ClasseController::class, 'destroy'])->name('class.destroy');
            Route::put('/changeStatus/{id}', [ClasseController::class, 'changeStatus'])->name('class.changeStatus');
        });
        Route::group(['prefix'=> '/discipline'],function () {
            Route::get('/', [DisciplineController::class, 'index'])->name('user.index');
            Route::post('/store', [DisciplineController::class,'store'])->name('user.store');
            Route::get('/show/{id}', [DisciplineController::class,'show'])->name('user.show');
            Route::put('/update/{id}', [DisciplineController::class, 'update'])->name('user.update');
            Route::delete('/destroy/{id}', [DisciplineController::class, 'destroy'])->name('user.destroy');
            Route::put('/changeStatus/{id}', [DisciplineController::class, 'changeStatus'])->name('user.changeStatus');
        });
        Route::group(['prefix'=> '/course'],function () {
            Route::get('/', [CoursesController::class, 'index'])->name('course.index');
            Route::post('/store', [CoursesController::class,'store'])->name('course.store');
            Route::get('/show/{id}', [CoursesController::class,'show'])->name('course.show');
            Route::put('/update/{id}', [CoursesController::class, 'update'])->name('course.update');
            Route::delete('/destroy/{id}', [CoursesController::class, 'destroy'])->name('course.destroy');
            Route::put('/changeStatus/{id}', [CoursesController::class, 'changeStatus'])->name('course.changeStatus');
        });
        Route::group(['prefix'=> '/classroom'],function () {
            Route::get('/', [ClassRoomController::class, 'index'])->name('classroom.index');
            Route::post('/store', [ClassRoomController::class,'store'])->name('classroom.store');
            Route::get('/show/{id}', [ClassRoomController::class,'show'])->name('classroom.show');
            Route::put('/update/{id}', [ClassRoomController::class, 'update'])->name('classroom.update');
            Route::delete('/destroy/{id}', [ClassRoomController::class, 'destroy'])->name('classroom.destroy');
            Route::put('/changeStatus/{id}', [ClassRoomController::class, 'changeStatus'])->name('classroom.changeStatus');
        });
        Route::group(['prefix'=> '/trimestre'],function () {
            Route::get('/', [TrimestreController::class, 'index'])->name('pedagogical.trimestre.index');
            Route::post('/store', [TrimestreController::class,'store'])->name('pedagogical.trimestre.store');
            Route::get('/show/{id}', [TrimestreController::class,'show'])->name('pedagogical.trimestre.show');
            Route::put('/update/{id}', [TrimestreController::class, 'update'])->name('pedagogical.trimestre.update');
            Route::delete('/destroy/{id}', [TrimestreController::class, 'destroy'])->name('pedagogical.trimestre.destroy');
            Route::put('/changeStatus/{id}', [TrimestreController::class, 'changeStatus'])->name('pedagogical.trimestre.changeStatus');
        });
        Route::group(['prefix'=> '/turma'],function () {
            Route::get('/', [TurmaController::class, 'index'])->name('turma.index');
            Route::post('/store', [TurmaController::class,'store'])->name('turma.store');
            Route::get('/show/{id}', [TurmaController::class,'show'])->name('turma.show');
            Route::put('/update/{id}', [TurmaController::class, 'update'])->name('turma.update');
            Route::delete('/destroy/{id}', [TurmaController::class, 'destroy'])->name('turma.destroy');
            Route::put('/changeStatus/{id}', [TurmaController::class, 'changeStatus'])->name('turma.changeStatus');
            Route::get('view', [TurmaController::class, 'view'])->name('turma.view');
    
            /*Route::withoutMiddleware(['auth:sanctum'])->group(function () {
                Route::get('relatorio', [TurmaController::class, 'relatorio'])->name('turma.relatorio');
            });*/
        });
        
    
        
        
        
        /* DOCUMENTOS */
        
        /* PAUTA*/
        Route::group(['prefix'=> '/schedule'],function () {
            Route::get('/', [ScheduleController::class, 'index'])->name('pedagogical.schedule.index');
            Route::get('/view', [ScheduleController::class, 'view'])->name('pedagogical.schedule.view');
            Route::post('/store', [ScheduleController::class,'store'])->name('pedagogical.schedule.store');
            Route::get('/show/{id}', [ScheduleController::class,'show'])->name('pedagogical.schedule.show');
            Route::put('/update/{id}', [ScheduleController::class, 'update'])->name('pedagogical.schedule.update');
            Route::delete('/destroy/{id}', [ScheduleController::class, 'destroy'])->name('pedagogical.schedule.destroy');
            Route::post('/uplode/{id}', [ScheduleController::class, 'updateFile'])->name('pedagogical.schedule.updateFile');
            Route::put('/changeStatus/{id}', [ScheduleController::class, 'changeStatus'])->name('pedagogical.schedule.changeStatus');
            
            /* MINI PAUTA*/
            Route::group(['prefix'=> '/mini'],function () {
                Route::get('/', [MiniScheduleController::class, 'index'])->name('pedagogical.schedule.mini.index');
                Route::get('/view', [MiniScheduleController::class, 'view'])->name('pedagogical.schedule.mini.view');
                Route::post('/store', [MiniScheduleController::class,'store'])->name('pedagogical.schedule.mini.store');
                Route::get('/show/{id}', [MiniScheduleController::class,'show'])->name('pedagogical.schedule.mini.show');
                Route::get('/schedule/{id}', [MiniScheduleController::class,'all_mini_schedule_of_schedule'])->name('pedagogical.schedule.mini.all_mini_schedule_of_schedule');
                Route::put('/update/{id}', [MiniScheduleController::class, 'update'])->name('pedagogical.schedule.mini.update');
                Route::post('/uplode/{id}', [MiniScheduleController::class, 'updateFile'])->name('pedagogical.schedule.mini.updateFile');
                Route::delete('/destroy/{id}', [MiniScheduleController::class, 'destroy'])->name('pedagogical.schedule.mini.destroy');
                Route::put('/changeStatus/{id}', [MiniScheduleController::class, 'changeStatus'])->name('pedagogical.schedule.mini.changeStatus');
                
                  /* NOTAS */
                Route::group(['prefix'=> '/grade'],function () {
                    Route::get('/', [GradeScheduleController::class, 'index'])->name('pedagogical.schedule.mini.grade.index');
                    Route::get('/teste', [GradeScheduleController::class, 'teste'])->name('pedagogical.schedule.mini.grade.teste');
                    Route::post('/store', [GradeScheduleController::class,'store'])->name('pedagogical.schedule.mini.grade.store');
                    Route::get('/show/{id}', [GradeScheduleController::class,'show'])->name('pedagogical.schedule.mini.grade.show');
                    Route::get('/mini_schedule/{id}', [GradeScheduleController::class,'showAll'])->name('pedagogical.schedule.mini.grade.showAll');
                    Route::put('/update/{id}', [GradeScheduleController::class, 'update'])->name('pedagogical.schedule.mini.grade.update');
                    Route::delete('/destroy/{id}', [GradeScheduleController::class, 'destroy'])->name('pedagogical.schedule.mini.grade.destroy');
                    Route::put('/changeStatus/{id}', [GradeScheduleController::class, 'changeStatus'])->name('pedagogical.schedule.mini.grade.changeStatus');
                });
            });
        });        
        
        
         // todos estudantes com mesma, classe, turma, ....
        Route::get('student/schedule/{id}', [StudentController::class, 'student_in_turma']);
        Route::withoutMiddleware(['auth:sanctum'])->group(function () {
            Route::get('students/relatorio', [ConfigController::class, 'relatorio'])->name('turma.relatorio');
        });
            
            
        Route::group(['prefix'=> '/documents'],function () {
            
            /*BOLETIM */
              Route::withoutMiddleware(['auth:sanctum'])->group(function () { 
                      Route::group(['prefix'=> '/report-card'],function () {
                      Route::get('/student/{id}', [ReportCardController::class,'studentId']);
                    });
                });
            });
        

    });
    

    /*SECRETARIA */
    Route::group(['prefix'=> 'secretary'],function () {
        Route::group(['prefix'=> 'schoolyear'],function () {
            Route::get('/', [SchoolYearController::class, 'index'])->name('schoolyear.index');
            Route::post('/store', [SchoolYearController::class,'store'])->name('schoolyear.store');
            Route::get('/show/{id}', [SchoolYearController::class,'show'])->name('schoolyear.show');
            Route::put('/update/{id}', [SchoolYearController::class, 'update'])->name('schoolyear.update');
            Route::delete('/destroy/{id}', [SchoolYearController::class, 'destroy'])->name('schoolyear.destroy');
            Route::put('/changeStatus/{id}', [SchoolYearController::class, 'changeStatus'])->name('schoolyear.changeStatus');
        });
        
          /* MATRICULA */
        Route::group(['prefix'=> 'enrollment'],function () {
            Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment.index');
            Route::get('/view', [EnrollmentController::class, 'view'])->name('enrollment.view');
            Route::get('/dropout', [EnrollmentController::class, 'dropout'])->name('enrollment.dropout');
            Route::post('/confirmation', [EnrollmentController::class, 'confirmation']); // FUNCAO DE CONFIRMACAO
            Route::post('/store', [EnrollmentController::class,'store'])->name('enrollment.store');
            Route::get('/show/{id?}', [EnrollmentController::class,'show'])->name('enrollment.show');
            Route::put('/update/{id}', [EnrollmentController::class, 'update'])->name('enrollment.update');
            Route::delete('/destroy/{id}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');
            Route::put('/changeStatus/{id}', [EnrollmentController::class, 'changeStatus'])->name('enrollment.changeStatus');


              /* CONFIRMACAO OLD*/
            Route::group(['prefix'=> 'registation'],function () {
                Route::get('/', [EnrollmentRegistationController::class, 'index'])->name('enrollment.registation.index');
                Route::post('/store', [EnrollmentRegistationController::class,'store'])->name('enrollment.registation.store');
                Route::get('/show/{id}', [EnrollmentRegistationController::class,'show'])->name('enrollment.registation.show');
                Route::put('/update/{id}', [EnrollmentRegistationController::class, 'update'])->name('enrollment.registation.update');
                Route::delete('/destroy/{id}', [EnrollmentRegistationController::class, 'destroy'])->name('enrollment.registation.destroy');
                Route::put('/changeStatus/{id}', [EnrollmentRegistationController::class, 'changeStatus'])->name('enrollment.registation.changeStatus');
            });
        });
        
        /* ESTUDANTES */
        Route::group(['prefix'=> 'students'],function () {
            Route::get('/', [StudentController::class, 'index'])->name('students.index');
            Route::get('view', [StudentController::class,'view'])->name('students.view');
            // Route::get('search', [SchoolYearController::class,'search'])->name('students.search');
        });
  
        
        
        
        
        /* RELATORIO DE  MATRICULA/CONFIRMACAO */
        Route::withoutMiddleware(['auth:sanctum'])->group(function () { 
            Route::group(['prefix'=> 'documents'],function () {
                Route::get('type', [RelatorioController::class,'documentsSecretary']);
            });
        });

        // Route::group(['prefix'=> 'transport'],function () {
        //     Route::get('/', [TransportController::class, 'index'])->name('transport.index');
        //     Route::post('/store', [TransportController::class,'store'])->name('transport.store');
        //     Route::get('/show/{id}', [TransportController::class,'show'])->name('transport.show');
        //     Route::put('/update/{id}', [TransportController::class, 'update'])->name('transport.update');
        //     Route::delete('/destroy/{id}', [TransportController::class, 'destroy'])->name('transport.destroy');
        //     Route::put('/changeStatus/{id}', [TransportController::class, 'changeStatus'])->name('transport.changeStatus');
        // });
    });
    
    
    /* AREA FINANCEIRA */
    Route::group(['prefix'=> '/finance'],function () {
    
        /* ARTIGOS */
        Route::group(['prefix'=>'/article'], function(){
            Route::get('/', [ArticleController::class, 'index'])->name('finance.article.index');
            Route::post('/store', [ArticleController::class,'store'])->name('finance.article.store');
            Route::get('/show/{id}', [ArticleController::class,'show'])->name('finance.article.show');
            Route::put('/update/{id}', [ArticleController::class, 'update'])->name('finance.article.update');
            Route::delete('/destroy/{id}', [ArticleController::class, 'destroy'])->name('finance.article.destroy');
            
            /* TAXA */
            Route::group(['prefix'=>'/tax'],function () {
                Route::get('/', [TaxController::class, 'index'])->name('finance.article.tax.index');
                Route::post('/store', [TaxController::class,'store'])->name('finance.article.tax.store');
                Route::get('/show/{id}', [TaxController::class,'show'])->name('finance.article.tax.show');
                Route::put('/update/{id}', [TaxController::class, 'update'])->name('finance.article.tax.update');
                Route::delete('/destroy/{id}', [TaxController::class, 'destroy'])->name('finance.article.tax.destroy');
            });
            
            /* TIPOS DE ARTIGO */
            Route::group(['prefix'=>'/type'],function () {
                Route::get('/', [ArticleTypeController::class, 'index'])->name('finance.article.type.index');
                Route::post('/store', [ArticleTypeController::class,'store'])->name('finance.article.type.store');
                Route::get('/show/{id}', [ArticleTypeController::class,'show'])->name('finance.article.type.show');
                Route::put('/update/{id}', [ArticleTypeController::class, 'update'])->name('finance.article.type.update');
                Route::delete('/destroy/{id}', [ArticleTypeController::class, 'destroy'])->name('finance.article.type.destroy');
            });
            
            /* CATEGORIA DE ARTIGO */
            Route::group(['prefix'=>'/category'],function () {
                Route::get('/', [ArticleCategoryController::class, 'index'])->name('finance.article.category.index');
                Route::get('/get_category_in_invoice_receipts', [ArticleCategoryController::class, 'get_category_in_invoice_receipts'])->name('finance.article.category.get_category_in_invoice_receipts');
                Route::post('/store', [ArticleCategoryController::class,'store'])->name('finance.article.category.store');
                Route::get('/show/{id}', [ArticleCategoryController::class,'show'])->name('finance.article.category.show');
                Route::put('/update/{id}', [ArticleCategoryController::class, 'update'])->name('finance.article.category.update');
                Route::delete('/destroy/{id}', [ArticleCategoryController::class, 'destroy'])->name('finance.article.category.destroy');
            });
            
            /* RETENCAO */
            Route::group(['prefix'=>'/retention'],function () {
                Route::get('/', [RetentionController::class, 'index'])->name('finance.article.retention.index');
                Route::post('/store', [RetentionController::class,'store'])->name('finance.article.retention.store');
                Route::get('/show/{id}', [RetentionController::class,'show'])->name('finance.article.retention.show');
                Route::put('/update/{id}', [RetentionController::class, 'update'])->name('finance.article.retention.update');
                Route::delete('/destroy/{id}', [RetentionController::class, 'destroy'])->name('finance.article.retention.destroy');
            });
        });
    
        /* FACTURA RECIBO */
        Route::group(['prefix'=>'/invoice_receipt'], function(){
            Route::get('/', [InvoiceReceiptController::class, 'index'])->name('invoice.receipt.index');
            Route::get('/view', [InvoiceReceiptController::class, 'view'])->name('invoice.receipt.view');
            Route::get('/invoice_number', [InvoiceReceiptController::class, 'last_invoice'])->name('invoice.receipt.last_invoice');
            Route::post('/store', [InvoiceReceiptController::class,'store'])->name('invoice.receipt.store');
            Route::get('/show/{id}', [InvoiceReceiptController::class,'show'])->name('invoice.receipt.show');
            Route::put('/update/{id}', [InvoiceReceiptController::class, 'update'])->name('invoice.receipt.update');
            Route::delete('/destroy/{id}', [InvoiceReceiptController::class, 'destroy'])->name('invoice.receipt.destroy');
            Route::get('/student/{id}', [InvoiceReceiptController::class, 'students']);
        });
        
        /* RELATORIO DE PROPINAS / POR AJUSTAR */
        Route::withoutMiddleware(['auth:sanctum'])->group(function () { 
            Route::group(['prefix'=>'/bribes'], function () { 
                Route::get('students_company', [InvoiceReceiptController::class, 'Propinas']);
                Route::get('students/{id}', [InvoiceReceiptController::class, 'propinaEstudentId']);
                Route::get('debtors/{id}', [InvoiceReceiptController::class, 'PropinaDividas']);
            });
        });
        
        
        
        /* DESPESAS */
        Route::group(['prefix'=>'/expenses'], function(){
            Route::get('/', [ExpenseController::class, 'index'])->name('expense.index');
            Route::post('/store', [ExpenseController::class,'store'])->name('expense.store');
            Route::get('/show/{id}', [ExpenseController::class,'show'])->name('expense.show');
            Route::put('/update/{id}', [ExpenseController::class, 'update'])->name('expense.update');
            Route::delete('/destroy/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
        });
        Route::group(['prefix'=>'/form_of_payment'], function(){
            Route::get('/', [FormOfPaymentController::class, 'index'])->name('invoice.receipt.index');
        });
    });
});

/* PAUTA
Route::group(['prefix'=> '/schedule'],function () {
    Route::get('/', [ScheduleController::class,'index'])->name('schedule.index');
    Route::get('/show', [ScheduleController::class,'show'])->name('schedule.show');
    Route::post('/store',[ScheduleController::class, 'store'])->name('schedule.store');
}); */

/* DEFINICOES 
Route::group(['prefix'=> '/settings'],function () {
    Route::get('company_groups', [CompanyController::class, 'company_groups']);
    Route::post('company_groups_change', [CompanyController::class, 'company_groups_change'])->name('company.company_groups_change');
    Route::get('company_groups_delete', [CompanyController::class, 'company_groups_delete']);
});*/




Route::get('/', function(){
    return redirect("http://escolar.atomo.ao");
});
