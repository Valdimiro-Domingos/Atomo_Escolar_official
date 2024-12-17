import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ScheduleComponent } from './pages/schedule/schedule.component';
import { TimeComponent } from './pages/time/time.component';
import { ClassificationComponent } from './pages/classification/classification.component';
import { DeclarationComponent } from './pages/declaration/declaration.component';
import { CertificateComponent } from './pages/certificate/certificate.component';
import { PeriodComponent } from './pages/period/period.component';
import { ClassComponent } from './pages/class/class.component';
import { DisciplineComponent } from './pages/discipline/discipline.component';
import { CourseComponent } from './pages/course/course.component';
import { ClassRoomsComponent } from './pages/class-rooms/class-rooms.component';
import { TrimesterComponent } from './pages/trimester/trimester.component';
import { TurmaComponent } from './pages/turma/turma.component';
import { SchoolYearComponent } from '../secretary/pages/school-year/school-year.component';
import { GradeComponent } from './pages/schedule/pages/grade/grade.component';
import { MiniScheduleComponent } from './pages/schedule/pages/mini-schedule/mini-schedule.component';
import { ReportCardComponent } from './pages/report-card/report-card.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/dash/pedagogical-area/schedule',
    pathMatch: 'full'
  },
  {
    path: '',
    component: PeriodComponent
  },
  {
    path: 'schedule',
    component: ScheduleComponent
  },
  {
    path: 'mini-schedule',
    component: MiniScheduleComponent
  },
  {
    path: 'mini-schedule/schedule/:id',
    component: MiniScheduleComponent
  },
  {
    path: 'mini-schedule/schedule/grade/:id',
    component: GradeComponent
  },
  {
    path: 'declaration',
    component: DeclarationComponent
  },
  {
    path: 'report-card',
    component: ReportCardComponent
  },
  {
    path: 'certification',
    component: CertificateComponent
  },
  {
    path: 'time',
    component: TimeComponent
  },
  {
    path: 'classification',
    component: ClassificationComponent
  },
  {
    path: 'period',
    component: PeriodComponent
  },
  {
    path: 'class',
    component: ClassComponent
  },
  {
    path: 'discipline',
    component: DisciplineComponent
  },
  {
    path: 'course',
    component: CourseComponent
  },
  {
    path: 'classRooms',
    component: ClassRoomsComponent
  },
  {
    path: 'trimester',
    component: TrimesterComponent
  },
  {
    path: 'turma',
    component: TurmaComponent
  },
  {
    path: 'schoolYear',
    component: SchoolYearComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PedagogicalRoutingModule { }
