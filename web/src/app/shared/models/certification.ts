export interface Certification {
  id: number;
  enrollment_number: string;
  student: string;
  school_year: string;
  classe: string;
  turma: string;
  course: string;
  class_room: string;
  period: string;
  certification: string | null;
}

export interface getCertification{
    students : Certification[],
    items : ItemsCertification
}

// src/app/models/items.model.ts

 interface Turma {
  id: number;
  designation: string;
  description: string | null;
  status: string;
}

 interface Classe {
  id: number;
  designation: string;
  description: string;
  status: string;
}

 interface SchoolYear {
  id: number;
  designation: string;
  description: string;
  status: string;
  company_id: number;
  created_at: string;
  updated_at: string;
}

 interface Course {
  id: number;
  designation: string;
  description: string | null;
  status: string;
}

interface Classroom {
  id: number;
  designation: string;
  description: string | null;
  status: string;
}

interface Period {
  id: number;
  time_start: string;
  time_end: string;
  designation: string;
  description: string;
  company_id: number;
}

interface Professor {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  status: string;
  company_id: number;
  role_id: number;
  departament_id: number;
  created_at: string;
  updated_at: string;
}

interface Trimestre {
  id: number;
  designation: string;
  description: string;
  status: string;
}

export interface ItemsCertification {
  turmas: Turma[];
  classes: Classe[];
  school_year: SchoolYear[];
  courses: Course[];
  company_id: number;
  classrooms: Classroom[];
  periods: Period[];
  professores: Professor[];
  trimestres: Trimestre[];
}
