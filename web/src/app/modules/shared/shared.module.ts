import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MaterialModule } from '../material/material.module';
import { BreadcrumbComponent } from '../admin/components/breadcrumb/breadcrumb.component';
import { PreLoadComponent } from '../admin/components/pre-load/pre-load.component';
import { FormModalComponent } from '../admin/components/form-modal/form-modal.component';
import {TableModule} from 'primeng/table';
import { ChartModule } from 'primeng/chart'
import {ToastModule} from 'primeng/toast';
import { PdfViewModalComponent } from '../admin/components/pdf-view-modal/pdf-view-modal.component';
import {FileUploadModule} from 'primeng/fileupload';
import { NavBarHamburguerComponent } from '../admin/components/header/components/nav-bar-hamburguer/nav-bar-hamburguer.component';
import { FindIndexPipe } from 'src/app/pipes/find-index.pipe';
import { FormModalReportComponent } from '../admin/components/form-modal-report/form-modal-report.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { FileComponent } from '../admin/components/file/file.component';
import { FormModalUplodeComponent } from '../admin/components/form-modal-uplode/form-modal-uplode.component';
import { PasswordModule } from 'primeng/password';
import { FormFullComponent } from '../admin/components/form-full/form-full.component';
import { DropdownModule } from 'primeng/dropdown';
import { CustomSelectComponent } from '../admin/components/custom-select/custom-select.component';


@NgModule({
  declarations: [
    FileComponent,
    FormModalReportComponent,
    BreadcrumbComponent,
    PreLoadComponent,
    PdfViewModalComponent,
    FormModalComponent,
    FindIndexPipe,
    FormFullComponent,
    FormModalUplodeComponent,
    CustomSelectComponent
    // NavBarHamburguerComponent

  ],
  imports: [
    CommonModule,
    MaterialModule,
    TableModule,
    ChartModule,
    ToastModule,
    FormsModule,
    PasswordModule,
    ReactiveFormsModule,
    FileUploadModule,


    DropdownModule
  ],
  exports: [
    FileComponent,
    PasswordModule,
    FormFullComponent,
    FormModalUplodeComponent,
    FormModalReportComponent,
    BreadcrumbComponent,
    PreLoadComponent,
    PdfViewModalComponent,
    FormModalComponent,
    TableModule,
    ChartModule,
    FindIndexPipe,
    CustomSelectComponent,
    // NavBarHamburguerComponent,
    ToastModule,
    FileUploadModule,
    DropdownModule,
  ]
})
export class SharedModule { }
