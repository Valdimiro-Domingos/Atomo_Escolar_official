import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RegistrationComponent } from './pages/registration/registration.component';
import { ConfirmationComponent } from './pages/confirmation/confirmation.component';
import { SchoolYearComponent } from './pages/school-year/school-year.component';
import { StudentsComponent } from './pages/students/students.component';
import { DropoutComponent } from './pages/dropout/dropout.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/dash/secretary/registration',
    pathMatch: 'full',
  },
  {
    path: 'registration',
    component: RegistrationComponent
  },
  {
    path: 'dropout',
    component: DropoutComponent
  },
   {
    path: 'students',
    component: StudentsComponent
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SecretaryRoutingModule { }
