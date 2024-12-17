export interface IRole {
  roles: RoleItem[]
}

export interface RoleItem {
  id?: number,
  role: string,
  guard: string,
  status?: number,
  token?: string
}
