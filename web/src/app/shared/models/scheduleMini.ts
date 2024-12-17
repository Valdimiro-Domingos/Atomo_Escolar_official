import { DisciplineItem } from "./discipline"

export interface IScheduleMini {
  mini_schedules:  ScheduleMiniItem[]
}

export interface ScheduleMiniItem {
  id?: number | string,
  file? : string | File;
  schedule_id: number,
  profeessor_id: number,
  designation: string,
  description?: string,
  trimestre_id: number,
  discipline_id: number,
  school_year?: string
  status?: number,
  period? : string,
  discipline?: DisciplineItem,
  token?: string
}
export interface ScheduleMiniUpdate {
  id?: number | string,
  schedule_id: number,
  profeessor_id: number,
  designation: string,
  description: string,
  trimestre_id: number,
  discipline_id: number,
  token?: string
}
