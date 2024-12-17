import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ClassItem } from 'src/app/shared/models/class';
import { RegistrationItem } from 'src/app/shared/models/registration';
import { SchoolYearItem } from 'src/app/shared/models/schoolYear';
import { environmentSettings } from 'src/environments/environment.dev';

@Injectable({
  providedIn: 'root'
})
export class SecretaryService {
  apiUrl = environmentSettings.endpoint.url
  constructor(private http: HttpClient) { }


  createRegistration(data: RegistrationItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/secretary/enrollment/store`, data, { headers: headers })
  }

  getAllRegistration(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/enrollment`, {headers: headers})
  }
  
  getAllRegistrationDropoutView(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/enrollment/dropout`, {headers: headers})
  }
  getAllRegistrationView(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/enrollment/view`, {headers: headers})
  }

  getAllStudent(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/pedagogical/student/schedule/${id}`, {headers: headers})
  }

  getRegistration(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/enrollment/show/${id}`, {headers: headers})
  }

  deleteRegistration(id: number, token: string, params? : any){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/secretary/enrollment/destroy/${id}`,  {headers: headers, body: params})
  }

  updateRegistration(data: RegistrationItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/secretary/enrollment/update/${data.id}`, data, { headers: headers })
  }

  // exporta dados
    
  createExportacao(data: any) {

    let url = `${this.apiUrl}/secretary/documents/type?
    company=${data.company_id}
    &documento=${data.documento}
    &date_issure=${data.date_issure}
    &date_end=${data.date_end}`;
     window.open(url, '_blank');
  }




  createSchoolYear(data: SchoolYearItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/secretary/schoolyear/store`, data, { headers: headers })
  }

  getAllSchoolYear(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/schoolyear`, {headers: headers})
  }

  getSchoolYear(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/schoolyear/show/${id}`, {headers: headers})
  }

  deleteSchoolYear(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/secretary/schoolyear/destroy/${id}`, {headers: headers})
  }

  updateSchoolYear(data: SchoolYearItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/secretary/schoolyear/update/${data.id}`, data, { headers: headers })
  }


  createConfirmation(data: RegistrationItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/secretary/enrollment/confirmation`, data, { headers: headers })
  }

  getAllConfirmation(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/enrollment/registation`, {headers: headers})
  }




  getConfirmation(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/enrollment/registation/show/${id}`, {headers: headers})
  }


  getAllStudents(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/students`, {headers: headers})
  }
  
  getAllStudentsView(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/secretary/students/view`, {headers: headers})
  }


}
