import { AfterViewInit, ChangeDetectorRef, Component, Input, OnInit, SimpleChanges, ViewChild, ViewChildren } from '@angular/core';
import { Router } from '@angular/router';
import * as feather from 'feather-icons';
import { SideBarComponent } from '../../../side-bar/side-bar.component';

@Component({
  selector: 'app-nav-bar-hamburguer',
  templateUrl: './nav-bar-hamburguer.component.html',
  styleUrls: ['./nav-bar-hamburguer.component.css']
})
export class NavBarHamburguerComponent implements OnInit {
  @Input() title: string = '';
  @Input() isIcon: boolean = false;
  @Input() isHeader: boolean = false
  option: string = '';
  showModal: boolean = false;
  urlBase: string = ''

    constructor(private route: Router, private cdr: ChangeDetectorRef) {}

    ngOnInit(): void {
      feather.replace()
      this.getLoad()
    }

    getLoad(){
      const url = this.route.url
      const newUrl = url.split('/').filter(i => i != '')
      this.option = newUrl[1]
      this.urlBase = newUrl[2]
    }

    ngAfterViewChecked(): void {
      this.getLoad()
      this.cdr.detectChanges();
    }

  setUrlBase(url: string){
    this.urlBase = url
  }

  setOption(opt: string){
    this.option = opt
  }

  refresh(){
    feather.replace()
  }




  handleShow(): void {
    this.showModal = !this.showModal;
  }

  handleClose(): void {
    this.showModal = false;
  }

  handleBackdropClick(event: MouseEvent): void {
    if (event.target === event.currentTarget) {
      this.handleClose();
    }
  }
}
