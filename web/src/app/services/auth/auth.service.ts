import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { environmentSettings } from 'src/environments/environment.dev';
import { Title } from '@angular/platform-browser';



@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private apiUrl = environmentSettings.endpoint.url;
  constructor(private http: HttpClient, private router: Router, private titleService: Title) {}

  login(email: string, password: string) {
    return this.http.post(`${this.apiUrl}/auth/login`, {
      email: email,
      password: password,
    });
  }
  
  statusSystem(token : string){
   const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.get(`${this.apiUrl}/auth/system`, { headers: headers });
  }
  register(data: {
    name: string;
    email: string;
    designation: string;
    nif: string;
  }) {
    return this.http.post(`${this.apiUrl}/auth/company/create`, data);
  }

  logOut(token: string) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
    return this.http.post(
      `${this.apiUrl}/auth/logout`,
      {},
      { headers: headers }
    );
  }

  getUser() {
    const userString = localStorage.getItem('user');
    
    
    try {
      if (userString) {
        this.titleService.setTitle("Sistema Escolar | "+JSON.parse(userString).user?.name);
        return JSON.parse(userString);
      } else {
        return null;
      }
    } catch (error) {
      return null;
    }
  }

  validateToken() {
    return this.http.post<any>(`${this.apiUrl}/auth/init`, {
      token: (localStorage.getItem('user') ? this.getUser().token : ''),
    });
  }
}
