import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IArticle } from 'src/app/shared/models/article';
import { FinanceService } from '../../services/finance.service';
import { ICategory } from 'src/app/shared/models/category';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.css']
})
export class CategoryComponent implements OnInit {
  filter =  [
    {
      label: 'Nome',
      select: [
        'pedro',
        'lucas',
        'fabio'
      ]
    }
  ]
  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;
   base = [{ name: 'Finanças',  url: '/dash/finance' }, { name: 'Categoria',  url: '/dash/finance/category' }]

  datas: ICategory = {
    article_categories: []
  }
  formulario!: FormGroup
  isLoading: boolean = false


  constructor(private clientApi: FinanceService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.isLoading = true
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: [''],
      unique: [0]
    });
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllCategory(token).subscribe(data => {
      const response = data as ICategory
      this.datas.article_categories = response.article_categories
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteCategory(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Categoria deletada com sucesso!")
        this.getDatas()
      }
    },
      err => {
        alert(err.error.message)
      }
    )
  }

  patchValues(item: any) {
    this.formulario.patchValue({
      designation: item.designation,
      description: item.description
    });
  }


  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateCategory({description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Categoria actualizada com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }

  onSave(){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.createCategory({description: formData.description, designation: String(formData?.designation), token: token}).subscribe(data => {
        this.alertService.AlertSucess("Categoria cadastrada com sucesso!")
        this.formulario.reset()
        this.formModalComponent.handleClose()
        this.getDatas()
      },
      err => {
        this.alertService.AlertError(err.error.message)
      }
      )
    }
  }


}
