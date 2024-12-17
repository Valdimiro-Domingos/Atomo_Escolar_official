export interface IPermission {
  permissions: PermissionItem[]
}

export interface PermissionItem {
  id?: number,
  permission: string,
  status?: number,
  token?: string
}
