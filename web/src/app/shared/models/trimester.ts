export interface ITrimester {
  trimestres: TrimesterItem[]
}

export interface TrimesterItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
