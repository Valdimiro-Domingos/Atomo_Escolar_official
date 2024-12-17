import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IArticle } from 'src/app/shared/models/article';
import { IClass } from 'src/app/shared/models/class';
import { FinanceService } from '../../services/finance.service';
import { ITax } from 'src/app/shared/models/tax';
import { ICategory } from 'src/app/shared/models/category';
import { IType } from 'src/app/shared/models/type';
import { IRetention } from 'src/app/shared/models/retention';
import { AlertService } from 'src/app/services/alert/alert.service';

@Component({
  selector: 'app-articles',
  templateUrl: './articles.component.html',
  styleUrls: ['./articles.component.css']
})
export class ArticlesComponent implements OnInit {
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
  base = [{ name: 'Finanças',  url: '/dash/finance' }, { name: 'Artigos',  url: '/dash/finance/article' }]

  datas: IArticle = {
    articles : []
  }

  taxDatas: ITax = {
    taxes: []
  }

  categoryDatas: ICategory = {
    article_categories: []
  }

  articleDatas: IType = {
    article_types: []
  }

  retentionDatas: IRetention = {
    retentions: []
  }

  formulario!: FormGroup
  isLoading: boolean = false

  constructor(private clientApi: FinanceService, private alertService: AlertService, private authService: AuthService, private formBuilder: FormBuilder) { }

  ngOnInit(): void {
    this.isLoading = true
    this.formulario = this.formBuilder.group({
      designation: ['', [Validators.required]],
      description: [''],
      article_type_id: ['Selecione o tipo de artigo', [Validators.required]],
      article_category_id: ['Selecione a categoria', [Validators.required]],
      retention_id: ['Selecione a retenção', [Validators.required]],
      tax_id: ['Selecione a taxa', [Validators.required]],
      price: ['', [Validators.required]],
    });
    this.getDatas()
  }

  getDatas(){
    const token = this.authService.getUser().token
    this.clientApi.getAllArticle(token).subscribe(data => {
      const response = data as IArticle
      this.datas.articles = response.articles
    })

    this.clientApi.getAllTax(token).subscribe(data => {
      const response = data as ITax
      this.taxDatas.taxes = response.taxes
    })

    this.clientApi.getAllCategory(token).subscribe(data => {
      const response = data as ICategory
      this.categoryDatas.article_categories = response.article_categories
    })

    this.clientApi.getAllType(token).subscribe(data => {
      const response = data as IType
      this.articleDatas.article_types = response.article_types
    })

    this.clientApi.getAllRetention(token).subscribe(data => {
      const response = data as IRetention
      this.retentionDatas.retentions = response.retentions
      this.isLoading = false
    }, err => {
      this.isLoading = false
    })
  }

  handleRemove(id: number){
    const token = this.authService.getUser().token
    this.clientApi.deleteArticle(id, token).subscribe(data => {
      if(data){
        this.alertService.AlertSucess("Artigo deletado com sucesso!")
        this.getDatas()
      }
    },
      err => {
        this.alertService.AlertError(err.error.message)
      }
    )
  }

  patchValues(item: any) {
    this.formulario.patchValue({
      designation: item.designation,
      description: item.description,
      article_type_id: item.article_type_id,
      article_category_id: item.article_category_id,
      retention_id: item.retention_id,
      tax_id: item.tax_id,
      price: item.price,
    });
  }


  onUpdate(item: any){
    if (this.formulario.valid) {
      const formData = this.formulario.value
      const token = this.authService.getUser().token
      this.clientApi.updateArticle({...formData, description: formData.description, designation: String(formData?.designation), token: token, id: item.id}).subscribe(data => {
        this.alertService.AlertSucess("Artigo actualizado com sucesso!")
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
      this.clientApi.createArticle({...formData, description: formData.description, designation: String(formData?.designation), token: token}).subscribe(data => {
        this.alertService.AlertSucess("Artigo cadastrado com sucesso!")
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
