export interface IBank {
  company_bank: BankItem[]
}

export interface BankItem {
  id?: number,
  name: string
  account_number: string,
  iban: string,
  swift: string,
  status?: number,
  token?: string
}
