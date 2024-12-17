export interface ICompany {
  companies: CompanyItem | null
}

export interface CompanyItem {
  id?: number,
  designation: string,
  description: string,
  nif: string,
  foundation_date: string,
  share_capital: string,
  email: string,
  contact: string,
  representative_name: string,
  representative_identification: string,
  country: string,
  city: string,
  address: string,
  whatsapp: string,
  facebook: string,
  web_site: string,
  general_manager?: string,
  pedagogical_manager?: string,
  provincial_manager?: string,
  municipal_manager?: string,
  status?: number,
  token?: string
}
