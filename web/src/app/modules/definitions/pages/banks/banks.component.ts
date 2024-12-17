import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IDepartament } from 'src/app/shared/models/department';
import { DefinitionService } from '../../services/definition.service';
import { IBank } from 'src/app/shared/models/bank';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-banks',
  templateUrl: './banks.component.html',
  styleUrls: ['./banks.component.css']
})
export class BanksComponent implements OnInit {
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

  datas: IBank = {
    company_bank: []
  }
  formulario!: FormGroup
  
  base = [{ name: 'Definições',  url: '/definitions' },  { name: 'Bancos',  url: '/dash/definitions/banks' }]


  constructor(private clientApi: DefinitionService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      name: ['', [Validators.required]],
      account_number: ['', [Validators.required]],
      iban: ['', [Validators.required, Validators.minLength(25)]],
      swift: ['']
    });
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllBanks(token).subscribe(data => {
      const response = data as IBank
      this.datas.company_bank = response.company_bank
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteBank(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Conta deletada com sucesso!")
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
      this.clientApi.updateBank({...formData, token: token, id: item.id}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Conta actualizada com sucesso!")
          this.formulario.reset()
          this.formModalComponent.handleClose()
          this.getDatas()
        }
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
      this.clientApi.createBank({...formData, token: token}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Conta adicionada com sucesso!")
          this.formulario.reset()
          this.formModalComponent.handleClose()
          this.getDatas()
        }
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }

}
