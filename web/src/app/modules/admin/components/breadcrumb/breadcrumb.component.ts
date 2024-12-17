import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-breadcrumb',
  templateUrl: './breadcrumb.component.html',
  styleUrls: ['./breadcrumb.component.css']
})
export class BreadcrumbComponent implements OnInit {
  @Input() url: LinkRoutes[] = []
  @Input() urlBase: string = "Dashboard"
  @Input() urlMiddle: string = ""
  @Input() icon: string = ''

  typeUrl: boolean = false;

  constructor(private router : Router) { }

  ngOnInit(): void {
    if(!this.url){
      this.url = [
        {
          name: "Home",
          url: '/home'
        }
      ];
    }
    

    if(typeof this.typeUrl === 'string'){
      this.typeUrl = true;
    }else{
      this.typeUrl = false;
    }
  }


  page(value : string){
    this.router.navigate([value]);
  }

}
export interface LinkRoutes{
    url : string;
    name: string;
  }