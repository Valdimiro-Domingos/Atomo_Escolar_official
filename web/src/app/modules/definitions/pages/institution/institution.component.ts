import { Component, OnInit, ViewChild } from '@angular/core';
import { DefinitionService } from '../../services/definition.service';
import { ICompany } from 'src/app/shared/models/company';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from 'src/app/services/auth/auth.service';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AlertService } from 'src/app/services/alert/alert.service';
import { environmentSettings } from 'src/environments/environment.dev';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-institution',
  templateUrl: './institution.component.html',
  styleUrls: ['./institution.component.css']
})
export class InstitutionComponent implements OnInit {
  apiUrl = environmentSettings.endpoint.url
  isLoading: boolean = true
  selectedFile!: File;



  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;
    base = [{ name: 'Definições',  url: '/definitions' },  { name: 'Instituição',  url: '/dash/definitions/company' }]

  Heading = [
    "DESIGNAÇÃO",
    "NIF",
    "EMAIL",
    // "DIRECTOR(a) GERAL",
    // "DIRECTOR(a) PEDAGÓGICO",
    // "DIRECTOR(a) PROVINCIAL",
    // "DIRECTOR(a) MUNICIPAL",
    // "WEB-SITE",
    // "ENDEREÇO",
    // "CONTACTO",
    // "CAPITAL SOCIAL",
    // "DESCRIÇÃO",
    // "DATA DE FUNDAÇÃO",
    "NOME REPRESENTATIVO",
    // "IDENTIFICAÇÃO",
    "Opções"
  ];

  datas: ICompany = {
    companies: null
  }


  formulario!: FormGroup
  formFile!: FormGroup

  constructor(private http : HttpClient, private clientApi: DefinitionService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { 

    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: [''],
      nif:  [''],
      foundation_date:  [''],
      share_capital:  [''],
      email:  [''],
      contact:  [''],
      representative_name:  [''],
      representative_identification:  [''],
      country:  [''],
      city:   [''],
      address:  [''],
      whatsapp:  [''],
      facebook:  [''],
      web_site:  [''],
      general_manager: [''],
      logo: [''],
      pedagogical_manager:  [''],
      provincial_manager:  [''],
      municipal_manager:  ['',]
    });

    
    this.formFile = this.formBuilder.group({
      logo: ['', Validators.compose([Validators.required])]
    }); 

  }

  ngOnInit(): void {
    this.getDatas()
  }

  getDatas(){
    this.isLoading = true;
    const token = this.authService.getUser().token
    this.clientApi.getAllCompany(token).subscribe(data => {
      const response = data as ICompany
      this.datas.companies = response.companies
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }


  patchValues(item: any) {
    this.formulario.patchValue({
      ...item
    });
  }


  onFileChange(event: any) {
    const file: File = event.target.files[0]; 
    const uplode : any = new FormData();
    uplode.append('logo', file);
    this.formulario.controls['logo'].setValue(uplode);
  }


  onUpdate(item: any) {
    if (this.formulario.valid) {
      const formData = this.formulario.value;
     
      const token = this.authService.getUser().token;
      this.clientApi.updateCompany({...formData, token: token, id: item.id}).subscribe(
        data => {
          if (data) {
            this.alertService.AlertSucess("Dados atualizados com sucesso!");
            this.formulario.reset();
            this.formModalComponent.handleClose();
            this.getDatas();
          }
        },
        err => {
          this.alertService.AlertError(err.error.message);
        }
      );
    }
  }


  onFile(value : any){
    const uplode : any = new FormData();
    uplode.append('logo', value);
    this.formulario.controls['logo'].setValue(uplode);
  }

  
  fechar(){
    this.isFormLogo = false;
  }

  onFileSelected(event: any): void {
    this.selectedFile = event.target.files[0];
  }


  isFormLogo : boolean = false;

  onUpload(id: any): void {
    this.alertService.AlertProgress("", "Carregando o logotipo" );
    const formData: FormData = new FormData();
    formData.append('logo', this.selectedFile);
  
    const token: any = this.authService.getUser();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token?.token}`
    });
  
    this.http.post(`${this.apiUrl}/settings/company/logo/${id}`, formData, { headers: headers }).subscribe({
      next : (response : any) => {
        this.alertService.AlertSucess(response?.message)
        this.isFormLogo = false;
        this.formFile.reset();
        location.reload()
      },
      error : (error : any)  => {
        this.alertService.AlertError(error.error.message)
      }
    });
  }
}
