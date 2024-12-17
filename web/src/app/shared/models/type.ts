export interface IType {
  article_types: TypeItem[]
}

export interface TypeItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
