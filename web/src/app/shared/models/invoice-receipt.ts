
export interface IInvoiceReceipt {
  invoice_receipts: InvoiceReceiptItem[]
}

export interface InvoiceReceiptItem {
  id: number,
  client_name: string,
  invoice_number: string,
  date_of_issue: string,
  due_date: string,
  student_id: number,
  form_of_payment_id: number,
  enrollment_id: number,
  coin: string,
  itens: Item[],
  student: any
}


interface Item {
  qtd: number,
  discount: number,
  rate: number,
  paid: number,
  article_id: number
}
