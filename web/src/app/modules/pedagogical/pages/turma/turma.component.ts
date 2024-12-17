import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { PedagogicalService } from '../../services/pedagogical.service';
import { ITurma, ITurmaView } from 'src/app/shared/models/turma';
import { AlertService } from 'src/app/services/alert/alert.service';
import { IClass } from 'src/app/shared/models/class';

@Component({
  selector: 'app-turma',
  templateUrl: './turma.component.html',
  styleUrls: ['./turma.component.css'],
})
export class TurmaComponent implements OnInit {
  filter = [
    {
      label: 'Nome',
      select: ['pedro', 'lucas', 'fabio'],
    },
  ];

  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;
  isLoading: boolean = false;
  datas: ITurmaView = {
    turmas: [],
    classes: [],
    company_id : '',
    school_year: []
  };
  
      base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Extra',  url: '/dash/pedagogical-area/' },{ name: 'Turma',  url: '/dash/pedagogical-area/turma' }]


  formulario!: FormGroup;
  view_form!: FormGroup;

  constructor(
    private clientApi: PedagogicalService,
    private alertService: AlertService,
    private authService: AuthService,
    private formBuilder: FormBuilder
  ) {}

  ngOnInit(): void {
    this.isLoading = true;
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: [''],
    });

    this.view_form = this.formBuilder.group({
      turma_id: ['', Validators.required],
      company_id: [''],
      classe_id: ['', [Validators.required]],
      school_year_id: ['', Validators.required],
    });

    this.getDatas();
  }

  getDatas() {
    const token = this.authService.getUser().token;
    this.clientApi.getAllTurmaView(token).subscribe(
      (data) => {
        const response = data as ITurmaView;
        this.datas.turmas = response.turmas;
        this.datas.classes = response.classes;
        this.datas.school_year = response.school_year,
        this.datas.company_id = response.company_id
        this.view_form.controls['company_id'].setValue(response.company_id);
        this.isLoading = false;
      },
      (err) => {
        this.isLoading = false;
      }
    );
  }

  handleRemove(id: number) {
    const token = this.authService.getUser().token;
    this.clientApi.deleteTurma(id, token).subscribe(
      (data) => {
        if (data) {
          this.alertService.AlertSucess('Turma deletada com sucesso!');
          this.getDatas();
        }
      },
      (err) => {
        alert(err.error.message);
      }
    );
  }

  patchValues(item: any) {
    this.formulario.patchValue({
      designation: item.designation,
      description: item.description,
    });
  }

  onUpdate(item: any) {
    if (this.formulario.valid) {
      const formData = this.formulario.value;
      const token = this.authService.getUser().token;
      this.clientApi
        .updateTurma({
          description: formData.description,
          designation: String(formData?.designation),
          token: token,
          id: item.id,
        })
        .subscribe(
          (data) => {
            this.alertService.AlertSucess('Turma actualizada com sucesso!');
            this.formulario.reset();
            this.formModalComponent.handleClose();
            this.getDatas();
          },
          (err) => {
            this.alertService.AlertError(err.error.message);
          }
        );
    } else {
      this.alertService.AlertSucess('Existe campos invalido!');
    }
  }

  onSave() {
    if (this.formulario.valid) {
      const formData = this.formulario.value;
      const token = this.authService.getUser().token;
      this.clientApi
        .createTurma({
          description: formData.description,
          designation: formData?.designation,
          token: token,
        })
        .subscribe(
          (data) => {
            this.alertService.AlertSucess('Turma cadastrada com sucesso!');
            this.formulario.reset();
            this.formModalComponent.handleClose();
            this.getDatas();
          },
          (err) => {
            this.alertService.AlertError(err.error.message);
          }
        );
    }
  }

  // modal dialog
  view() : any {
    const token = this.authService.getUser().token;
      if (this.view_form.valid) {
      this.clientApi.RelatorioTurma({ ...this.view_form.value, token : token});
    } else {
      this.alertService.AlertInfo('Por favor, preenche os campos');
    }
  }
}
