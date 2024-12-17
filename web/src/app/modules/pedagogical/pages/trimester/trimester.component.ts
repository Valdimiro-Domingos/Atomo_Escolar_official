import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IDiscipline } from 'src/app/shared/models/discipline';
import { PedagogicalService } from '../../services/pedagogical.service';
import { ITrimester } from 'src/app/shared/models/trimester';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-trimester',
  templateUrl: './trimester.component.html',
  styleUrls: ['./trimester.component.css']
})
export class TrimesterComponent implements OnInit {
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
  isLoading: boolean = false

  datas: ITrimester = {
    trimestres: []
  }
  formulario!: FormGroup

    base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Extra',  url: '/dash/pedagogical-area/' },{ name: 'Trimestre',  url: '/dash/pedagogical-area/trimester' }]

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
    this.clientApi.getAllTrimester(token).subscribe(data => {
      const response = data as ITrimester
      this.datas.trimestres = response.trimestres
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteTrimester(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Trimestre deletado com sucesso!")
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
      this.clientApi.updateTrimester({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Trimestre actualizado com sucesso!")
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
      this.clientApi.createTrimester({description: formData.description, designation: formData?.designation, token: token}).subscribe(data => {
        this.alertService.AlertSucess("Trimestre cadastrado com sucesso!")
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
