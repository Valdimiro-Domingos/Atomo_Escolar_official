export interface IDiscipline {
  disciplines: DisciplineItem[]
}

export interface DisciplineItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
