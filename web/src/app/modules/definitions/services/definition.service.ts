import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CompanyItem } from 'src/app/shared/models/company';
import { DepartamentItem } from 'src/app/shared/models/department';
import { PermissionItem } from 'src/app/shared/models/permission';
import { UserItem } from 'src/app/shared/models/users';
import { environmentSettings } from 'src/environments/environment.dev';

@Injectable({
  providedIn: 'root'
})
export class DefinitionService {
  apiUrl = environmentSettings.endpoint.url
  constructor(private http: HttpClient) { }

  createCompany(data: CompanyItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/company/store`, data, { headers: headers })
  }

  getAllCompany(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company`, {headers: headers})
  }

  getCompany(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company/show/${id}`, {headers: headers})
  }

  deleteCompany(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/settings/company/destroy/${id}`, {headers: headers})
  }

  updateCompany(data: CompanyItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/settings/company/update/${data.id}`, data, { headers: headers })
  }

  createDepartment(data: DepartamentItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/company/departament/store`, data, { headers: headers })
  }

  getAllDepartment(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company/departament`, {headers: headers})
  }

  getDepartment(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company/departament/show/${id}`, {headers: headers})
  }

  deleteDepartment(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/settings/company/departament/destroy/${id}`, {headers: headers})
  }

  updateDepartment(data: DepartamentItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/settings/company/departament/update/${data.id}`, data, { headers: headers })
  }


  createBank(data: DepartamentItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/company/bank/store`, data, { headers: headers })
  }

  getAllBanks(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company/bank`, {headers: headers})
  }

  getBank(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company/bank/show/${id}`, {headers: headers})
  }

  deleteBank(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/settings/company/bank/destroy/${id}`, {headers: headers})
  }

  updateBank(data: DepartamentItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/settings/company/bank/update/${data.id}`, data, { headers: headers })
  }

  createRole(data: DepartamentItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/manegment/role/store`, data, { headers: headers })
  }

  getAllRoles(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/role`, {headers: headers})
  }

  getRole(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/role/show/${id}`, {headers: headers})
  }

  deleteRole(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/settings/manegment/role/destroy/${id}`, {headers: headers})
  }

  updateRole(data: DepartamentItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/settings/manegment/role/update/${data.id}`, data, { headers: headers })
  }


  createPermission(data: PermissionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/manegment/permission/store`, data, { headers: headers })
  }

  getAllPermissions(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/permission`, {headers: headers})
  }

  getPermission(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/permission/show/${id}`, {headers: headers})
  }

  deletePermission(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/settings/manegment/permission/destroy/${id}`, {headers: headers})
  }

  updatePermission(data: PermissionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/settings/manegment/permission/update/${data.id}`, data, { headers: headers })
  }




  createUser(data: UserItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/manegment/user/store`, data, { headers: headers })
  }

  getAllUsers(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/user`, {headers: headers})
  }

  getUser(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/manegment/user/show/${id}`, {headers: headers})
  }

  deleteUser(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/settings/manegment/user/destroy/${id}`, {headers: headers})
  }

  updateUser(data: UserItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/settings/manegment/user/update/${data.id}`, data, { headers: headers })
  }

  
  updateUserPass(data: any) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/settings/company/users_pass/${data.id}`, data, { headers : headers})
  }

  updateUserPassReset(data: any) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.get(`${this.apiUrl}/settings/company/users_pass_default/${data.id}`, { headers : headers})
  }


  // report
  Report(data: any, token : string) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/settings/reports/${data}`, { headers: headers })
  }
}
