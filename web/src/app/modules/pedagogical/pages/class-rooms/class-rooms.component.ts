import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { ICourse } from 'src/app/shared/models/course';
import { PedagogicalService } from '../../services/pedagogical.service';
import { IClassRoom } from 'src/app/shared/models/classRoom';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-class-rooms',
  templateUrl: './class-rooms.component.html',
  styleUrls: ['./class-rooms.component.css']
})
export class ClassRoomsComponent implements OnInit {
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
  datas: IClassRoom = {
    classrooms: []
  }
  formulario!: FormGroup
  constructor(private clientApi: PedagogicalService,  private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }
  base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Sala',  url: '/dash/pedagogical-area/classRooms' }]
  
  
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
    this.clientApi.getAllClassRoom(token).subscribe(data => {
      const response = data as IClassRoom
      this.datas.classrooms = response.classrooms
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteClassRoom(id, token).subscribe(data => {
      if(data){ 
        this.alertService.AlertSucess("Sala deletada com sucesso!")
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
      this.clientApi.updateClassRoom({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Sala actualizada com sucesso!")
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
      this.clientApi.createClassRoom({description: formData.description, designation: formData?.designation, token: token}).subscribe(data => {
        this.alertService.AlertSucess("Sala cadastrada com sucesso!")
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
