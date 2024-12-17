import { Component, OnInit, ViewChild } from '@angular/core';
import {
  FormGroup,
  FormBuilder,
  Validators,
  FormArray,
  AbstractControl,
} from '@angular/forms';
import * as feather from 'feather-icons';
import { FormModalComponent } from 'src/app/modules/admin/components/form-modal/form-modal.component';
import { PedagogicalService } from 'src/app/modules/pedagogical/services/pedagogical.service';
import { SecretaryService } from 'src/app/modules/secretary/services/secretary.service';
import { AlertService } from 'src/app/services/alert/alert.service';
import { AuthService } from 'src/app/services/auth/auth.service';
import { IClass } from 'src/app/shared/models/class';
import { IClassRoom } from 'src/app/shared/models/classRoom';
import { ICourse } from 'src/app/shared/models/course';
import { IRegistration } from 'src/app/shared/models/registration';
import { ISchoolYear } from 'src/app/shared/models/schoolYear';
import { ITurma } from 'src/app/shared/models/turma';
import { FinanceService } from '../../services/finance.service';
import { IPaymentForm } from 'src/app/shared/models/paymentForms';
import { UserItem } from 'src/app/shared/models/users';
import { IInvoiceReceipt } from 'src/app/shared/models/invoice-receipt';
import { ArticleItem, IArticle } from 'src/app/shared/models/article';
import { environmentSettings } from 'src/environments/environment.dev';
import { CategoryItem, ICategory } from 'src/app/shared/models/category';

@Component({
  selector: 'app-payments',
  templateUrl: './payments.component.html',
  styleUrls: ['./payments.component.css'],
})
export class PaymentsComponent implements OnInit {
  url = environmentSettings.endpoint.url;
  isLoading: boolean = false;




  @ViewChild(FormModalComponent)
  private formModalComponent!: FormModalComponent;
  base = [{ name: 'Finanças', url: '/dash/finance' }, { name: 'Fatura-Recibo', url: '/dash/finance/invoice-receipt' }]

  datas: IRegistration = {
    enrollment: [],
  };

  invocieDatas: IInvoiceReceipt = {
    invoice_receipts: [],
  };

  @ViewChild(HTMLAnchorElement) link!: HTMLAnchorElement;

  formulario!: FormGroup;
  formularioReconfirm!: FormGroup;
  formularioFormReconfirm!: FormGroup;

  invoice_number: { invoice_number: string } = {
    invoice_number: '',
  };

  categoryDatas: CategoryItem[] = [];

  articleDatas: IArticle = {
    articles: [],
  };

  paymentForms: IPaymentForm = {
    form_of_payment: [],
  };

  user: { name: '' } = {
    name: '',
  };

  heading = [
    'Categoria do Artigo',
    'Artigo',
    'Qtd',
    'Preço Unitário',
    'Desc %',
    'Multa',
    'Total',
    'Opções',
  ];

  items: any[] = [];
  studants: any = [];
  dataLocal: string = new Date().toLocaleDateString();
  constructor(
    private clientApi: SecretaryService,
    private financeService: FinanceService,
    private alertService: AlertService,
    private pedagogicalService: PedagogicalService,
    private authService: AuthService,
    private formBuilder: FormBuilder
  ) {
    this.formulario = this.formBuilder.group({
      date_of_issue: [this.dataLocal],
      due_date: [this.dataLocal],
      form_of_payment_id: ['', Validators.required],
      enrollment_id: ['', [Validators.required]],
      coin: ['Kwanza', [Validators.required]],
      itens: ['', Validators.required],
    });

    this.formulario.controls['coin'].setValue('Kwanza');

    this.formularioFormReconfirm = this.formBuilder.group({
      qtd: ['1'],
      discount: ['0'],
      article: [''],
      preco: [''],
      category: [''],
      category_id: [''],
      price: [{ value: '', disabled: true }],
      article_id: [{ value: '', disabled: false }, Validators.required],
      rate: ['0', { value: '', disabled: true }],
      paid: ['', { value: '', disabled: true }],
    });

    this.formularioReconfirm = this.formBuilder.group({
      itens: this.formBuilder.array([]),
    });
  }

  ngOnInit(): void {
    feather.replace();
    this.isLoading = true;

    this.formulario.patchValue({
      due_date: this.dataLocal,
      date_of_issue: this.dataLocal
    })

    this.getDatas();
  }

