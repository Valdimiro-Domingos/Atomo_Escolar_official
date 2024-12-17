import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { AuthService } from '../auth/auth.service';
import { AlertService } from '../alert/alert.service';
import { Router } from '@angular/router';
import { ErrorsService } from '../errors.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(private authService: AuthService, private errors: ErrorsService, private alerta : AlertService, private router : Router) { }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<any> {
    return next.handle(req).pipe(
      catchError((error: HttpErrorResponse) => {
          console.clear()
          if (error.status === 401) {
            if(error.error.message == "Unauthenticated" || error.error.message == "Unauthenticated."){
                this.alerta.AlertProgress("Sessão expirada", "Estamos redirecionar ao painel de acesso!")
                 console.clear()
                
                var deleteItem = setTimeout((e : any) => {
                    localStorage.clear();
                    clearTimeout(deleteItem)
                    this.alerta.AlertInfo("Tens de iniciar sessão")
                    this.router.navigate(['auth/login']);
                }, 2000)
            }
             console.clear()
          }
          
        this.errors.setError(error.message)
        return throwError(error);
      })
    );
  }
}
