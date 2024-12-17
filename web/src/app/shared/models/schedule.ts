import { ClassItem, IClass } from "./class"
import { ClassRoomItem, IClassRoom } from "./classRoom"
import { CourseItem, ICourse } from "./course"
import { DisciplineItem, IDiscipline } from "./discipline"
import { IPeriod } from "./period"
import { SchoolYearItem } from "./schoolYear"
import { ITurma, TurmaItem } from "./turma"

export interface ISchedule {
  schedules:  ScheduleItem[]
}

export interface ScheduleItem {
  id?: number,
  designation: string,
  description: string,
  discipline_id: number,
  school_year_id: number,
  course_id: number,
  turma_id: number,
  file? : string
  classe: ClassItem,
  turma: TurmaItem,
  course: CourseItem,
  class_room: ClassRoomItem,
  period: IPeriod
  school_year: SchoolYearItem
  class_room_id: number,
  classe_id: number,
  period_id: number,
  status?: number,
  token?: string
}
