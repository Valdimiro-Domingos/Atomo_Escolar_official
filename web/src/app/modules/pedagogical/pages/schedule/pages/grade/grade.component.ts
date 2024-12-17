import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { ISchedule, ScheduleItem } from 'src/app/shared/models/schedule';
import { IScheduleMini } from 'src/app/shared/models/scheduleMini';
import { ITrimester } from 'src/app/shared/models/trimester';
import { IUsers } from 'src/app/shared/models/users';
import { IEstudent, IGrade, gradeItem } from 'src/app/shared/models/grade';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { IRegistration } from 'src/app/shared/models/registration';
import { SecretaryService } from 'src/app/modules/secretary/services/secretary.service';

@Component({
  selector: 'app-grade',
  templateUrl: './grade.component.html',
  styleUrls: ['./grade.component.css']
})
export class GradeComponent implements OnInit {
 
  
  isLoading: boolean = false
  @ViewChild(FormModalComponent) formModal!: FormModalComponent;
  id: number | null = 0
  name: string = ""


  urlResponse: { 
   students: IEstudent[] | any;
   grads: gradeItem[]
  } = {
    students : [],
    grads : []
  }
  
  
  schedule_id: any
  scheduleDatas: ISchedule = {
    schedules: []
  }
 
  formulario!: FormGroup
    base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, 
  { name: 'Gestão de Pauta',  url: '/dash/pedagogical-area/schedule' },
  { name: 'Pauta',  url: '/dash/pedagogical-area/schedule/'},
  { name: 'Mini-Pauta',  url: '/dash/pedagogical-area/mini-schedule/schedule/'+this.route.snapshot.paramMap.get('id')},
   { name: 'Notas',  url: ('/dash/pedagogical-area/grade/mini-schedule/'+this.route.snapshot.paramMap.get('id')) },
  ]

  constructor(private route: ActivatedRoute, private secretaryService: SecretaryService, private formBuilder: FormBuilder, private alertService: AlertService, private clientApi: PedagogicalService, private router: Router, private authService: AuthService) { }

  ngOnInit(): void {
    let shedule_id = null;
    this.isLoading = true
    this.formulario = this.formBuilder.group({
      student_id: [''],
      id : [''],
      continuous_evaluation_average: ['', [Validators.required]],
      teachers_test_score: ['', [Validators.required]],
      quarterly_test_score: ['', [Validators.required]],
    });
    const token = this.authService.getUser().token
    this.id = Number(this.route.snapshot.paramMap.get('id'));
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getNotesScheduleMiniId(Number(this.id), token).subscribe(data => {
    this.isLoading = false;
      const response = data as any
      this.urlResponse = response
    })


  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteGrade(id, token).subscribe((data : any) => {
      if(data){
        this.alertService.AlertSucess("Dados deletado com sucesso!")
        this.urlResponse = data
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }

  patchValues(item: any) {
    this.formulario.patchValue({
      id : item?.id,
      student_id: item?.student_id,
      continuous_evaluation_average: item?.continuous_evaluation_average,
      teachers_test_score: item?.teachers_test_score,
      quarterly_test_score: item?.quarterly_test_score,
    });
  }

  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateGrade({...formData, mini_schedule_id: this.id, token: token, 
        id: item.id})
      .subscribe(data => {
        this.alertService.AlertSucess("Dados actualizado com sucesso!")
        const response = data as any
        this.formModal.handleClose()
        this.urlResponse = response
        this.formulario.reset()
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
      this.clientApi.createGrade({...formData, mini_schedule_id: this.id, token: token}).subscribe(data => {
          this.alertService.AlertSucess("Dados cadastrado com sucesso!")
          
          const response = data as any
          this.urlResponse = response
          this.formModal.handleClose()
          this.formulario.reset()
      },
      err => {
        this.alertService.AlertError(err.error.message)
        this.formulario.reset()
        this.formModal.handleClose()
      }
      )
    }else{
      this.alertService.AlertInfo('Por favor, preencha todos os campos obrigatórios!');
    }
  }


}
