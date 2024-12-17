import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IDepartament } from 'src/app/shared/models/department';
import { DefinitionService } from '../../services/definition.service';
import { IRole } from 'src/app/shared/models/role';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-profiles',
  templateUrl: './profiles.component.html',
  styleUrls: ['./profiles.component.css']
})
export class ProfilesComponent implements OnInit {
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

  datas: IRole = {
    roles: []
  }
  formulario!: FormGroup

  base = [{ name: 'Definições',  url: '/definitions' }, { name: 'Gestão de Utilizadores',  url: '/dash/definitions/users' }, { name: 'Cargo',  url: '/dash/definitions/role' }]
  constructor(private clientApi: DefinitionService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      role: ['', [Validators.required]],
    });
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllRoles(token).subscribe(data => {
      const response = data as IRole
      this.datas.roles = response.roles
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteRole(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Cargo deletado com sucesso!")
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
      this.clientApi.updateRole({...formData, token: token, id: item.id}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Cargo actualizado com sucesso!")
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
      this.clientApi.createRole({...formData, token: token}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Cargo criado com sucesso!")
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
