import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { FinanceService } from '../../services/finance.service';
import { ITax } from 'src/app/shared/models/tax';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-tax',
  templateUrl: './tax.component.html',
  styleUrls: ['./tax.component.css']
})
export class TaxComponent implements OnInit {
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
   base = [{ name: 'Finanças',  url: '/dash/finance' }, { name: 'Taxa',  url: '/dash/finance/tax' }]

  datas: ITax = {
    taxes: []
  }
  formulario!: FormGroup
  isLoading: boolean = false

  constructor(private clientApi: FinanceService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: ['']
    });
    this.getDatas()
  }

  getDatas(){
    this.isLoading = true

    const token = this.authService.getUser().token
    this.clientApi.getAllTax(token).subscribe(data => {
      if(data){
        const response = data as ITax
        this.datas.taxes = response.taxes
        this.isLoading = false
      }
    }, err => {
      this.isLoading = false
    })

  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteTax(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Taxa deletada com sucesso!")
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
      this.clientApi.updateTax({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Taxa actualizada com sucesso!")
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
      this.clientApi.createTax({description: formData.description, designation: String(formData?.designation), token: token}).subscribe(data => {
        this.alertService.AlertSucess("Taxa cadastrada com sucesso!")
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
