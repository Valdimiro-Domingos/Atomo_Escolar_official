import { Component, OnInit, ViewChild, inject } from '@angular/core';
import { SecretaryService } from '../../services/secretary.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { Subscription } from 'rxjs';





import {
  FormGroup,
  FormBuilder,
  Validators,
  FormArray,
  AbstractControl,
} from '@angular/forms';
import * as feather from 'feather-icons';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { AlertService } from 'src/app/services/alert/alert.service';
import { IClass } from 'src/app/shared/models/class';
import { IClassRoom } from 'src/app/shared/models/classRoom';
import { ICourse } from 'src/app/shared/models/course';
import { IRegistration } from 'src/app/shared/models/registration';
import { ISchoolYear } from 'src/app/shared/models/schoolYear';
import { ITurma } from 'src/app/shared/models/turma';
import { FinanceService } from 'src/app/modules/finance/services/finance.service';
import { IPaymentForm } from 'src/app/shared/models/paymentForms';
import { UserItem } from 'src/app/shared/models/users';
import { IInvoiceReceipt } from 'src/app/shared/models/invoice-receipt';
import { ArticleItem, IArticle } from 'src/app/shared/models/article';
import { environmentSettings } from 'src/environments/environment.dev';
import { CategoryItem, ICategory } from 'src/app/shared/models/category';
import { FormFullComponent } from 'src/app/modules/admin/components/form-full/form-full.component';











@Component({
  selector: 'app-students',
  templateUrl: './students.component.html',
  styleUrls: ['./students.component.css']
})
export class StudentsComponent implements OnInit {

  
  
  
  
  url = environmentSettings.endpoint.url;
  base = [{ name: 'Secretaria',  url: '/dash/secretary' }, { name: 'Estudantes',  url: '/dash/secretary/studants' }]
  
  isLoading: boolean = false;
  filter = [
    {
      label: 'Nome',
      select: ['pedro', 'lucas', 'fabio'],
    },
  ];

  @ViewChild(FormFullComponent)
  private formModalComponent!: FormFullComponent;


  @ViewChild(HTMLAnchorElement) link!: HTMLAnchorElement;

  formulario!: FormGroup;
  formularioReconfirm!: FormGroup;
  formularioFormReconfirm!: FormGroup;



  items: any[] = [];
  studants: any = [];
  baseForm! : FormGroup;
  dataLocal: string = new Date().toLocaleDateString();
  
  constructor(
    private clientApi: PedagogicalService,
    private alertService: AlertService,
    private authService: AuthService,
    private build: FormBuilder
  ) {  
  
    
    this.baseForm = build.group({
      documento: ['estudantes'],
      turma: ['', Validators.required],
      curso: ['', Validators.required],
      sala: ['', Validators.required],
      classe: ['', Validators.required],
      lectivo: ['', Validators.required],
      periodo : ['', Validators.required],
      mes : [''],
      company_id : [''],
    });
  }

  ngOnInit(): void {
    feather.replace();
    this.isLoading = true;
    this.initStudent()
  }


  meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    
  private studentService = inject(SecretaryService)
  // private authService = inject(AuthService)
  
  

  baseResponse : {
    students : [],
    items : any;
    periodos : any;
   [key: string]: any;
  } = {
    students : [],
    items : {},
    periodos: []
  }
  
  
  initStudent() : void{
    const token = this.authService.getUser().token
    this.studentService.getAllStudents(token).subscribe(data => {
    this.isLoading = false;
    // console.log(data);
    this.baseResponse = data as any
    })
  }
 
  printPDF(item: any) {
    return `${this.url}/pdf/invoice_receipts_pdf/${item.id}`;
  }
  
  view() : any {
    const token = this.authService.getUser().token;
      if (this.baseForm.valid) {
      this.clientApi.RelatorioTurma({ ...this.baseForm.value, token : token});
    } else {
      this.alertService.AlertInfo('Por favor, preenche os campos');
    }
  }
 
  handleRemove(id: number) {}

  patchValues(item: any) {
    this.formulario.patchValue({
      ...item,
    });
  }

  clear() {}

  patchValuesReconfirm() {
   
  }

  onSave() { }

  onReconfirm() { }

  artigo: any = {};
  pricevalue: any;
  unitprice: any;
  articles: ArticleItem[] = [];

  isItem: boolean = false;

  toogleContainer() {
  
  }

  onBuscar() {
      const token = this.authService.getUser().token;
      if (this.baseForm.valid) {
      // this.baseForm.value.documento == 'propinas' || 
        if((this.baseForm.value.documento == 'devedores') && !this.baseForm.value.mes){
          this.alertService.AlertInfo('Por favor, mensalidade não foi definida');
        }else{
          this.baseForm.controls['company_id'].setValue(this.authService.getUser().user.company_id)
          this.clientApi.Relatorio({ ...this.baseForm.value, token : token});
        }
    } else {
      this.alertService.AlertInfo('Por favor, preenche os campos');
    }
  }

  onClose() {
    this.formModalComponent.handleClose();
  }

}

interface IStudent{
    id: number,
    name: string,
    identity: string,
    gender: string
    photo: string
}