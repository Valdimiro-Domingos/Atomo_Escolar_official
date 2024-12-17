export interface IRetention {
  retentions: RetentionItem[]
}

export interface RetentionItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
