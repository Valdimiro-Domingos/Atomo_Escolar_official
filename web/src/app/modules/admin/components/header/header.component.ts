import { Component, HostListener, OnInit } from '@angular/core';
import * as feather from 'feather-icons';
import { AuthService } from 'src/app/services/auth/auth.service';
import { ILogin } from 'src/app/shared/models/login';
import { environmentSettings } from 'src/environments/environment.dev';


@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  systemStatus : statuSystem = {
    status: false,
    description: '',
    time : '3h'
  };
  
  isSideBarGroup: boolean = false
  isSideBar: boolean = false
  user = {
      id: 0,
      name: '',
      email: '',
      departament_id: 0,
      role_id: 0
  }

  constructor(private authService: AuthService) {}


  ngOnInit(): void {
    feather.replace()
    this.user = this.authService.getUser()?.user
    
    this.authService.statusSystem(this.authService.getUser()?.token).subscribe((item : any)=>{
      this.systemStatus = item;
    });
  }

  toggleSideBarGroup() {
    this.isSideBarGroup =!this.isSideBarGroup
  }

  toggleSideBar(){
    this.isSideBar =!this.isSideBar
  }
  
  logoUrl(){
    return `${environmentSettings.endpoint.urlImage}/viewLogo/` + JSON.parse(localStorage.getItem("user") as any).user?.company_id ?? 'assets/images/ESCOLAR 1.png'
  }
}

interface  statuSystem {
  description : string;
  status : boolean;
  time : string;
}