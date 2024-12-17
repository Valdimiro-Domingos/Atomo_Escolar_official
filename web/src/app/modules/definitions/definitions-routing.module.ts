import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { InstitutionComponent } from './pages/institution/institution.component';
import { DepartmentsComponent } from './pages/departments/departments.component';
import { BanksComponent } from './pages/banks/banks.component';
import { UsersComponent } from './pages/users/users.component';
import { ProfilesComponent } from './pages/profiles/profiles.component';
import { PermissionsComponent } from './pages/permissions/permissions.component';
import { ReportsComponent } from './pages/reports/reports.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/dash/definitions/company',
    pathMatch: 'full'
  },
  {
    path: 'company',
    component: InstitutionComponent
  },
  {
    path: 'departaments',
    component: DepartmentsComponent
  },
  {
    path: 'banks',
    component: BanksComponent
  },
  {
    path: 'users',
    component: UsersComponent
  },
  {
    path: 'role',
    component: ProfilesComponent
  },
  {
    path: 'permissions',
    component: PermissionsComponent
  },
  {
    path: 'reports',
    component: ReportsComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DefinitionsRoutingModule { }
