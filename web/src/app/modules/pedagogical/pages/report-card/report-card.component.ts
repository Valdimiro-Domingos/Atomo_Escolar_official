import { CommonModule } from '@angular/common';
import { ChangeDetectionStrategy, Component, ViewChild, inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { FormFullComponent } from 'src/app/modules/admin/components/form-full/form-full.component';
import { SecretaryService } from 'src/app/modules/secretary/services/secretary.service';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { environmentSettings } from 'src/environments/environment.dev';
import * as feather from 'feather-icons';
import { empty } from 'rxjs';









@Component({
    selector: 'app-report-card',
    templateUrl: "report-card.component.html",
    styleUrls: ['./report-card.component.css'],
})
export class ReportCardComponent { 

  
  
  
      url = environmentSettings.endpoint.url;
      base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, 
       { name: 'Documentos',  url: '/dash/pedagogical-area/report-card' }, 
       { name: 'Boletim',  url: '/dash/pedagogical-area/report-card' }]
    
      
      isLoading: boolean = false;
    
    
      @ViewChild(FormFullComponent)
      private formModalComponent!: FormFullComponent;
    
    
      @ViewChild(HTMLAnchorElement) link!: HTMLAnchorElement;
    
      formulario!: FormGroup;
      formularioReconfirm!: FormGroup;
      formularioFormReconfirm!: FormGroup;
    
    
    
      items: any[] = [];
      studants: any = [];
      baseForm! : FormGroup;
      dataLocal: string = new Date().toLocaleDateString();
      
      constructor(
        private alertService: AlertService,
        private authService: AuthService,
        private build: FormBuilder
      ) {  
      
        
        this.baseForm = build.group({
          turma: [''],
          classe: [''],
          lectivo: [''],
          periodo : [''],
          sala : [''],
          curso : [''],
          trimestre: [''],
          enrollment_id: ['', Validators.required],
        });
      }
    
      ngOnInit(): void {
        feather.replace();
        this.isLoading = true;
        this.initStudent()
      }
    
    
    
        
      private studentService = inject(SecretaryService)
      // private authService = inject(AuthService)
      
      
    
      baseResponse : {
        students : [] | {
            [key : string]: any
        } | any,
        items : any;
        periodos : any;
       [key: string]: any;
      } = {
        students : [] ?? {},
        items : {},
        periodos: []
      }
      
      
      initStudent() : void{
          this.isLoading = false;
        const token = this.authService.getUser().token
        this.studentService.getAllStudentsView(token).subscribe(data => {
            this.baseResponse = data as any;
            if(data){
                this.studants = this.baseResponse.students
            }
        })
      }
      
     
      printPDF(item: any) {
        return `${this.url}/pdf/invoice_receipts_pdf/${item.id}`;
      }
    
    
    
    
    OpenModal() {
        this.baseForm.reset()
    }
    

    onBuscar() {
        const turmaId = this.baseForm.get('turma')?.value;
        const classeId = this.baseForm.get('classe')?.value;
        const periodoId = this.baseForm.get('periodo')?.value;
        const anoLectivoId = this.baseForm.get('lectivo')?.value;
        const classRoomId = this.baseForm.get('sala')?.value;
        const cursoId = this.baseForm.get('curso')?.value;
        // efetuar busca
        
        if(this.baseForm.invalid){
            this.alertService.AlertInfo("Seleciona estudante e trimestre")
        }
        
        if((this.baseForm.get('enrollment_id')?.value)){
            if((this.baseForm.get('trimestre')?.value  == null)){
                this.alertService.AlertInfo("Seleciona o Trimestre")
            }else{
                const selectElement : any = document.querySelector('#enrollment_id');
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const estudante = selectedOption.text
                this.alertService.AlertProgress('Estudante '+estudante, 'Emitir Boletim')
            }
        }else{
            // Filtrar os estudantes com base nos valores selecionados
            this.studants = this.baseResponse.students.filter((estudante: any) => {
            // console.log(this.studants)
                let matchesTurma = !turmaId || estudante.enrollment.turma.id == turmaId;
                let matchesClasse = !classeId || estudante.enrollment.classe.id == classeId;
                let matchesPeriodo = !periodoId || estudante.enrollment.period.id == periodoId;
                let matchesAnoLectivo = !anoLectivoId || estudante.enrollment.school_year.id == anoLectivoId;
                let matchesClassRoom = !classRoomId || estudante.enrollment.class_room.id == classRoomId;
                let matchesCurso = !cursoId || estudante.enrollment.classe.id == cursoId;
          
                return matchesTurma && matchesClasse && matchesPeriodo && matchesAnoLectivo && matchesClassRoom && matchesCurso;
            });
        }
    }
        
    // limpar estudantes do filtro
    OnChange(){
        this.baseForm.get('enrollment_id')?.setValue('')
    }


  onClose() {
    this.formModalComponent.handleClose();
    this.baseForm.reset()
  }
}

interface IStudent{
    id: number,
    name: string,
    identity: string,
    gender: string
    photo: string
}
