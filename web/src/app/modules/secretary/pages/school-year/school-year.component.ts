import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { SecretaryService } from '../../services/secretary.service';
import { ISchoolYear } from 'src/app/shared/models/schoolYear';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-school-year',
  templateUrl: './school-year.component.html',
  styleUrls: ['./school-year.component.css']
})
export class SchoolYearComponent implements OnInit {
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

  datas: ISchoolYear = {
    school_year: []
  }
  formulario!: FormGroup

  isLoading: boolean = true
  constructor(private clientApi: SecretaryService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }
    base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, 
    { name: 'Extra',  url: '/dash/pedagogical-area/' }, { name: 'Ano Lectivo',  url: '/dash/pedagogical-area/schoolYear' }]
  
 
 
 
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
    this.clientApi.getAllSchoolYear(token).subscribe(data => {
      const response = data as ISchoolYear
      this.datas.school_year = response.school_year
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteSchoolYear(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Ano lectivo deletado com sucesso!")
        this.getDatas()
      }
    },
      err => {
        alert(err.error.message)
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
      this.clientApi.updateSchoolYear({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Ano lectivo actualizado com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        alert(err.error.message)
      }
      )
    }
  }

  onSave(){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.createSchoolYear({description: formData.description, designation: formData?.designation, token: token}).subscribe(data => {
        this.alertService.AlertSucess("Ano lectivo cadastrado com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        alert(err.error.message)
      }
      )
    }
  }

}
