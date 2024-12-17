import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ArticleItem } from 'src/app/shared/models/article';
import { CategoryItem } from 'src/app/shared/models/category';
import { RetentionItem } from 'src/app/shared/models/retention';
import { environmentSettings } from 'src/environments/environment.dev';

@Injectable({
  providedIn: 'root'
})
export class FinanceService {
  apiUrl = environmentSettings.endpoint.url
  constructor(private http: HttpClient) { }

  createArticle(data: ArticleItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/article/store`, data, { headers: headers })
  }

  getAllArticle(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article`, {headers: headers})
  }

  getArticle(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/show/${id}`, {headers: headers})
  }

  deleteArticle(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/finance/article/destroy/${id}`, {headers: headers})
  }

  updateArticle(data: ArticleItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/finance/article/update/${data.id}`, data, { headers: headers })
  }


  createCategory(data: CategoryItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/article/category/store`, data, { headers: headers })
  }

  getAllCategory(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/category`, {headers: headers})
  }

  getAllCategoryArticle(id:number,token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/invoice_receipt/student/${id}`, {headers: headers})
  }

  getCategory(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/category/show/${id}`, {headers: headers})
  }

  deleteCategory(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/finance/article/category/destroy/${id}`, {headers: headers})
  }

  updateCategory(data:  CategoryItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/finance/article/category/update/${data.id}`, data, { headers: headers })
  }


  createTax(data: CategoryItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/article/tax/store`, data, { headers: headers })
  }

  getAllTax(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/tax`, {headers: headers})
  }

  getTax(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/tax/show/${id}`, {headers: headers})
  }

  deleteTax(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/finance/article/tax/destroy/${id}`, {headers: headers})
  }

  updateTax(data:  CategoryItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/finance/article/tax/update/${data.id}`, data, { headers: headers })
  }


  createRetention(data:RetentionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/article/retention/store`, data, { headers: headers })
  }

  getAllRetention(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/retention`, {headers: headers})
  }

  getRetention(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/retention/show/${id}`, {headers: headers})
  }

  deleteRetention(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/finance/article/retention/destroy/${id}`, {headers: headers})
  }

  updateRetention(data: RetentionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/finance/article/retention/update/${data.id}`, data, { headers: headers })
  }


  createType(data:RetentionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/article/type/store`, data, { headers: headers })
  }

  createInvoice(data:any) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/invoice_receipt/store`, data, { headers: headers })
  }

  getAllType(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/type`, {headers: headers})
  }

  getType(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/article/type/show/${id}`, {headers: headers})
  }

  deleteType(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/finance/article/type/destroy/${id}`, {headers: headers})
  }

  updateType(data: RetentionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/finance/article/type/update/${data.id}`, data, { headers: headers })
  }

  getInvoice_number(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/invoice_receipt/invoice_number`, {headers: headers})
  }

  getPaymentForms(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/form_of_payment`, {headers: headers})
  }

  getAllInvoiceView(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get<{
      articles : any,
      categories : any,
      form_payment : any,
      invoice_receipts : any,
      studants : any,
      invoice_number : any
    }>(`${this.apiUrl}/finance/invoice_receipt/view`, {headers: headers})
  }

  getAllInvoice(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/invoice_receipt`, {headers: headers})
  }

  printInvoiceReceipt(id:number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Access-Control-Allow-Origin': '*'
    });

    return this.http.get(`http://192.168.1.74:8001/api/pdf/get_mini_schedule_pdf/1`, {headers: headers, responseType: 'blob', withCredentials: true})
  }

  createExpense(data:RetentionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.post(`${this.apiUrl}/finance/expenses/store`, data, { headers: headers })
  }

  getAllExpense(token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/expenses`, {headers: headers})
  }

  getExpense(id: string, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.apiUrl}/finance/expenses/${id}`, {headers: headers})
  }

  deleteExpense(id: number, token: string){
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.delete(`${this.apiUrl}/finance/expenses/destroy/${id}`, {headers: headers})
  }

  updateExpense(data: RetentionItem) {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${data.token}`
    });
    return this.http.put(`${this.apiUrl}/finance/expenses/update/${data.id}`, data, { headers: headers })
  }
  
  
  
  
  // Bribes | &dateOf=${data.dateOf}&dateTo=${data.dateTo}
  bribesAll(data: any) {
    var url = (`${this.apiUrl}/finance/bribes/students_company?document=${data.document}&companyId=${data.companyId}`);
    window.open(url, '_blank');
  }
  
  
  
  reportsPayment(data: any) {
    var url = (`${environmentSettings.endpoint.url}/finance/state/students_company?companyId=${data.companyId}&dateOf=${data.dateOf}&dateTo=${data.dateTo}`);
    window.open(url, '_blank');
  }
  
  
  
}
