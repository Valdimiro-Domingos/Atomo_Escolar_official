import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { ITurma } from 'src/app/shared/models/turma';
import { PedagogicalService } from '../../services/pedagogical.service';
import { ISchedule } from 'src/app/shared/models/schedule';
import { IClass } from 'src/app/shared/models/class';
import { IClassRoom } from 'src/app/shared/models/classRoom';
import { ICourse } from 'src/app/shared/models/course';
import { ISchoolYear } from 'src/app/shared/models/schoolYear';
import { SecretaryService } from 'src/app/modules/secretary/services/secretary.service';
import { IDiscipline } from 'src/app/shared/models/discipline';
import { AlertService } from 'src/app/services/alert/alert.service';
import { environmentSettings } from 'src/environments/environment.dev';

@Component({
  selector: 'app-schedule',
  templateUrl: './schedule.component.html',
  styleUrls: ['./schedule.component.css']
})
export class ScheduleComponent implements OnInit {
  url: string = environmentSettings.endpoint.url
  heading = ['id', 'Ano Lectivo', 'Classe', 'Sala', 'Turma', 'Curso', 'Período', 'Opções']

  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;

  datas: ISchedule = {
    schedules: []
  }
  courseDatas: ICourse = {
    courses : []
  }

  periodDatas: any = {
    periods: []
  }

  classDatas: IClass = {
    classe: []
  }

  turmaDatas: ITurma = {
    turmas: []
  }

  classRoomsData: IClassRoom = {
    classrooms: []
  }

  schoolYearDatas: ISchoolYear = {
    school_year: [],
  }

  disciplineDatas: IDiscipline = {
    disciplines: []
  }

  urlResponse : any = {
    ...this.datas,
    items : {
      ...this.courseDatas,
      ...this.periodDatas,
      ...this.classDatas,
      ...this.turmaDatas,
      ...this.classRoomsData,
      ...this.schoolYearDatas,
      ...this.disciplineDatas
    }
  }


  formulario!: FormGroup
  selectedFile!: File | any;
  isLoading: boolean = false

  constructor(private clientApi: PedagogicalService, private alertService: AlertService, private apiClient: SecretaryService, private authService: AuthService, private formBuilder: FormBuilder) { 
    this.formulario = this.formBuilder.group({
      designation: [''],
      description: [''],
      id : [''],
      school_year_id:  ['', [Validators.required]],
      course_id: ['',  [Validators.required]],
      turma_id: ['', [Validators.required]],
      class_room_id: ['', [Validators.required]],
      classe_id: ['', [Validators.required]],
      period_id: ['', [Validators.required]],
    });
  }
  
  
  
   base = [
    { name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, 
    { name: 'Gestão de Pauta',  url: '/dash/pedagogical-area/schedule' },
   { name: 'Pauta',  url: '/dash/pedagogical-area/schedule' }]

  ngOnInit(): void {
    this.isLoading = true
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllScheduleView(token).subscribe(data => {
      this.isLoading = false
      // console.log(data)
      const response = data as any
      this.urlResponse = response
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteSchedule(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Pauta deletada com sucesso!")
        this.getDatas()
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }
  patchValues(item: any) {
    if(item){
    console.log(item)
        this.formulario.patchValue({
        ...item,
        school_year_id:  item.school_year.id,
        course_id: item.course.id,
        turma_id: item.turma.id,
        class_room_id: item.class_room.id,
        classe_id: item.classe.id,
        period_id: item.period.id
        });
        return
    }
    this.formulario.reset()
  }

  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateSchedule({...formData, token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Pauta actualizada com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }else{
      this.alertService.AlertInfo('Por favor, preencha todos os campos obrigatórios!');
    }
  }

  onSave(){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.createSchedule({...formData, token: token}).subscribe(data => {
        this.alertService.AlertSucess("Pauta cadastrada com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }else{
      this.alertService.AlertInfo('Por favor, preencha todos os campos obrigatórios!');
    }
  }
  
  
  printPDF(item: any) {
    return `${environmentSettings.endpoint.urlImage}/viewFile/${item.file}`;
  }
  
  onFileSelected(event: any) {
      this.selectedFile = event.target.files[0];
  }
  
  uplodeFile(id : string){
    if(this.selectedFile.name){
      const token = this.authService.getUser().token;
      var form = new FormData();
      form.append('file', this.selectedFile);
      this.clientApi.uplodeSchedule(id, form ,token).subscribe({
        next: () => {
          this.getDatas();
          this.alertService.AlertSucess("Pauta enviada com sucesso!")
          this.selectedFile = null;
        },
        error: (err) => {
          this.alertService.AlertError(err.error.message)
        }
      })
    }else{
      this.alertService.AlertInfo("Carrega o arquivo")
    }
  }
}
