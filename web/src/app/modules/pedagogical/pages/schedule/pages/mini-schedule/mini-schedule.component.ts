import { Component, OnInit, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth/auth.service';
import { ISchedule, ScheduleItem } from 'src/app/shared/models/schedule';
import { AlertService } from 'src/app/services/alert/alert.service';
import { IScheduleMini, ScheduleMiniItem } from 'src/app/shared/models/scheduleMini';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { IUsers } from 'src/app/shared/models/users';
import { ITrimester } from 'src/app/shared/models/trimester';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { IDiscipline } from 'src/app/shared/models/discipline';
import { environmentSettings } from 'src/environments/environment.dev';
import { ICourse } from 'src/app/shared/models/course';
import { IClass } from 'src/app/shared/models/class';
import { ITurma } from 'src/app/shared/models/turma';
import { IClassRoom } from 'src/app/shared/models/classRoom';
import { ISchoolYear } from 'src/app/shared/models/schoolYear';
import { SecretaryService } from 'src/app/modules/secretary/services/secretary.service';

@Component({
  selector: 'app-mini-schedule',
  templateUrl: './mini-schedule.component.html',
  styleUrls: ['./mini-schedule.component.css']
})
export class MiniScheduleComponent implements OnInit {


  constructor(private route: ActivatedRoute, private formBuilder: FormBuilder, private apiClient: SecretaryService,private alertService: AlertService, private clientApi: PedagogicalService, private router: Router, private authService: AuthService) {
    this.formulario = this.formBuilder.group({
      id : [''],
      schedule_id : [''],
      profeessor_id: ['', [Validators.required]],
      trimestre_id: ['', [Validators.required]],
      discipline_id: ['', [Validators.required]],
    });
    this.id = Number(this.route.snapshot.paramMap.get('id'));
  }


  selectedFile!: File | any;
  url: string = environmentSettings.endpoint.url

  
    isLoading: boolean = false
  
   base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, 
  { name: 'Gestão de Pauta',  url: '/dash/pedagogical-area/schedule' },
  { name: 'Pauta',  url: '/dash/pedagogical-area/schedule/'},
  { name: 'Mini-Pauta',  url: '/dash/pedagogical-area/mini-schedule/schedule/'+this.route.snapshot.paramMap.get('id')}]
  
  
  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;
  id: any
  name: string = ""

  heading = ['#', 'Descrição', 'Professor','Disciplina','Trimestre', 'Opções']
  
  
  datas: IScheduleMini = {
    mini_schedules: []
  }
  
  baseResponse : {
   mini_schedules: ScheduleMiniItem[] | any[]
   items : {
     company_id? : number
     school_year?: ISchoolYear | any,
     disciplines? : IDiscipline | any,
     trimestres: ITrimester | any,
     professores :  IUsers | any,
      [key: string]: any
   }
  } = {
    mini_schedules: [],
    items: {
      trimestres: [],
      disciplines: [],
      professores: [],
    }
  }


  @ViewChild(FormModalComponent) moda!: FormModalComponent
  formulario!: FormGroup




  ngOnInit(): void {
    this.isLoading = true
    this.getDatas();
    this.formulario.controls['schedule_id'].setValue(Number(this.route.snapshot.paramMap.get('id')))
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllScheduleMini(Number(this.id), token).subscribe(data => {
      const response = data as any
      this.isLoading = false
      
      this.baseResponse = response
    }, err => { this.isLoading = false})
    
    
  }
  
  

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteScheduleMini(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Mini Pauta deletada com sucesso!")
         const response = data as any
        this.baseResponse = response
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }
  
  onFileSelected(event: any) {
      this.selectedFile = event.target.files[0];
  }
  
  uplodeFile(id : string){
    if(this.selectedFile.name){
      const token = this.authService.getUser().token;
      var form = new FormData();
      form.append('file', this.selectedFile);
      this.clientApi.uplodeScheduleMini(id, form ,token).subscribe({
        next: () => {
          this.alertService.AlertSucess("Mini Pauta enviada com sucesso!")
          this.selectedFile = null;
          this.getDatas();
        },
        error: (err) => {
          this.alertService.AlertError(err.error.message)
        }
      })
    }else{
      this.alertService.AlertInfo("Carrega o arquivo")
    }
  }

  patchValues(item: any) {
    if(item != null){
      this.formulario.patchValue({
        id: item.id,
        profeessor_id: item?.professor?.id ?? null,
        trimestre_id: item.trimestre.id,
        discipline_id: item.discipline.id
      });
      }else{
        this.formulario.reset()
      }
  }

  printPDF(item: any) {
    return `${environmentSettings.endpoint.urlImage}/viewFile/${item.file}`;
  }

  onUpdate(item: any) {
    if (this.formulario.valid) {
          // Pegando o ID do schedule da rota
          this.formulario.value.schedule_id = Number(this.route.snapshot.paramMap.get('id'));
  
          // Obtendo o token do usuário autenticado
          const token = this.authService.getUser().token;
  
  
          // Chamando a API para atualizar o schedule
          this.clientApi.updateScheduleMini({...this.formulario.value, token : token}).subscribe(
              (data) => {
                  const response = data as any;
                  this.baseResponse = response;
                  this.alertService.AlertSucess("Mini Pauta actualizada com sucesso!");
                  this.formulario.reset();
                  this.formModalComponent.handleClose();
              },
              (err) => {
                  this.alertService.AlertError(err.error.message);
              }
          );
      } else {
          this.alertService.AlertInfo('Por favor, preencha todos os campos obrigatórios!');
      }
  }

  onSave() {
    if (this.formulario.valid) {
      this.formulario.value.schedule_id = Number(this.route.snapshot.paramMap.get('id'));
  
      const token = this.authService.getUser().token;
      
      this.clientApi.createScheduleMini({...this.formulario.value, token : token}).subscribe(data => {
        this.alertService.AlertSucess("Mini Pauta cadastrada com sucesso!");
        this.formulario.reset();
        
        // refresh
        const response = data as any;
        this.baseResponse = response;
        this.formModalComponent.handleClose();
      }, err => {
        this.formModalComponent.handleClose();
        this.formulario.reset();
        this.alertService.AlertError(err.error.message);
      });
    } else {
      this.alertService.AlertInfo('Por favor, preencha todos os campos obrigatórios!');
    }
  }


}
