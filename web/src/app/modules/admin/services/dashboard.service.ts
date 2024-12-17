import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environmentSettings } from 'src/environments/environment.dev';

@Injectable({
  providedIn: 'root'
})
export class DashboardService {
  apiUrl = environmentSettings.endpoint.url
  constructor(private http: HttpClient) { }
  getDatas(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/dashboard`, {headers: headers})
  }
}
