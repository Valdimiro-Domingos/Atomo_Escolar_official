import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IClass } from 'src/app/shared/models/class';
import { PedagogicalService } from '../../services/pedagogical.service';
import { IDiscipline } from 'src/app/shared/models/discipline';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-discipline',
  templateUrl: './discipline.component.html',
  styleUrls: ['./discipline.component.css']
})
export class DisciplineComponent implements OnInit {
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
   base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Discíplina',  url: '/dash/pedagogical-area/discipline' }]
  isLoading: boolean = false
  datas: IDiscipline = {
    disciplines: []
  }
  formulario!: FormGroup


  constructor(private clientApi: PedagogicalService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

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
    this.clientApi.getAllDiscipline(token).subscribe(data => {
      const response = data as IDiscipline
      this.datas.disciplines = response.disciplines
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteDiscipline(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Discíplina deletada com sucesso!")
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
      this.clientApi.updateDiscipline({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Discíplina actualizada com sucesso!")
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
      this.clientApi.createDiscipline({description: formData.description, designation: formData?.designation, token: token}).subscribe(data => {
        this.alertService.AlertSucess("Discíplina cadastrada com sucesso!")
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
