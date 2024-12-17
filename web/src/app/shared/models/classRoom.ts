export interface IClassRoom {
  classrooms: ClassRoomItem[]
}

export interface ClassRoomItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
