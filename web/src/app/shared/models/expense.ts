export interface IExpense {
  expenses: ExpenseItem[]
}

export interface ExpenseItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  value: number
  token?: string
}
