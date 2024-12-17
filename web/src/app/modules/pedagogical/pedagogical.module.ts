import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PedagogicalRoutingModule } from './pedagogical-routing.module';
import { SharedModule } from '../shared/shared.module';
import { MaterialModule } from '../material/material.module';
import { AdminModule } from '../admin/admin.module';
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
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { GradeComponent } from './pages/schedule/pages/grade/grade.component';
import { MiniScheduleComponent } from './pages/schedule/pages/mini-schedule/mini-schedule.component';
import { PdfViewerModule } from 'ng2-pdf-viewer';
import { ReportCardComponent } from './pages/report-card/report-card.component';



@NgModule({
  declarations: [
    ScheduleComponent,
    TimeComponent,
    ClassificationComponent,
    MiniScheduleComponent,
    DeclarationComponent,
    CertificateComponent,
    PeriodComponent,
    ClassComponent,
    DisciplineComponent,
    CourseComponent,
    ClassRoomsComponent,
    TrimesterComponent,
    TurmaComponent,
    ReportCardComponent,
    GradeComponent
  ],
  imports: [
    CommonModule,
    PedagogicalRoutingModule,
    SharedModule,
    MaterialModule,
    AdminModule,
    FormsModule,
    ReactiveFormsModule,
    PdfViewerModule
  ]
})
export class PedagogicalModule { }
