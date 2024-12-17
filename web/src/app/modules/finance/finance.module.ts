import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FinanceRoutingModule } from './finance-routing.module';
import { ExpensesComponent } from './pages/expenses/expenses.component';
import { PaymentsComponent } from './pages/payments/payments.component';
import { ArticlesComponent } from './pages/articles/articles.component';
import { SharedModule } from '../shared/shared.module';
import { AdminModule } from '../admin/admin.module';
import { MaterialModule } from '../material/material.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CategoryComponent } from './pages/category/category.component';
import { TaxComponent } from './pages/tax/tax.component';
import { RetentionComponent } from './pages/retention/retention.component';
import { TypeComponent } from './pages/type/type.component';
import { PdfViewerModule } from 'ng2-pdf-viewer';



@NgModule({
  declarations: [
    ExpensesComponent,
    PaymentsComponent,
    ArticlesComponent,
    CategoryComponent,
    TaxComponent,
    TypeComponent,
    RetentionComponent,
  ],
  imports: [
    CommonModule,
    FinanceRoutingModule,
    SharedModule,
    AdminModule,
    MaterialModule,
    FormsModule,
    ReactiveFormsModule,
    PdfViewerModule
  ]
})
export class FinanceModule { }