  totalDesconto: any = 0;
  createItemFormGroup(form: FormGroup) {
    if (form.invalid) return this.alertService.AlertInfo('Dados Inválidos');
    let formData = {
      ...form.value,
      preco: { value: Number(this.unitprice), disabled: false },
      price: { value: Number(this.unitprice), disabled: true },
      article_id: { value: Number(form.value.article_id), disabled: false },
      discount: { value: Number(form.value.discount), disabled: false },
      rate: Number(form.value.rate),
    };

    this.itens.push(
      this.formBuilder.group({
        ...formData,
      })
    );

    this.totalDesconto +=
      form.value.discount != 0
        ? this.unitprice -
        this.unitprice * (form.value.discount / 100) -
        this.unitprice
        : 0;
    this.totalIliquid();
    form.reset();
  }

  totalIliquid() {
    let total = 0;
    const data = this.itens.value as any[];
    data.forEach((item) => {
      total += item.paid;
    });
    return total;
  }

  get itens(): FormArray {
    return this.formularioReconfirm.get('itens') as FormArray;
  }

  // remove item da fatura
  removeItem(index: number): void {
    this.itens.at(index).value;
    this.itens.removeAt(index);

    const data = this.itens.value as any[];
    this.totalDesconto = 0;

    data.forEach((item) => {
      const total_discount =
        item.price * item.quantity -
        item.price * item.quantity * (item.discount / 100) -
        item.price * item.quantity;
      this.totalDesconto += total_discount - item.price;
    });
  }

  autoQuantidade(value: any) {
    this.formularioFormReconfirm.controls['price'].setValue(this.artigo.price);
    this.unitprice = this.artigo.price * value;
    this.formularioFormReconfirm.controls['discount'].setValue(null);
    this.formularioFormReconfirm.controls['rate'].setValue(null);
    this.formularioFormReconfirm.controls['paid'].setValue(this.unitprice);
  }

  autoPaid(value: any) {
    if (!this.unitprice) return;
    this.formularioFormReconfirm.controls['paid'].setValue(
      this.unitprice - this.unitprice * (value / 100)
    );
  }

  autoMulta(value: any) {
    if (!this.unitprice) return;
    this.formularioFormReconfirm.controls['paid'].setValue(
      this.unitprice + this.unitprice * (value / 100)
    );
  }

  // busca artigos de uma categoria
  seleciona_Estudante(item: any) {
    this.reset();
    if (item.target.value == '') {
      this.isItem = false;
      return;
    }
    this.isItem = true;
    const token = this.authService.getUser().token;
    this.financeService
      .getAllCategoryArticle(Number(item.target.value), token)
      .subscribe((data) => {
        const response = data as CategoryItem[];
        this.categoryDatas = response;
      });
  }

  // selecionar artigos da categoria
  buscarCategory(item: any) {
    this.reset();
    const data = this.categoryDatas.find(
      (itens) => itens.id == item?.target?.value
    );
    this.formularioFormReconfirm.controls['category'].setValue(
      data?.designation
    );

    this.articles = data?.article || [];
  }

  // seleciona artigo
  artigoSelect(item: any, form: FormGroup) {
    this.reset();
    this.artigo = this.articles.find(
      (itens) => itens.id == item?.target?.value
    );
    this.autoQuantidade(1);

    this.formularioFormReconfirm.controls['article'].setValue(
      this.artigo.designation
    );
    this.formularioFormReconfirm.controls['qtd'].setValue(1);
    this.formularioFormReconfirm.controls['discount'].setValue(0);
    this.formularioFormReconfirm.controls['rate'].setValue(0);
  }

  printPDF(item: any) {
    return `${this.url}/exportacao/invoice_receipts/${item.id}`;
  }

  getDatas() {
    const token = this.authService.getUser().token;
    // this.user = this.authService.getUser().user;
    // this.clientApi.getAllConfirmation(token).subscribe((data) => {

    //   const response = data as IRegistration;
    //   this.datas.enrollment = response.enrollment;
    // });
    
    // this.financeService.getInvoice_number(token).subscribe((data) => {
    //   const response = data as { invoice_number: string };
    //   this.invoice_number.invoice_number = response.invoice_number;
    // });

    // this.financeService.getPaymentForms(token).subscribe((data) => {
    //   const response = data as IPaymentForm;
    //   this.paymentForms.form_of_payment = response.form_of_payment;
    // });
    
    // this.financeService.getAllArticle(token).subscribe((data) => {
      //   if (data) {
        //     const response = data as IArticle;
        //     this.articleDatas.articles = response.articles;
        //   }
        // });
        
    this.financeService.getAllInvoiceView(token).subscribe((data) => {
      this.isLoading = false;
      this.invocieDatas.invoice_receipts = data?.invoice_receipts;
      this.studants = data?.studants;
      this.articleDatas.articles = data?.articles
      this.paymentForms.form_of_payment = data?.form_payment;
      this.invoice_number.invoice_number = data.invoice_number;
    });

  }

