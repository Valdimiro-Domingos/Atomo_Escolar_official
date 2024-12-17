<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ClassRoomResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\DisciplineResource;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\InvoiceReceiptResource;
use App\Http\Resources\PeriodResource;
use App\Http\Resources\SchoolYearResource;
use App\Http\Resources\TrimestreResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TurmaResource;
use App\Http\Resources\FormOfPaymentResource;
use App\Http\Resources\ArticleCategoryResource;

// gerador de relatorios por turma
use App\Models\Article;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\Enrollment;
use App\Models\InvoiceReceipt;
use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Trimestre;
use App\Models\Turma;
use App\Models\FormOfPayment;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    public function includePath($model, $query = null)
    {
        $sql = null;
        $queryBuilder = null;
        $companyId = Auth::user()->company_id;

        switch ($model) {
            case 'turma':
                $queryBuilder = Turma::where("company_id", $companyId);
                break;
            case 'article':
                $queryBuilder = Article::where("company_id", $companyId);
                break;
            case 'class':
                $queryBuilder = ClassRoom::where("company_id", $companyId);
                break;
            case 'course':
                $queryBuilder = Course::where("company_id", $companyId);
                break;
            case 'payment':
                $queryBuilder = FormOfPayment::where("company_id", $companyId);
                break;
            case 'shool':
                $queryBuilder = SchoolYear::where("company_id", $companyId);
                break;
            case 'category':
                $queryBuilder = ArticleCategory::where("company_id", $companyId);
                break;
            case 'discipline':
                $queryBuilder = Discipline::where("company_id", $companyId);
                break;
            case 'enrollement':
                $queryBuilder = Enrollment::where("company_id", $companyId);
                break;
            case 'trimestre':
                $queryBuilder = Trimestre::where("company_id", $companyId);
                break;
            case 'period':
                $queryBuilder = Period::where("company_id", $companyId);
                break;
            case 'student':
                $queryBuilder = Student::where("company_id", $companyId);
                break;
            case 'invoice':
                $queryBuilder = InvoiceReceipt::where("company_id", $companyId);
                break;
        }

        if ($query) {
            $query($queryBuilder); 
        }

        $results = $queryBuilder->get();

        switch ($model) {
            case 'turma':
                return TurmaResource::collection($results);
            case 'article':
                return ArticleResource::collection($results);
            case 'class':
                return ClassRoomResource::collection($results);
            case 'course':
                return CourseResource::collection($results);
            case 'payment':
                return FormOfPaymentResource::collection($results);
            case 'shool':
                return SchoolYearResource::collection($results);
            case 'category':
                return ArticleCategoryResource::collection($results);
            case 'discipline':
                return DisciplineResource::collection($results);
            case 'enrollement':
                return EnrollmentResource::collection($results);
            case 'trimestre':
                return TrimestreResource::collection($results);
            case 'period':
                return PeriodResource::collection($results);
            case 'student':
                return StudentResource::collection($results);
            case 'invoice':
                return InvoiceReceiptResource::collection($results);
        }

        return [];
    }
    
    public function include($model, $resource =true, $companyId = null, $sql = null)
    {   
        
        $companyId = $companyId ?? Auth::user()->company_id;

        if($resource){
            switch ($model) {
                case 'turma':
                    $sql = TurmaResource::collection(Turma::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'article':
                    $sql = ArticleResource::collection(Article::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'class':
                    $sql = ClassRoomResource::collection(ClassRoom::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'course':
                    $sql = CourseResource::collection(Course::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'payment':
                    $sql = FormOfPaymentResource::collection(FormOfPayment::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'shool':
                    $sql = SchoolYearResource::collection(SchoolYear::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'category':
                    $sql = ArticleCategoryResource::collection(ArticleCategory::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'discipline':
                    $sql = DisciplineResource::collection(Discipline::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'enrollement':
                    $sql = EnrollmentResource::collection(Enrollment::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'trimestre':
                    $sql = TrimestreResource::collection(Trimestre::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'period':
                    $sql = PeriodResource::collection(Period::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'student':
                    $sql = StudentResource::collection(Student::where("company_id", Auth::user()->company_id)->get());
                    break;
                case 'invoice':
                    $sql = InvoiceReceiptResource::collection(InvoiceReceipt::where("company_id", Auth::user()->company_id)->get());
                    break;
            }
        }else{
            switch ($model) {
                case 'turma':
                    $sql = (Turma::where("company_id", $companyId));
                    break;
                case 'article':
                    $sql = (Article::where("company_id",$companyId));
                    break;
                case 'class':
                   return ClassRoom::where("company_id", $companyId);
                    break;
                case 'course':
                    $sql = Course::where("company_id", $companyId);
                    break;
                case 'payment':
                    $sql = FormOfPayment::where("company_id", $companyId);
                    break;
                case 'shool':
                    $sql = SchoolYear::where("company_id", $companyId);
                    break;
                case 'category':
                    $sql = (ArticleCategory::where("company_id", $companyId));
                    break;
                case 'discipline':
                    $sql = (Discipline::where("company_id", $companyId));
                    break;
                case 'enrollement':
                    $sql = (Enrollment::where("company_id", $companyId));
                    break;
                case 'trimestre':
                    $sql = (Trimestre::where("company_id", $companyId));
                    break;
                case 'period':
                    $sql = (Period::where("company_id", $companyId));
                    break;
                case 'student':
                    $sql = (Student::where("company_id", $companyId));
                    break;
                case 'invoice':
                    $sql = (InvoiceReceipt::where("company_id", $companyId));
                    break;
            }
        }

        return $sql;
    }
}
