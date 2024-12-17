import { NavBarHamburguerComponent } from './../header/components/nav-bar-hamburguer/nav-bar-hamburguer.component';
import { Component, OnInit, ViewChild } from '@angular/core';

@Component({
  selector: 'app-layout',
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.css']
})
export class LayoutComponent implements OnInit {
  @ViewChild(NavBarHamburguerComponent) nav!: NavBarHamburguerComponent;
  constructor() { }

  ngOnInit(): void {
  }

}
