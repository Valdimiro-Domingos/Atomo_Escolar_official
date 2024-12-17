import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IRole } from 'src/app/shared/models/role';
import { DefinitionService } from '../../services/definition.service';
import { IPermission } from 'src/app/shared/models/permission';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-permissions',
  templateUrl: './permissions.component.html',
  styleUrls: ['./permissions.component.css']
})
export class PermissionsComponent implements OnInit {
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
  
  base = [{ name: 'Definições',  url: '/definitions' }, { name: 'Gestão de Utilizadores',  url: '/dash/definitions' },  { name: 'Permissões',  url: '/dash/definitions/permissions' }]

  datas: IPermission = {
    permissions: []
  }
  formulario!: FormGroup

  constructor(private clientApi: DefinitionService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      permission: ['', [Validators.required]],
    });
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllPermissions(token).subscribe(data => {
      const response = data as IPermission
      this.datas.permissions = response.permissions
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deletePermission(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Permissão deletada com sucesso!")
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
      this.clientApi.updatePermission({...formData, token: token, id: item.id}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Permissão actualizada com sucesso!")
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
      this.clientApi.createPermission({...formData, token: token}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Permissão criada com sucesso!")
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
