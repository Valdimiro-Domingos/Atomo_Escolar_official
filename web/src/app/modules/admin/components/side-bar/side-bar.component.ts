import { ChangeDetectorRef, Component, Input, OnInit, SimpleChanges, ViewChild, ViewChildren } from '@angular/core';
import { Router } from '@angular/router';
import * as feather from 'feather-icons';
import { NavBarHamburguerComponent } from '../header/components/nav-bar-hamburguer/nav-bar-hamburguer.component';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { FinanceService } from 'src/app/modules/finance/services/finance.service';

@Component({
  selector: 'app-side-bar',
  templateUrl: './side-bar.component.html',
  styleUrls: ['./side-bar.component.css']
})
export class SideBarComponent implements OnInit {
  @Input() isHeader: boolean = false
  option: string = '';
  urlBase: string = ''
  @ViewChild(NavBarHamburguerComponent)
  NavBarComponent!: NavBarHamburguerComponent;
  showModal: boolean = true;
  
  meses: string[] = [
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
  ];
  
  constructor(private route: Router,  private authService: AuthService, 
    private cdr: ChangeDetectorRef, private alertService: AlertService, private build : FormBuilder, private financeOpen: FinanceService) {
    
    
    
    this.formBribes = this.build.group({
      // dateOf : ['', Validators.required],
      // dateTo : ['', Validators.required],
      document: ['', Validators.required],
      companyId: ['']
    })
  }

  ngOnInit(): void {
    feather.replace()
    // this.getLoad()
  }

  getLoad(){
    const url = this.route.url
    const newUrl = url.split('/').filter(i => i != '')
    this.option = newUrl[1]
    this.urlBase = newUrl[2]
  }

  ngAfterViewChecked(): void {
    this.getLoad()
    this.cdr.detectChanges();
  }



  setUrlBase(url: string){
    this.urlBase = url
  }

  setOption(opt: string){
    this.option = opt
  }

  modal : boolean = false;
  modal_card : boolean = false
  OnReporte(){
    this.modal = true;
  }
  
  
  
  modalStatus : boolean = false;
  modalCurrent : string = '';
  modalOpen(page : string){
    this.modalStatus = true;
    this.modalCurrent = page;
  }
  modalClose(){
    this.modalStatus = false;
  }
  
  
  // forms modal
  formBribes! : FormGroup;
  onBribes() : any {
    const token = this.authService.getUser().token;
    const company = this.authService.getUser().user?.company_id;
    
    this.formBribes.controls['companyId'].setValue(company);
    if(this.formBribes.valid){
      return this.financeOpen.bribesAll({...this.formBribes.value, token: token});
    }else{
      this.alertService.AlertInfo( 'Por favor, preencha todos os campos obrigatórios!' );
    }
  }
}
