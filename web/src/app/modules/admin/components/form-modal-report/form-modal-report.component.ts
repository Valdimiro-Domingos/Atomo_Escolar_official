import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component, EventEmitter, Inject, Injectable, Input, OnInit, Output, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import * as feather from 'feather-icons';
import { DefinitionService } from 'src/app/modules/definitions/services/definition.service';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { environmentSettings } from 'src/environments/environment.dev';


@Injectable()
@Component({
  selector: 'app-form-modal-report',
  templateUrl: './form-modal-report.component.html',
  styleUrls: ['./form-modal-report.component.css']
})
export class FormModalReportComponent implements OnInit {

  apiUrl = environmentSettings.endpoint.url
  @Input() lockClose = false;
  @Input() title: string = 'Relatório';
  @Input() full : boolean = false;
  @Input() isEdit: boolean = false;
  @Input() custom : boolean = false;
  @Input() route: string | null = null;
  @Input() urlBase: string = ''
  @Input() width : number = 60;
  @Input() routes: any = {}
  @Output() onClick = new EventEmitter<any>();
  dataLocal: any = new Date()

  reportForm! : FormGroup;

  
  constructor(private build : FormBuilder,private router : Router, private http:HttpClient, private setting : DefinitionService,  private alertService: AlertService, private authService: AuthService){
    this.reportForm = this.build.group({
      type: ['matricula', Validators.compose([Validators.required])],
      date_of_issure: [this.formatDatetimeLocal(new Date()), Validators.required],
      date_due:  [this.formatDatetimeLocal(new Date()), Validators.required]
    });
  }
  
  
  
  
  showModal: boolean = false
  ngOnInit() {
    feather.replace()
    
    if(this.full){
      this.showModal = true
    }

    // this.reportForm.controls['date_due'].setValue(this.formatDatetimeLocal(new Date()));
    // this.reportForm.controls['date_of_issure'].setValue(this.formatDatetimeLocal(new Date()))

  }
  
  click(){
    if(!this.lockClose){
      this.onClick.emit()
    }
  }

  
  formatDatetimeLocal(date: Date): string {
    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const day = ('0' + date.getDate()).slice(-2);

    return `${year}-${month}-${day}`;
  }
  
  openModal(){
    this.showModal = true
  }
  handleShow(): void {
    this.showModal = true;
  }
  
  handleClose(): void {
    this.showModal = false;
    
    if(!this.lockClose){
      this.click();
    }
    this.onClick.emit()
  }
  
  handleBackdropClick(event: MouseEvent): void {
    if (event.target === event.currentTarget) {
      if(!this.lockClose){
        this.handleClose();
      }
    }
  }

  
  printPDF(){
    const token : any = this.authService.getUser();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token?.token}`
    });
  
    var dateOF : any = ( this.reportForm.value?.date_of_issure) ?? this.formatDatetimeLocal(new Date());
    var dateDue : any = this.reportForm.value?.date_due ?? this.formatDatetimeLocal(new Date());
      const url = `${environmentSettings.endpoint.url}/settings/reports/${this.reportForm.value?.type}/${token?.user?.company_id}/${dateOF}/${dateDue}`;
      window.open(url);

    // return  `https://vmi1559123.contaboserver.net/escolar/public/api/settings/reports/${this.reportForm.value?.type}/${token?.user?.company_id}/${dateOF}/${dateDue}`;
  }
  
}
