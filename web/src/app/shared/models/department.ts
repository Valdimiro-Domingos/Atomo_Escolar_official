export interface IDepartament {
  departaments: DepartamentItem[]
}

export interface DepartamentItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
