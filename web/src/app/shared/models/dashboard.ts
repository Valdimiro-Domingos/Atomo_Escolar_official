
export interface IDashboard {
  dashboard: {
    users: number,
    enrollments: number,
    expenses: number,
    revenue: number | any, 
    databases : {
      revenue : any[],
      expenses  : any[],
      enrollments: any[]
    }
  }
}
