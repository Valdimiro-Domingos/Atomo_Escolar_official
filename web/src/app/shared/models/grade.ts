export interface IGrade {
  grades: gradeItem[]
}

export interface gradeItem {
  id: number,
  student_id: number,
  mini_schedule_id: number,
  continuous_evaluation_average: number,
  teachers_test_score: number,
  quarterly_test_score: number,
  quarterly_average: number,

  student? : {
    id: number,
    name: string,
    identity: string,
    gender: string
    photo: string,
    created_at ? :string;
    updated_at ? :string;
  }
}
export interface IEstudent {
  id: number,
  name: string,
  identity: string,
  gender: string
  photo: string,
  created_at ? :string;
  updated_at ? :string;
}