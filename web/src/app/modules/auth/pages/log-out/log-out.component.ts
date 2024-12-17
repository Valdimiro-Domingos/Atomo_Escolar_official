import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';

@Component({
  selector: 'app-log-out',
  templateUrl: './log-out.component.html',
  styleUrls: ['./log-out.component.css']
})
export class LogOutComponent implements OnInit {
  constructor(private authService: AuthService, private router: Router, private alertservice: AlertService) { }
  ngOnInit(): void {
    const token = this.authService.getUser()?.token
    this.authService.logOut(token).subscribe(data => {
      if(data){
        localStorage.clear()
        this.router.navigate(['/auth/login'])
        localStorage.clear()
      }
    },
    err => {
      this.router.navigate(['/auth/login'])
      localStorage.clear()
    }
    )
  }

}
