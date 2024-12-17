import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IRole } from 'src/app/shared/models/role';
import { DefinitionService } from '../../services/definition.service';
import { IUsers } from 'src/app/shared/models/users';
import { IDepartament } from 'src/app/shared/models/department';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.css']
})
export class UsersComponent implements OnInit {
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

  datas: IUsers = {
    users: []
  }

  departamentDatas: IDepartament = {
    departaments: []
  }

  roleDatas: IRole = {
    roles: []
  }

  formulario!: FormGroup
  base = [{ name: 'Definições',  url: '/definitions' }, { name: 'Gestão de Utilizadores',  url: '/dash/definitions/users' }, { name: 'Utilizador',  url: '/dash/definitions/users' }]
  constructor(private clientApi: DefinitionService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      name: ['', [Validators.required]],
      email: ['', [Validators.required]],
      role_id: ['', [Validators.required]],
      departament_id: ['']
    });

    this.formPass = this.formBuilder.group({
      id : [''],
      pass : ['', [Validators.required]],
      pass_new: ['', [Validators.required]],
      pass_new_confirmation : ['', [Validators.required]]
    });


    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllUsers(token).subscribe(data => {
      const response = data as IUsers
      this.datas.users = response.users
    })

    this.clientApi.getAllDepartment(token).subscribe(data => {
      const response = data as IDepartament
      this.departamentDatas.departaments = response.departaments
    })

    this.clientApi.getAllRoles(token).subscribe(data => {
      const response = data as IRole
      this.roleDatas.roles = response.roles
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteUser(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Utilizador deletado com sucesso!")
        this.getDatas()
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }

  patchValues(item: any) {
   if(item){
     this.formulario.patchValue({
      ...item,
      role_id: item.role.id,
      departament_id: item.departament.id
    });
   }
  }


  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateUser({...formData, token: token, id: item.id}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Utilizador actualizado com sucesso!")
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
      this.clientApi.createUser({...formData, token: token}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Utilizador criado com sucesso!")
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


  
  fechar(){
    this.isPass = false;
    this.formPass.reset();
  }
  formPass !: FormGroup;
  isPass : boolean = false;
  UpdatePass(){
    // datas.companies?.id
    if(this.formPass.valid){
      const token = this.authService.getUser().token
      this.clientApi.updateUserPass({...this.formPass.value, token: token}).subscribe(data => {
        if(data){
          this.fechar();
          this.alertService.AlertSucess("Senha de Utilizador actualizado!")
          this.formulario.reset()
          this.formModalComponent.handleClose()
          this.getDatas()
        }
      },
      err => {
        this.alertService.AlertError(err.error.message)
      })
    }
  }

  resetPass(id : any){
    // datas.companies?.id
    this.alertService.AlertProgress("Solicitando", 'Alteração de senha');
      const token = this.authService.getUser().token
      this.clientApi.updateUserPassReset({id: id, token: token}).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Senha redifinida!")
        }
      },
      err => {
        this.alertService.AlertInfo("Lamentamos, problema tecnico!")
      })
  }
}
