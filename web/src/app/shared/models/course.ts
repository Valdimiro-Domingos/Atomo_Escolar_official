export interface ICourse {
  courses: CourseItem[]
}

export interface CourseItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