  handleRemove(id: number) {
    const token = this.authService.getUser().token;
    this.clientApi.deleteRegistration(id, token).subscribe(
      (data) => {
        if (data) {
          this.alertService.AlertSucess('Matrícula deletada com sucesso!');
          this.getDatas();
        }
      },
      (err) => {
        this.alertService.AlertError(err.error.message);
      }
    );
  }

  patchValues(item: any) {
    this.formulario.patchValue({
      ...item,
    });
  }

  clear() {
    this.formulario.reset();
    this.formularioFormReconfirm.reset();
    this.formularioReconfirm.reset();
    this.itens.clear();
  }

  patchValuesReconfirm() {
    const token = this.authService.getUser().token;
    if (!this.formularioReconfirm.value.enrollment_id) return;
    this.clientApi
      .getRegistration(this.formularioReconfirm.value.enrollment_id, token)
      .subscribe(
        (data) => {
          const response = data as { enrollment: any };
          this.formularioReconfirm.patchValue({
            ...response.enrollment,
            name: response.enrollment.student.name,
            gender: response.enrollment.student.gender,
            identity: response.enrollment.student.identity,
            student_id: response.enrollment.student.id,
          });
          this.formularioReconfirm.patchValue({});
        },
        (err) => {
          this.alertService.AlertError('Lamentamos');
        }
      );
  }

  onSave() {
    this.formulario.controls['itens'].setValue({ ...this.formularioReconfirm.value })
    this.formulario;
    if (this.formulario.valid) {
      const formData = {
        ...this.formulario.value,
        form_of_payment_id: Number(this.formulario.value.form_of_payment_id),
        enrollment_id: Number(this.formulario.value.enrollment_id),
        itens: this.formularioReconfirm.value.itens,
      };
      const token = this.authService.getUser().token;
      this.financeService
        .createInvoice({
          ...formData,
          date_of_issue: this.dataLocal,
          invoice_number: this.invoice_number.invoice_number,
          token: token,
        })
        .subscribe(
          (data) => {
            this.alertService.AlertSucess('Factura criada com sucesso!');
            this.formularioReconfirm.reset();
            this.formulario.reset();
            this.formularioFormReconfirm.reset();
            this.formModalComponent.handleClose();
            this.itens.clear();
            this.getDatas();
          },
          (err) => {
            alert(err.error.message);
            // this.formularioReconfirm.reset()
            // this.formulario.reset()
            // this.formularioFormReconfirm.reset()
            // this.itens.clear()
          }
        );
    } else {
      this.alertService.AlertInfo('Preencha todos os campos');
    }
  }

  onReconfirm() {
    if (this.formularioReconfirm.valid) {
      const formData = this.formularioReconfirm.value;
      const token = this.authService.getUser().token;
      this.clientApi
        .createConfirmation({
          ...formData,
          enrollment_id: formData.enrollment_id,
          token: token,
        })
        .subscribe(
          (data) => {
            this.alertService.AlertSucess('Confirmação feita com sucesso!');
            this.formularioReconfirm.reset();
            this.formModalComponent.handleClose();
            this.getDatas();
          },
          (err) => {
            alert(err.error.message);
          }
        );
    }
  }

  artigo: any = {};
  pricevalue: any;
  unitprice: any;
  articles: ArticleItem[] = [];

  isItem: boolean = false;

  toogleContainer() {
    if (this.categoryDatas.length > 0) {
      this.isItem = !this.isItem;
    }
  }

  reset() {
    this.formularioFormReconfirm.controls['discount'].setValue(null);
    this.formularioFormReconfirm.controls['rate'].setValue(null);
    this.formularioFormReconfirm.controls['price'].setValue(null);
    this.formularioFormReconfirm.controls['paid'].setValue(null);
    this.unitprice = null;
    this.artigo = null;
  }

  onClose() {
    this.formModalComponent.handleClose();
  }
}
