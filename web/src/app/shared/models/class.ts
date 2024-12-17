export interface IClass {
  classe: ClassItem[]
}

export interface ClassItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
