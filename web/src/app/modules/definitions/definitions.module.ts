import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DefinitionsRoutingModule } from './definitions-routing.module';
import { SharedModule } from '../shared/shared.module';
import { MaterialModule } from '../material/material.module';
import { AdminModule } from '../admin/admin.module';
import { InstitutionComponent } from './pages/institution/institution.component';
import { DepartmentsComponent } from './pages/departments/departments.component';
import { BanksComponent } from './pages/banks/banks.component';
import { UsersComponent } from './pages/users/users.component';
import { ProfilesComponent } from './pages/profiles/profiles.component';
import { PermissionsComponent } from './pages/permissions/permissions.component';
import { ReportsComponent } from './pages/reports/reports.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';


@NgModule({
  declarations: [
    InstitutionComponent,
    DepartmentsComponent,
    BanksComponent,
    UsersComponent,
    ProfilesComponent,
    PermissionsComponent,
    ReportsComponent
  ],
  imports: [
    CommonModule,
    DefinitionsRoutingModule,
    SharedModule,
    MaterialModule,
    AdminModule,
    FormsModule,
    ReactiveFormsModule
  ]
})
export class DefinitionsModule { }
