import { Component, OnInit, ViewChild } from '@angular/core';
import * as feather from 'feather-icons'
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { SecretaryService } from '../../services/secretary.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IRegistration, RegistrationItem } from 'src/app/shared/models/registration';
import { ICourse } from 'src/app/shared/models/course';
import { IClass } from 'src/app/shared/models/class';
import { ITurma } from 'src/app/shared/models/turma';
import { IClassRoom } from 'src/app/shared/models/classRoom';
import { ISchoolYear } from 'src/app/shared/models/schoolYear';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { AlertService } from 'src/app/services/alert/alert.service';
import { environmentSettings } from 'src/environments/environment.dev';
import { FinanceService } from 'src/app/modules/finance/services/finance.service';
import { IPaymentForm } from 'src/app/shared/models/paymentForms';
import { Router } from '@angular/router';




@Component({
  selector: 'app-dropout',
  templateUrl: './dropout.component.html',
  styleUrls: ['./dropout.component.css']
})
export class DropoutComponent implements OnInit {


  [x: string]: any;
  url: string = environmentSettings.endpoint.url
  base = [{ name: 'Secretaria', url: '/dash/secretary' }, { name: 'Desistentes', url: '/dash/secretary/dropout' }]
  isLoading: boolean = false

  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;

  identity: string = ""
  enrollment_number: string = ""

  datas: IRegistration = {
    enrollment: []
  }

  baseResponse: {
    enrollment: IRegistration | any,
    enrollment_number: string,
    items: {
      courses?: ICourse | any,
      periods?: any;
      classes?: IClass | any,
      turmas?: ITurma | any,
      company_id?: number
      classrooms?: IClassRoom | any,
      school_year?: ISchoolYear | any,
    }
  } = {
      enrollment: null,
      enrollment_number: '',
      items: {
        courses: null,
        classes: null,
        classrooms: null,
        company_id: 1,
        periods: null,
        school_year: null
      }
    }


  observationState: any;
  formulario!: FormGroup
  documentalType!: FormGroup
  formularioReconfirm!: FormGroup

  constructor(private clientApi: SecretaryService, private router: Router, private financeService: FinanceService,
    private alertService: AlertService, private pedagogicalService: PedagogicalService,
    private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    feather.replace()

    this.isLoading = true
    this.formulario = this.formBuilder.group({
      name: ['', [Validators.required]],
      gender: ['M'],
      identity: ['', [Validators.required]],
      course_id: ['1', [Validators.required]],
      period_id: ['', [Validators.required]],
      school_year_id: ['1', [Validators.required]],
      classe_id: ['', [Validators.required]],
      turma_id: ['', [Validators.required]],
      class_room_id: ['', [Validators.required]],
      enrollment_number: [''],
      address: ['', [Validators.required]],
      photo: [null],
      father_name: ['', [Validators.required]],
      mother_name: ['', [Validators.required]],
    });

    this.formularioReconfirm = this.formBuilder.group({
      name: [{ value: '', disabled: true }],
      gender: [{ value: '', disabled: true }],
      identity: [{ value: '', disabled: true }],
      photo: [null],
      course_id: ['', [Validators.required]],
      period_id: ['', [Validators.required]],
      school_year_id: ['', [Validators.required]],
      classe_id: ['', [Validators.required]],
      turma_id: ['', [Validators.required]],
      class_room_id: ['', [Validators.required]],
      enrollment_id: ['', [Validators.required]],
      student_id: ['', [Validators.required]],
      enrollment_number: ['', [Validators.required]],
      address: [{ value: '', disabled: true }],
      father_name: [{ value: '', disabled: true }],
      mother_name: [{ value: '', disabled: true }],
      // form_of_payment_id: ['', [Validators.required]]
    });

    // export
    this.documentalType = this.formBuilder.group({
      company_id: [''],
      documento: ['', [Validators.required]],
      date_issure: ['', [Validators.required]],
      date_end: ['', [Validators.required]],
    });
    this.init()
  }


  printPDF(item: any) {
    return `${this.url}/exportacao/enrollment/${item.id}`;
  }

  init() {
    const token = this.authService.getUser().token

    this.clientApi.getAllRegistrationDropoutView(token).subscribe(data => {
      this.isLoading = false
      const response = data as EnrollementView
      this.baseResponse = data as any

      this.formulario.controls['enrollment_number'].setValue(response.enrollment_number)
    })
    this.formulario.controls['enrollment_number'].setValue(this.baseResponse.enrollment_number)
  }


  handleRemove(id: number, params?: any) {
    const token = this.authService.getUser().token
    this.clientApi.deleteRegistration(id, token, params).subscribe(data => {
      if (data) {
        this.alertService.AlertSucess("Aluno adicionado aos estudantes!")
        this.router.navigate(['dash/secretary/registration'])
        this.init()
        var datas = data as any
        this.datas = (datas);
        // this.router.navigate([''])
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }

  patchValues(item: any) {
    if (item) {
      this.formulario.patchValue({
        enrollment_id: item.id,
        student_id: item.student_id,
        name: item.student.name,
        gender: item.student.gender,
        identity: item.student.identity,
        course_id: item.course_id,
        period_id: item.period_id,
        school_year_id: item.school_year_id,
        classe_id: item.classe_id,
        turma_id: item.turma_id,
        class_room_id: item.class_room_id,
        enrollment_number: item.enrollment_number,
        address: item.student.address,
        photo: item.student.photo,
        father_name: item.student.father_name,
        mother_name: item.student.mother_name
      });
    }
  }

  fileA: any
  onFileChange(event: any) {
    let file = event?.target?.files[0];
    if (file) {
      const upload: any = new FormData();
      upload.append('photo', file);
      this.fileA = upload
      this.formulario.controls['photo'].setValue(upload)
    }
  }




  selectedOptios: any;

  trasp: any
  setOpt(value: any): void {
    if (value) {
      this.trasp = value
      return
    }
    this.trasp = false
  }


  exportDocumento(): any {
    if (this.documentalType.valid) {
      const formattedDateIssure = this.formatDatetimeLocal(this.documentalType.value.date_issure);
      const formattedDateEnd = this.formatDatetimeLocal(this.documentalType.value.date_end);

      this.documentalType.controls['date_issure'].setValue(formattedDateIssure);
      this.documentalType.controls['date_end'].setValue(formattedDateEnd);

      const formData = this.documentalType.value;
      const token = this.authService.getUser().token;

      this.clientApi.createExportacao({ ...formData, company_id: this.authService.getUser().user.company_id, token: token });
    } else {
      this.alertService.AlertInfo(
        'Por favor, preencha todos os campos obrigatórios!'
      );
    }
  }

  formatDatetimeLocal(date: any): string {
    if (!(date instanceof Date)) {
      // Tentativa de converter para Date
      date = new Date(date);
      // Verifique novamente se a conversão foi bem-sucedida
      if (!(date instanceof Date)) {
        return ''; // ou lance um erro, dependendo do comportamento desejado
      }
    }

    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const day = ('0' + date.getDate()).slice(-2);

    return `${year}-${month}-${day}`;
  }



  // searchPage
  searchPage: boolean = false;
  removeSearchPage() {
    this.searchPage = false;
    this.filteredItems = []
  }
  openSearchPage() {
    this.searchPage = true;
  }


  filteredItems: any = [];
  filterItemsByProperty(items: any[], searchTerm: string): any[] {
    return items.filter((item) => {
      if (
        item.student &&
        (
          (item.student.identity && item.student.identity.toLowerCase().includes(searchTerm.toLowerCase())) ||
          (item.student.name && item.student.name.toLowerCase().includes(searchTerm.toLowerCase()))
        )
      ) {
        return true;
      }
      return false;
    });
  }

  onSearch(searchTerm: string) {
    if (searchTerm.length > 0) {
      this.filteredItems = this.filterItemsByProperty(this.baseResponse.enrollment, searchTerm);
    } else {
      this.filteredItems = []
    }
  }
}


export interface EnrollementView {
  enrollment: IRegistration[],
  enrollment_number: string,
  items: {
    courses: ICourse[],
    periods: any;
    classe: IClass[];
    turmas: ITurma[];
    company_id: number
    classrooms: IClassRoom[];
    school_year: ISchoolYear[]
  }
}