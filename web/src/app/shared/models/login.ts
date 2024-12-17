export interface ILogin {
  user: {
    id: number
    name: string,
    email: string,
    role_id: number,
    departament_id: number
  },
  token?: string
}
