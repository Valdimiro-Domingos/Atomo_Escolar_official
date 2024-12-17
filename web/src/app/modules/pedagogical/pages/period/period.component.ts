import { Component, OnInit, ViewChild } from '@angular/core';
import { PedagogicalService } from '../../services/pedagogical.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IPeriod } from 'src/app/shared/models/period';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-period',
  templateUrl: './period.component.html',
  styleUrls: ['./period.component.css']
})
export class PeriodComponent implements OnInit {
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
     base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Período',  url: '/dash/pedagogical-area/period' }]

  datas: any = {
    periods: []
  }
  isLoading: boolean = false
  formulario!: FormGroup

  constructor(private clientApi: PedagogicalService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.isLoading = true
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: [''],
      time_start: ['', [Validators.required]],
      time_end: ['', [Validators.required]]
    });
    this.getDatas()
  }


  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllPeriod(token).subscribe(data => {
      const response = data
      this.datas = response
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deletePeriod(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Período deletado com sucesso!")
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
      description: item.description,
      time_start: item.time_start,
      time_end: item.time_end
    });
  }


  onUpdate(item: any){
    if (this.formulario.valid) {
      let formData = this.formulario.value
      const token = this.authService.getUser().token
      formData = {...formData, token: token, id: item.id}
      this.clientApi.updatePeriod(formData).subscribe(data => {
        this.getDatas()
        this.alertService.AlertSucess("Período actualizado com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }

  onSave(){
    if (this.formulario.valid) {
      let formData = this.formulario.value
      const token = this.authService.getUser().token
      formData = {...formData, token: token}
      this.clientApi.createPeriod(formData).subscribe(data => {
        if(data){
          this.getDatas()
          this.alertService.AlertSucess("Período cadastrado com sucesso!")
          this.formulario.reset()
          this.formModalComponent.handleClose()
        }
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }



}
