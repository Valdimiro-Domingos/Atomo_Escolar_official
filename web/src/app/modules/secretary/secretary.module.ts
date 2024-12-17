import {  NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SecretaryRoutingModule } from './secretary-routing.module';
import { RegistrationComponent } from './pages/registration/registration.component';
import { ConfirmationComponent } from './pages/confirmation/confirmation.component';
import { SharedModule } from '../shared/shared.module';
import { AdminModule } from '../admin/admin.module';
import { StudentsComponent } from './pages/students/students.component';
import { RevenueComponent } from './pages/revenue/revenue.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SchoolYearComponent } from './pages/school-year/school-year.component';
import { PdfViewerModule } from 'ng2-pdf-viewer';
import { FindIndexPipe } from 'src/app/pipes/find-index.pipe';
import { DropoutComponent } from './pages/dropout/dropout.component';
import { LimitCharactersPipe } from 'src/app/pipes/limit-characters.pipe';



@NgModule({
  declarations: [
    DropoutComponent,
    RegistrationComponent,
    ConfirmationComponent,
    StudentsComponent,
    RevenueComponent,
    SchoolYearComponent,
    DropoutComponent,
    LimitCharactersPipe
  ],
  imports: [
    CommonModule,
    SecretaryRoutingModule,
    SharedModule,
    AdminModule,
    FormsModule,
    PdfViewerModule,
    ReactiveFormsModule,
  ],
})
export class SecretaryModule { }
