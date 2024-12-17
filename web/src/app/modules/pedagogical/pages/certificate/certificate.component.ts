import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PedagogicalService } from '../../services/pedagogical.service';
import { Certification, ItemsCertification } from 'src/app/shared/models/certification';
import { AuthService } from 'src/app/services/auth/auth.service';
import { environmentSettings } from 'src/environments/environment.dev';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-certificate',
  templateUrl: './certificate.component.html',
  styleUrls: ['./certificate.component.css'],
})
export class CertificateComponent implements OnInit {


  base = [{ name: 'Area Pedagógica', url: '/dash/pedagogical-area' }, { name: 'Certificado', url: '/dash/pedagogical-area/certification' }]
  heading = ['Estudante', 'Classe', 'Sala', 'Turma', 'Curso',  'Ano Lectivo', 'Período', 'Opções']


  baseForm!: FormGroup;
  isLoading: boolean = true
  constructor(private build: FormBuilder, private alert: AlertService, private authService: AuthService, private certificationService: PedagogicalService) {
    this.baseForm = build.group({
      course : ['-1'],
      periodo : ['-1'],
      schoolYear : ['-1'],
      classRom : ['-1'],
      turma : ['-1'],
    });
  }



  selectedFile: File | any;
  filteredItems: Certification[] = [];
  listErollements: Certification[] = [];
  listErollementsItems: ItemsCertification | null = null;

  ngOnInit(): void {
    this.initLoad()
  }

  initLoad() {
    const token = this.authService.getUser().token
    this.certificationService.getCertification(token).subscribe({
      next: (res: any) => {
        this.isLoading = false;
        this.filteredItems = res.students;
        this.listErollements = res.students;
        this.listErollementsItems = res.items;
      },
      error: (err) => {
        this.isLoading = false;
      }
    })
  }

  printPDF(item: any) {
    return `${environmentSettings.endpoint.urlImage}/viewFile/${item.certification}`;
  }

  onFileSelected(event: any) {
    this.selectedFile = event.target.files[0];
  }


  uplodeFile(id: number) {
    if (this.selectedFile.name) {
      const token = this.authService.getUser().token;
      var form = new FormData();
      form.append('file', this.selectedFile);
      this.certificationService.postUplodeCertification(id, form, token).subscribe({
        next: (res: any) => {
          this.selectedFile = null;
          this.filteredItems = res.students;
          this.listErollements = res.students;
          this.listErollementsItems = res.items;
          this.alert.AlertSucess("Certificado enviado com sucesso!")
        },
        error: (err) => {
          this.alert.AlertError(err.error.message)
        }
      })
    } else {
      this.alert.AlertInfo("Carrega o arquivo")
    }
  }
  
  
  Seachr() {
    const formValues = this.baseForm.value;
    console.log(formValues);
    if (this.baseForm.valid) {
      this.filteredItems = this.filterItems(formValues);
    } else {
      this.alert.AlertInfo("Carrega o arquivo");
    }
  }

  filterItems(filters: any): Certification[] | [] {
    if (!this.listErollements) return [];

    return this.listErollements.filter(item => {
      return (
        (filters.course === '-1' || item.course === filters.course) &&
        (filters.periodo === '-1' || item.period === filters.periodo) &&
        (filters.schoolYear === '-1' || item.school_year === filters.schoolYear) &&
        (filters.classRom === '-1' || item.class_room === filters.classRom) &&
        (filters.turma === '-1' || item.turma === filters.turma)
      );
    });
  }
  
  reset(){
    this.baseForm.patchValue({
      course: ['-1'],
      periodo: ['-1'],
      schoolYear: ['-1'],
      classRom: ['-1'],
      turma: ['-1'],
    });
    this.filteredItems = this.listErollements
  }
}
