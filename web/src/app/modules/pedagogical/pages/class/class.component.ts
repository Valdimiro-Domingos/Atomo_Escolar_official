import { Component, OnInit, ViewChild } from '@angular/core';
import { PedagogicalService } from '../../services/pedagogical.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IClass } from 'src/app/shared/models/class';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-class',
  templateUrl: './class.component.html',
  styleUrls: ['./class.component.css']
})
export class ClassComponent implements OnInit {
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

  datas: IClass = {
    classe: []
  }
  formulario!: FormGroup
  isLoading: boolean = false


  constructor(private clientApi: PedagogicalService,  private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }
    base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Certificado',  url: '/dash/pedagogical-area/class' }]
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
    this.clientApi.getAllClass(token).subscribe(data => {
      const response = data as IClass
      this.datas.classe = response.classe
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteClass(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Classe deletada com sucesso!")
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
      ...item
    });
  }


  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateClass({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Classe actualizada com sucesso!")
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
      this.clientApi.createClass({description: formData.description, designation: String(formData?.designation), token: token}).subscribe(data => {
        this.alertService.AlertSucess("Classe cadastrada com sucesso!")
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
