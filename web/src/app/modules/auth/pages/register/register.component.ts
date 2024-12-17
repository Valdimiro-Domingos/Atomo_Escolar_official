import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  formulario!: FormGroup
  constructor(private formBuilder: FormBuilder, private router : Router, private authService: AuthService, private alertService: AlertService) { }
  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      nif: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      name: ['', [Validators.required]],
      telephone: ['', [Validators.required, Validators.minLength(9)]]
    });
  }

  onSubmit(){
    if(this.formulario.valid){
      const formData = this.formulario.value
      this.authService.register(formData).subscribe(data => {
        if(data){
          this.alertService.AlertSucess("Empresa registrada com sucesso!")
          var deleteInterval: any = setInterval(() => {
            this.router.navigate(['/auth/login']);
            clearInterval(deleteInterval);
          }, 1000);
        }
      }, err => {
        this.alertService.AlertError(err?.error?.message)
      })
    }
  }

}
