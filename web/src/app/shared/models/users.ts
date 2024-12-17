import { IDepartament } from "./department"
import { IRole } from "./role"

export interface IUsers {
  users:  UserItem[]
}

export interface UserItem {
  id?: number,
  role_id: number,
  departament_id: number,
  departament: IDepartament,
  role: IRole,
  name: string,
  email: string,
  status?: number,
  token?: string
}
