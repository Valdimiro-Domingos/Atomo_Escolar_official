import { Component, OnInit, ViewChild } from '@angular/core';
import * as feather from 'feather-icons';
import { PrimeNGConfig } from 'primeng/api';
import { NavBarHamburguerComponent } from './modules/admin/components/header/components/nav-bar-hamburguer/nav-bar-hamburguer.component';
import { AuthService } from './services/auth/auth.service';
import { AlertService } from './services/alert/alert.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit {
  title = 'web';

  constructor(
    private primengConfig: PrimeNGConfig,
    private authService: AuthService,
    private alert: AlertService
  ) {}

  ngOnInit(): void {
    this.primengConfig.ripple = true;
    feather.replace();
  }
}
