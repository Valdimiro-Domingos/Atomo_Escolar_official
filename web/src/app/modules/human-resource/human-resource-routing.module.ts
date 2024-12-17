import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { EmployeeComponent } from './pages/employee/employee.component';
import { SalaryComponent } from './pages/salary/salary.component';
import { VocationComponent } from './pages/vocation/vocation.component';
import { AttendanceComponent } from './pages/attendance/attendance.component';

const routes: Routes = [
    {
    path: 'dash/human-resource',
    redirectTo: '/dash/human-resource/employee',
    pathMatch: 'full'
  },
  {
    path: '',
    component: EmployeeComponent
  },
  {
    path: 'employee',
    component: EmployeeComponent
  },
  {
    path: 'attendance',
    component: AttendanceComponent
  },
  {
    path: 'vacation',
    component: VocationComponent
  },
  {
    path: 'salary',
    component: SalaryComponent
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HumanResourceRoutingModule { }
