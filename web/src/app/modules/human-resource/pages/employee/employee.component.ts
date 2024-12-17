import { Component, OnInit } from '@angular/core';
import * as feather from 'feather-icons';
@Component({
  selector: 'app-employee',
  templateUrl: './employee.component.html',
  styleUrls: ['./employee.component.css']
})
export class EmployeeComponent implements OnInit {
  
  isLoading: boolean = false
  base = [{ name: 'Recursos Humano',  url: '/dash/human-resource' }, { name: 'Área de Funcionários',  url: '/dash/human-resource/'}, { name: 'Funcionários',  url: '/dash/human-resource/employee'}]
  
  constructor() { }

  ngOnInit(): void {
    this.isLoading = false
  
    // feather.replace();
  }
  
  
    handleRemove(id: number){
    // const token = this.authService.getUser().token
    // this.clientApi.deleteClassRoom(id, token).subscribe(data => {
    //   if(data){
    //     this.alertService.AlertSucess("Sala deletada com sucesso!")
    //     this.getDatas()
    //   }
    // },
    //   err => {
    //     this.alertService.AlertError(err.error.message)
    //   }
    // )
  }

  patchValues(item: any) {
    // this.formulario.patchValue({
    //   designation: item.designation,
    //   description: item.description
    // });
  }

}
