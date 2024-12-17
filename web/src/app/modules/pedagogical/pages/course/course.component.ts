import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IDiscipline } from 'src/app/shared/models/discipline';
import { PedagogicalService } from '../../services/pedagogical.service';
import { ICourse } from 'src/app/shared/models/course';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-course',
  templateUrl: './course.component.html',
  styleUrls: ['./course.component.css']
})
export class CourseComponent implements OnInit {
  filter =  [
    {
      label: 'Nome',
      select: [
        'pedro',
        'lucas',
        'fabio'
      ]
    }
  ]
  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;
   base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Curso',  url: '/dash/pedagogical-area/course' }]
  isLoading: boolean = false
  datas: ICourse = {
    courses: []
  }
  formulario!: FormGroup
  constructor(private clientApi: PedagogicalService,   private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.isLoading = true
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: ['']
    });
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllCourse(token).subscribe(data => {
      const response = data as ICourse
      this.datas.courses = response.courses
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteCourse(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Curso deletado com sucesso!")
        this.getDatas()
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }

  patchValues(item: any) {
    this.formulario.patchValue({
      designation: item.designation,
      description: item.description
    });
  }


  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateCourse({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Curso actualizado com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }

  onSave(){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.createCourse({description: formData.description, designation: formData?.designation, token: token}).subscribe(data => {
        this.alertService.AlertSucess("Curso cadastrado com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }

}
