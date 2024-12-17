import {  APP_INITIALIZER, NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminRoutingModule } from './admin-routing.module';
import { HomeComponent } from './pages/home/home.component';
import { MaterialModule } from '../material/material.module';
import { SharedModule } from '../shared/shared.module';
import { FeatherModule } from 'angular-feather';
import { allIcons } from 'angular-feather/icons';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import {MatIconModule} from '@angular/material/icon';
import { HeaderComponent } from './components/header/header.component';
import { SideBarComponent } from './components/side-bar/side-bar.component';
import { LayoutComponent } from './components/layout/layout.component';
import { FooterComponent } from './components/footer/footer.component';
import { FilterComponent } from './components/filter/filter.component';
import { TableComponent } from './components/table/table.component';
import { FormModalRemoveComponent } from './components/form-modal-remove/form-modal-remove.component';
import { SideBarGroupComponent } from './components/header/components/side-bar-group/side-bar-group.component';
import { NavBarHamburguerComponent } from './components/header/components/nav-bar-hamburguer/nav-bar-hamburguer.component';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { FormModalComponent } from './components/form-modal/form-modal.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { FilterPipe } from 'src/app/pipes/filter.pipe';
import { PdfViewModalComponent } from './components/pdf-view-modal/pdf-view-modal.component';
import { FormModalReportComponent } from './components/form-modal-report/form-modal-report.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { AuthInterceptor } from 'src/app/services/guard/auth.intercept';
import { FormReportCardComponent } from './components/form-report-card/form-report-card.component';

@NgModule({
  declarations: [
    HomeComponent,
    FilterPipe,
    HeaderComponent,
    SideBarComponent,
    LayoutComponent,
    FooterComponent,
    FilterComponent,
    TableComponent,
    SideBarGroupComponent,
    FormModalRemoveComponent,
    NavBarHamburguerComponent,
    FormReportCardComponent,
    
  ],
  imports: [
    CommonModule,
    AdminRoutingModule,
    MaterialModule,
    SharedModule,
    FontAwesomeModule,
    FormsModule,
    ReactiveFormsModule,
    MatIconModule,
    HttpClientModule,
    FeatherModule.pick(allIcons),
  ],
  exports: [
    FooterComponent,
    FormModalRemoveComponent,
    FilterComponent,
    TableComponent,
    NavBarHamburguerComponent,
    HttpClientModule,
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true,
    },
  ],
})
export class AdminModule {}

