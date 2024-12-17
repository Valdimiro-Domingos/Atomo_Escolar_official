import { ILogin } from './../../../../shared/models/login';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  sessao : boolean = false;
  formulario!: FormGroup

  constructor(private formBuilder: FormBuilder, private router : Router, private authService: AuthService, private alertService: AlertService) { 
    if(authService.getUser()?.token && authService.getUser()?.user.name){
      this.sessao = true
           this.router.navigate(['/dash/home']);
      }else{
        this.sessao = false;
        localStorage.clear()
      }
  }

  ngOnInit(): void {
    this.formulario = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]]
    });
  }

  onSubmit() {
      if (this.formulario.valid) {
        this.alertService.AlertProgress("Solicitando", 'Iniciar sessão');

        const formData = this.formulario.value
        this.authService.login(formData.email, formData.password).subscribe((data) => {
          const response = data as ILogin
          if(response?.token && response?.token !== null){
            localStorage.clear()
            localStorage.setItem("user", JSON.stringify(response));
            this.alertService.AlertSucess("Bem vindo!")
            setTimeout(() => {
              this.router.navigate(['/dash/home']);
            }, 500);
            this.formulario.reset()
          }
        }, err => {
          if(err?.error?.error == "pass")
          {
            this.alertService.AlertError(err?.error?.message)
          }else{
            this.alertService.AlertInfo(err?.error?.message)
          }
        })
      } else {
      
        if(this.formulario.value.password.length > 0 && this.formulario.value.password.length < 6){
          this.alertService.AlertInfo("Total de careteres no campo senha invalido!")
        }else{
          this.alertService.AlertInfo("Por favor preencha todos os campos!")
        }
      }
  }
}
