export interface ISchoolYear {
  school_year: SchoolYearItem[]
}

export interface SchoolYearItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
