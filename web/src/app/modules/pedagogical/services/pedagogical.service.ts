import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Certification, getCertification } from 'src/app/shared/models/certification';
import { ClassItem, IClass } from 'src/app/shared/models/class';
import { ClassRoomItem } from 'src/app/shared/models/classRoom';
import { CourseItem } from 'src/app/shared/models/course';
import { DisciplineItem } from 'src/app/shared/models/discipline';
import { IPeriod } from 'src/app/shared/models/period';
import { ScheduleItem } from 'src/app/shared/models/schedule';
import { ScheduleMiniItem, ScheduleMiniUpdate } from 'src/app/shared/models/scheduleMini';
import { TrimesterItem } from 'src/app/shared/models/trimester';
import { ITurmaView, TurmaItem } from 'src/app/shared/models/turma';
import { environmentSettings } from 'src/environments/environment.dev';

@Injectable({
  providedIn: 'root',
})
export class PedagogicalService {
  apiUrl = environmentSettings.endpoint.url;
  constructor(private http: HttpClient) {}

  createClass(data: ClassItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/class/store`, data, {
      headers: headers,
    });
  }

  getAllClass(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/class`, {
      headers: headers,
    });
  }

  getClass(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/class/show/${id}`, {
      headers: headers,
    });
  }

  deleteClass(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(`${this.apiUrl}/pedagogical/class/destroy/${id}`, {
      headers: headers,
    });
  }

  updateClass(data: ClassItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/class/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createPeriod(data: IPeriod) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/period/store`, data, {
      headers: headers,
    });
  }

  getAllPeriod(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/period`, {
      headers: headers,
    });
  }

  getPeriod(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/period/show/${id}`, {
      headers: headers,
    });
  }

  deletePeriod(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(`${this.apiUrl}/pedagogical/period/destroy/${id}`, {
      headers: headers,
    });
  }

  updatePeriod(data: IPeriod) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/period/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createDiscipline(data: DisciplineItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/discipline/store`, data, {
      headers: headers,
    });
  }

  getAllDiscipline(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/discipline`, {
      headers: headers,
    });
  }

  getDiscipline(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/discipline/show/${id}`, {
      headers: headers,
    });
  }

  deleteDiscipline(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(
      `${this.apiUrl}/pedagogical/discipline/destroy/${id}`,
      { headers: headers }
    );
  }

  updateDiscipline(data: DisciplineItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/discipline/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createCourse(data: CourseItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/course/store`, data, {
      headers: headers,
    });
  }

  getAllCourse(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/course`, {
      headers: headers,
    });
  }

  getCourse(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/course/show/${id}`, {
      headers: headers,
    });
  }

  deleteCourse(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(`${this.apiUrl}/pedagogical/course/destroy/${id}`, {
      headers: headers,
    });
  }

  updateCourse(data: CourseItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/course/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createTrimester(data: TrimesterItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/trimestre/store`, data, {
      headers: headers,
    });
  }

  getAllTrimester(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/trimestre`, {
      headers: headers,
    });
  }

  getTrimester(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/trimestre/show/${id}`, {
      headers: headers,
    });
  }

  deleteTrimester(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(
      `${this.apiUrl}/pedagogical/trimestre/destroy/${id}`,
      { headers: headers }
    );
  }

  updateTrimester(data: TrimesterItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/trimestre/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createClassRoom(data: ClassRoomItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/classroom/store`, data, {
      headers: headers,
    });
  }

  getAllClassRoom(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/classroom`, {
      headers: headers,
    });
  }

  getClassRoom(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/classroom/show/${id}`, {
      headers: headers,
    });
  }

  deleteClassRoom(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(
      `${this.apiUrl}/pedagogical/classroom/destroy/${id}`,
      { headers: headers }
    );
  }

  updateClassRoom(data: ClassRoomItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/classroom/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createTurma(data: TurmaItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/turma/store`, data, {
      headers: headers,
    });
  }

  getAllTurma(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/turma`, {
      headers: headers,
    });
  }

  getAllTurmaView(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/turma/view`, {
      headers: headers,
    });
  }

  getTurma(id: string, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/turma/show/${id}`, {
      headers: headers,
    });
  }

  deleteTurma(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(`${this.apiUrl}/pedagogical/turma/destroy/${id}`, {
      headers: headers,
    });
  }

  updateTurma(data: TurmaItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/turma/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  RelatorioTurma(data: any) {
     let url = `${this.apiUrl}/pedagogical/turma/relatorio?turma_id=${data.turma_id}&company_id=${data.company_id}&classe_id=${data.classe_id}&school_year_id=${data.school_year_id}`;
    window.open(url, '_blank');
  }
  
  Relatorio(data: any) {
     let url = `${this.apiUrl}/pedagogical/students/relatorio?company=${data.company_id}&document=${data.documento}&period=${data.periodo}&turma=${data.turma}&course=${data.curso}&class_room=${data.sala}&classe=${data.classe}&school_year=${data.lectivo}&mes=${data.mes}`;
    window.open(url, '_blank');
  }

  createSchedule(data: ScheduleItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(`${this.apiUrl}/pedagogical/schedule/store`, data, {
      headers: headers,
    });
  }

  getAllSchedule(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/schedule`, {
      headers: headers,
    });
  }
  getAllScheduleView(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/schedule/view`, {
      headers: headers,
    });
  }

  getSchedule(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/schedule/show/${id}`, {
      headers: headers,
    });
  }

  deleteSchedule(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(
      `${this.apiUrl}/pedagogical/schedule/destroy/${id}`,
      { headers: headers }
    );
  }

  updateSchedule(data: ScheduleItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/schedule/update/${data.id}`,
      data,
      { headers: headers }
    );
  }


  getAllScheduleMini(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(
      `${this.apiUrl}/pedagogical/schedule/mini/schedule/${id}`,
      { headers: headers }
    );
  }
  
  getAllScheduleMiniView(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(
      `${this.apiUrl}/pedagogical/schedule/mini/view/{id}`,
      { headers: headers }
    );
  }


  getAllProfeessor(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/user/professor`, {
      headers: headers,
    });
  }
  
  
  createScheduleMini(data: ScheduleMiniItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(
      `${this.apiUrl}/pedagogical/schedule/mini/store`,
      data,
      { headers: headers }
    );
  }
  uplodeSchedule(id: any, form : any , token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.post(
      `${this.apiUrl}/pedagogical/schedule/uplode/${id}`,
      form,
      { headers: headers }
    );
  }
  uplodeScheduleMini(id: any, form : any , token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.post(
      `${this.apiUrl}/pedagogical/schedule/mini/uplode/${id}`,
      form,
      { headers: headers }
    );
  }
  

  getScheduleMini(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(
      `${this.apiUrl}/pedagogical/schedule/mini/show/${id}`,
      { headers: headers }
    );
  }

  getNotesScheduleMiniId(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(
      `${this.apiUrl}/pedagogical/schedule/mini/grade/mini_schedule/${id}`,
      { headers: headers }
    );
  }

  deleteScheduleMini(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(
      `${this.apiUrl}/pedagogical/schedule/mini/destroy/${id}`,
      { headers: headers }
    );
  }
  
  

  updateScheduleMini(data: ScheduleMiniUpdate) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/schedule/mini/update/${data.id}`,
      data,
      { headers: headers }
    );
  }

  createGrade(data: any) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.post(
      `${this.apiUrl}/pedagogical/schedule/mini/grade/store`,
      data,
      { headers: headers }
    );
  }

  getAllGrade(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/pedagogical/schedule/mini/grade`, {
      headers: headers,
    });
  }

  getGrade(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(
      `${this.apiUrl}/pedagogical/schedule/mini/grade/show/${id}`,
      { headers: headers }
    );
  }

  deleteGrade(id: number, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.delete(
      `${this.apiUrl}/pedagogical/schedule/mini/grade/destroy/${id}`,
      { headers: headers }
    );
  }

  updateGrade(data: ScheduleMiniItem) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${data.token}`,
    });
    return this.http.put(
      `${this.apiUrl}/pedagogical/schedule/mini/grade/update/${data.id}`,
      data,
      { headers: headers }
    );
  }
  
  
  
  
  // CERTIFICATION
    getCertification(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get<getCertification[]>(`${this.apiUrl}/pedagogical/certification`, { headers: headers }
    );
  }
  

  postUplodeCertification(id: number, form : any, token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.post(
      `${this.apiUrl}/pedagogical/certification/uplode/${id}`, form,
      { headers: headers }
    );
  }
}
