import { ClassItem } from "./class"
import { ClassRoomItem } from "./classRoom"
import { CourseItem } from "./course"
import { IPeriod } from "./period"
import { SchoolYearItem } from "./schoolYear"
import { TurmaItem } from "./turma"

export interface IRegistration {
  enrollment: RegistrationItem[]
}

export interface RegistrationItem {
  id?: number
  photo?: string
  message? : string
  enrollment_number: string
  student: {
    id: number,
    name: string,
    identity: string,
    gender: string
    photo: string,
    created_at ? :string;
    updated_at ? :string;
  }
  course_id: number
  period_id: number
  school_year_id: number
  classe_id: number
  turma_id: number
  class_room_id: number
  token?: string
  class_room: ClassRoomItem,
  course: CourseItem,
  period: IPeriod
  classe: ClassItem,
  enrollment_id?: number
  turma: TurmaItem,
  school_year: SchoolYearItem
}

