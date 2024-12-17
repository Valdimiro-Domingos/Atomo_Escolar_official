export interface ITax {
  taxes: TaxItem[]
}

export interface TaxItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
