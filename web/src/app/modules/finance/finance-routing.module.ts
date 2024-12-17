import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ExpensesComponent } from './pages/expenses/expenses.component';
import { PaymentsComponent } from './pages/payments/payments.component';
import { ArticlesComponent } from './pages/articles/articles.component';
import { CategoryComponent } from './pages/category/category.component';
import { TaxComponent } from './pages/tax/tax.component';
import { RetentionComponent } from './pages/retention/retention.component';
import { TypeComponent } from './pages/type/type.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/dash/finance/invoice-receipt',
    pathMatch: 'full'
  },
  {
    path: 'expenses',
    component: ExpensesComponent
  },
  {
    path: 'invoice-receipt',
    component: PaymentsComponent
  },
  {
    path: 'articles',
    component: ArticlesComponent
  },
  {
    path: 'category',
    component: CategoryComponent
  },
  {
    path: 'tax',
    component: TaxComponent
  },
  {
    path: 'retention',
    component: RetentionComponent
  },
  {
    path: 'type',
    component: TypeComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class FinanceRoutingModule { }
