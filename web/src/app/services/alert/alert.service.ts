import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class AlertService {

  constructor(private router: Router) { }

  AlertSucess(infor: string) {
    Swal.fire({
      icon: 'success',
      title: infor,
      showConfirmButton: false,
      timer: 2000
    })
  }

  AlertError(info: string) {
    Swal.fire({
      icon: 'error',
      title: info,
      showConfirmButton: true,
    })
  }

  AlertInfo(info: string) {
    Swal.fire({
      icon: 'info',
      title: info,
      confirmButtonText: "Continuar",
      showConfirmButton: true,
    })
  }

  AlertWarning(info: string) {
    Swal.fire({
      icon: 'warning',
      title: info,
      confirmButtonText: 'Ok'
    })
  }

  AlertLogout(info: string) {
    Swal.fire({
      icon: 'info',
      title: info,
      confirmButtonText: 'Ok'
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.clear();
        location.reload()
        this.router.navigate(['/auth/login']);
      }
    })
  }

  AlertProgress(title?:string, message? : string) {
    Swal.fire({
      title: title ?? 'Wainting one moment',
      html: message?? 'checking data',
      timerProgressBar: false,
      didOpen: () => {
        Swal.showLoading()
      },
      willClose: () => {
        clearInterval(200)
      }
    })
  }

}
