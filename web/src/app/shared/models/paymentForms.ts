export interface IPaymentForm {
  form_of_payment: PaymentItem[]
}

export interface PaymentItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
