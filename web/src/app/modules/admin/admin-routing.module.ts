import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { LayoutComponent } from './components/layout/layout.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/dash/home',
    pathMatch: 'full'
  },
  {
    path: '',
    component: LayoutComponent,
    children: [
      {
        path: 'home',
        component: HomeComponent
      },
      {
        path: 'secretary',
        loadChildren: () => import('../secretary/secretary.module').then(m => m.SecretaryModule)
      },
      {
        path: 'finance',
        loadChildren: () => import('../finance/finance.module').then(m => m.FinanceModule)
      },
      // {
      //   path: 'human-resource',
      //   loadChildren: () => import('../human-resource/human-resource.module').then(m => m.HumanResourceModule)
      // },
      {
        path: 'pedagogical-area',
        loadChildren: () => import('../pedagogical/pedagogical.module').then(m => m.PedagogicalModule)
      },
      {
        path: 'definitions',
        loadChildren: () => import('../definitions/definitions.module').then(m => m.DefinitionsModule)
      }
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule { }
