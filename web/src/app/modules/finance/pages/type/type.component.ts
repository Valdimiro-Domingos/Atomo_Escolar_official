import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { ITax } from 'src/app/shared/models/tax';
import { FinanceService } from '../../services/finance.service';
import { IType } from 'src/app/shared/models/type';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-type',
  templateUrl: './type.component.html',
  styleUrls: ['./type.component.css']
})
export class TypeComponent implements OnInit {
  countItem : number = 0;
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
   base = [{ name: 'Finanças',  url: '/dash/finance' }, { name: 'Artigos',  url: '/dash/finance/type' }]

  datas: IType = {
    article_types: []
  }
  formulario!: FormGroup
  isLoading: boolean = false


  constructor(private clientApi: FinanceService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

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
    this.clientApi.getAllType(token).subscribe(data => {
      const response = data as IType
      this.datas.article_types = response.article_types
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteType(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Tipo de artigo deletada com sucesso!")
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
      this.clientApi.updateType({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Tipo de artigo actualizada com sucesso!")
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
      this.clientApi.createType({description: formData.description, designation: String(formData?.designation), token: token}).subscribe(data => {
        this.alertService.AlertSucess("Tipo de artigo cadastrada com sucesso!")
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
